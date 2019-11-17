<?php /** @noinspection PhpUnused */


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
    use IntellivoidAccounts\Abstracts\SearchMethods\SubscriptionPromotionSearchMethod;
    use IntellivoidAccounts\Abstracts\SubscriptionPromotionStatus;
    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\InvalidCyclePriceException;
    use IntellivoidAccounts\Exceptions\InvalidFeatureException;
    use IntellivoidAccounts\Exceptions\InvalidInitialPriceException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\Exceptions\InvalidSubscriptionPromotionNameException;
    use IntellivoidAccounts\Exceptions\SubscriptionPromotionAlreadyExistsException;
    use IntellivoidAccounts\Exceptions\SubscriptionPromotionNotFoundException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\Subscription\Feature;
    use IntellivoidAccounts\Objects\SubscriptionPromotion;
    use IntellivoidAccounts\Utilities\Converter;
    use IntellivoidAccounts\Utilities\Hashing;
    use IntellivoidAccounts\Utilities\Validate;
    use msqg\QueryBuilder;
    use ZiProto\ZiProto;

    /**
     * Class SubscriptionPromotionManager
     * @package IntellivoidAccounts\Managers
     */
    class SubscriptionPromotionManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * SubscriptionPromotionManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        /**
         * Creates a new subscription promotion record in the database
         *
         * @param int $subscription_plan_id
         * @param string $promotion_code
         * @param int $affiliation_account_id
         * @param float $affiliation_initial_share
         * @param float $affiliation_cycle_share
         * @param array $features
         * @return SubscriptionPromotion
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws InvalidCyclePriceException
         * @throws InvalidFeatureException
         * @throws InvalidInitialPriceException
         * @throws InvalidSearchMethodException
         * @throws InvalidSubscriptionPromotionNameException
         * @throws SubscriptionPromotionNotFoundException
         * @throws SubscriptionPromotionAlreadyExistsException
         */
        public function createSubscriptionPromotion(int $subscription_plan_id, string $promotion_code, int $affiliation_account_id, float $affiliation_initial_share, float $affiliation_cycle_share, array $features): SubscriptionPromotion
        {
            $promotion_code = Converter::subscriptionPromotionCode($promotion_code);
            if(Validate::subscriptionPromotionCode($promotion_code) == false)
            {
                throw new InvalidSubscriptionPromotionNameException();
            }

            try
            {
                $this->getSubscriptionPromotion(SubscriptionPromotionSearchMethod::byPromotionCode, $promotion_code);
                throw new SubscriptionPromotionAlreadyExistsException();
            }
            catch(SubscriptionPromotionNotFoundException $e)
            {
                unset($e);
            }

            if($affiliation_account_id == 0)
            {
                $affiliation_initial_share = (float)0;
                $affiliation_cycle_share = (float)0;
            }
            else
            {
                $this->intellivoidAccounts->getAccountManager()->getAccount(AccountSearchMethod::byId, $affiliation_account_id);

                if($affiliation_cycle_share < 0)
                {
                    throw new InvalidCyclePriceException();
                }

                if($affiliation_initial_share < 0)
                {
                    throw new InvalidInitialPriceException();
                }
            }

            $public_id = Hashing::SubscriptionPromotionPublicID((int)$subscription_plan_id, $promotion_code);
            /** @var Feature $feature */
            foreach($features as $feature)
            {
                if($feature->Name == null)
                {
                    throw new InvalidFeatureException();
                }

                if($feature->Value == null)
                {
                    throw new InvalidFeatureException();
                }
            }

            $decoded_features = array();
            /** @var Feature $feature */
            foreach($features as $feature)
            {
                $decoded_features[] = $feature->toArray();
            }

            $decoded_features = ZiProto::encode($decoded_features);
            $decoded_features = $this->intellivoidAccounts->database->real_escape_string($decoded_features);
            $public_id = $this->intellivoidAccounts->database->real_escape_string($public_id);
            $promotion_code = $this->intellivoidAccounts->database->real_escape_string($promotion_code);
            $flags = ZiProto::encode([]);
            $flags = $this->intellivoidAccounts->database->real_escape_string($flags);
            $last_updated_timestamp = (int)time();
            $created_timestamp = $last_updated_timestamp;

            $Query = QueryBuilder::insert_into('subscription_promotions', array(
                'public_id' => $public_id,
                'promotion_cde' => $promotion_code,
                'subscription_plan_id' => (int)$subscription_plan_id,
                'affiliation_account_id' => (int)$affiliation_account_id,
                'affiliation_initial_share' => (float)$affiliation_initial_share,
                'affiliation_cycle_share' => (float)$affiliation_cycle_share,
                'features' => $decoded_features,
                'status' => (int)SubscriptionPromotionStatus::Active,
                'flags' => $flags,
                'last_updated_timestamp' => $last_updated_timestamp,
                'created_timestamp' => $created_timestamp
            ));

            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }

            return $this->getSubscriptionPromotion(SubscriptionPromotionSearchMethod::byPromotionCode, $public_id);
        }

        /**
         * Returns the subscription promotion from the database
         *
         * @param string $search_method
         * @param string $value
         * @return SubscriptionPromotion
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws InvalidSubscriptionPromotionNameException
         * @throws SubscriptionPromotionNotFoundException
         */
        public function getSubscriptionPromotion(string $search_method, string $value): SubscriptionPromotion
        {
            switch($search_method)
            {
                case SubscriptionPromotionSearchMethod::byId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                case SubscriptionPromotionSearchMethod::byPublicId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = $this->intellivoidAccounts->database->real_escape_string($value);
                    break;

                case SubscriptionPromotionSearchMethod::byPromotionCode:
                    if(Validate::subscriptionPromotionCode($value) == false)
                    {
                        throw new InvalidSubscriptionPromotionNameException();
                    }
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = Converter::subscriptionPromotionCode($value);
                    $value = $this->intellivoidAccounts->database->real_escape_string($value);
                    break;

                default:
                    throw new InvalidSearchMethodException();
            }

            $Query = QueryBuilder::select('subscription_promotions', [
                'id',
                'public_id',
                'promotion_code',
                'subscription_plan_id',
                'affiliation_account_id',
                'affiliation_initial_share',
                'affiliation_cycle_share',
                'features',
                'status',
                'flags',
                'last_updated_timestamp',
                'created_timestamp'
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
                    throw new SubscriptionPromotionNotFoundException();
                }

                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);
                $Row['features'] = ZiProto::decode($Row['features']);
                $Row['flags'] = ZiProto::decode($Row['flags']);
                return SubscriptionPromotion::fromArray($Row);
            }
        }
    }