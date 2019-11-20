<?php /** @noinspection PhpUnused */


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\AccountStatus;
    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
    use IntellivoidAccounts\Abstracts\SearchMethods\SubscriptionPromotionSearchMethod;
    use IntellivoidAccounts\Abstracts\SearchMethods\SubscriptionSearchMethod;
    use IntellivoidAccounts\Exceptions\AccountLimitedException;
    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\Exceptions\InvalidSubscriptionPromotionNameException;
    use IntellivoidAccounts\Exceptions\SubscriptionPlanNotFoundException;
    use IntellivoidAccounts\Exceptions\SubscriptionPromotionNotFoundException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\Subscription;
    use IntellivoidAccounts\Utilities\Hashing;
    use msqg\QueryBuilder;
    use ZiProto\ZiProto;

    /**
     * Class SubscriptionManager
     * @package IntellivoidAccounts\Managers
     */
    class SubscriptionManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * SubscriptionManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        /**
         * Starts a new subscription for the account
         *
         * @param int $account_id
         * @param int $application_id
         * @param string $plan_name
         * @param string $promotion_code
         * @return Subscription
         * @throws AccountLimitedException
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws InvalidSubscriptionPromotionNameException
         * @throws SubscriptionPlanNotFoundException
         * @throws SubscriptionPromotionNotFoundException
         */
        public function startSubscription(int $account_id, int $application_id, string $plan_name, string $promotion_code = "NONE"): Subscription
        {
            // Retrieve the required information
            $Account = $this->intellivoidAccounts->getAccountManager()->getAccount(AccountSearchMethod::byId, $account_id);
            if($Account->Status == AccountStatus::Limited)
            {
                throw new AccountLimitedException();
            }

            $SubscriptionPlan = $this->intellivoidAccounts->getSubscriptionPlanManager()->getSubscriptionPlanByName(
                $application_id, $plan_name
            );


            $properties = new Subscription\Properties();
            $SubscriptionPromotion = null;

            if($promotion_code !== "NONE")
            {
                $SubscriptionPromotion = $this->intellivoidAccounts->getSubscriptionPromotionManager()->getSubscriptionPromotion(
                    SubscriptionPromotionSearchMethod::byPromotionCode, $promotion_code
                );
            }

            if(count($SubscriptionPlan->Features) > 0)
            {
                foreach($SubscriptionPlan->Features as $feature)
                {
                    $properties->addFeature($feature);
                }
            }

            if($SubscriptionPromotion == null)
            {
                $properties->InitialPrice = $SubscriptionPlan->InitialPrice;
                $properties->CyclePrice = $SubscriptionPlan->CyclePrice;
                $properties->PromotionID = 0;
            }
            else
            {
                $properties->InitialPrice = $SubscriptionPromotion->InitialPrice;
                $properties->CyclePrice = $SubscriptionPromotion->CyclePrice;
                $properties->PromotionID = $SubscriptionPromotion->ID;

                if(count($SubscriptionPromotion->Features) > 0)
                {
                    foreach($SubscriptionPromotion->Features as $feature)
                    {
                        $properties->addFeature($feature);
                    }
                }
            }


            $public_id = Hashing::SubscriptionPublicID($account_id, $SubscriptionPlan->ID);
            $public_id = $this->intellivoidAccounts->database->real_connect($public_id);
            $account_id = (int)$account_id;
            $subscription_plan_id = (int)$SubscriptionPlan->ID;
            $active = (int)True;
            $billing_cycle = (int)$SubscriptionPlan->BillingCycle;
            $next_billing_cycle = (int)time() + $billing_cycle;
            $properties = ZiProto::encode($properties->toArray());
            $properties = $this->intellivoidAccounts->database->real_escape_string($properties);
            $created_timestamp = (int)time();
            $flags = ZiProto::encode([]);
            $flags = $this->intellivoidAccounts->database->real_escape_string($flags);

            $Query = QueryBuilder::insert_into('subscriptions', array(
                'public_id' => $public_id,
                'account_id' => (int)$account_id,
                'subscription_plan_id' => (int)$subscription_plan_id,
                'active' => $active,
                'billing_cycle' => $billing_cycle,
                'next_billing_cycle' => $next_billing_cycle,
                'properties' => $properties,
                'created_timestamp' => $created_timestamp,
                'flags' => $flags
            ));
            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }
        }

        /**
         * Returns the subscription object from the database
         *
         * @param string $search_method
         * @param string $value
         * @return Subscription
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws SubscriptionPlanNotFoundException
         */
        public function getSubscription(string $search_method, string $value): Subscription
        {
            switch($search_method)
            {
                case SubscriptionSearchMethod::byId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                case SubscriptionSearchMethod::byPublicId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = $this->intellivoidAccounts->database->real_escape_string($value);
                    break;

                default:
                    throw new InvalidSearchMethodException();
            }

            $Query = QueryBuilder::select('subscriptions', [
                'id',
                'public_id',
                'account_id',
                'subscription_plan_id',
                'active',
                'billing_cycle',
                'next_billing_cycle',
                'properties',
                'created_timestamp',
                'flags'
            ], $search_method, $value);
            $QueryResults = $this->intellivoidAccounts->database->query($Query);

            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }
            else
            {
                if($QueryResults->num_rows !== 1)
                {
                    throw new SubscriptionPlanNotFoundException();
                }

                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);
                $Row['flags'] = ZiProto::decode($Row['flags']);
                return Subscription::fromArray($Row);
            }
        }
    }