<?php /** @noinspection PhpUnused */


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\AccountStatus;
    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
    use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationSearchMethod;
    use IntellivoidAccounts\Abstracts\SearchMethods\SubscriptionPromotionSearchMethod;
    use IntellivoidAccounts\Exceptions\AccountLimitedException;
    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\ApplicationNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\InsufficientFundsException;
    use IntellivoidAccounts\Exceptions\InvalidAccountStatusException;
    use IntellivoidAccounts\Exceptions\InvalidEmailException;
    use IntellivoidAccounts\Exceptions\InvalidFundsValueException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\Exceptions\InvalidSubscriptionPromotionNameException;
    use IntellivoidAccounts\Exceptions\InvalidUsernameException;
    use IntellivoidAccounts\Exceptions\InvalidVendorException;
    use IntellivoidAccounts\Exceptions\SubscriptionPlanNotFoundException;
    use IntellivoidAccounts\Exceptions\SubscriptionPromotionNotFoundException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\Subscription;
    use IntellivoidAccounts\Utilities\Hashing;
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
         * @throws ApplicationNotFoundException
         * @throws InsufficientFundsException
         * @throws InvalidAccountStatusException
         * @throws InvalidEmailException
         * @throws InvalidFundsValueException
         * @throws InvalidUsernameException
         * @throws InvalidVendorException
         */
        public function startSubscription(int $account_id, int $application_id, string $plan_name, string $promotion_code = "NONE"): Subscription
        {
            // Retrieve the required information
            $Application = $this->intellivoidAccounts->getApplicationManager()->getApplication(ApplicationSearchMethod::byId, $application_id);
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
            $started_timestamp = (int)time();

        }

    }