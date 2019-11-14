<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationSearchMethod;
    use IntellivoidAccounts\Abstracts\SearchMethods\SubscriptionPlanSearchMethod;
    use IntellivoidAccounts\Abstracts\SubscriptionPlanStatus;
    use IntellivoidAccounts\Exceptions\ApplicationNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\InvalidBillingCycleException;
    use IntellivoidAccounts\Exceptions\InvalidCyclePriceException;
    use IntellivoidAccounts\Exceptions\InvalidFeatureException;
    use IntellivoidAccounts\Exceptions\InvalidInitialPriceException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\Exceptions\InvalidSubscriptionPlanNameException;
    use IntellivoidAccounts\Exceptions\SubscriptionPlanNotFoundException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\Subscription\Feature;
    use IntellivoidAccounts\Objects\SubscriptionPlan;
    use IntellivoidAccounts\Utilities\Converter;
    use IntellivoidAccounts\Utilities\Hashing;
    use IntellivoidAccounts\Utilities\Validate;
    use msqg\QueryBuilder;
    use ZiProto\ZiProto;

    /**
     * Class SubscriptionPlanManager
     * @package IntellivoidAccounts\Managers
     */
    class SubscriptionPlanManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * SubscriptionPlanManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        /**
         * Creates a new subscription plan
         *
         * @param int $application_id
         * @param string $name
         * @param array $features
         * @param float $initial_price
         * @param float $cycle_price
         * @param int $billing_cycle
         * @return SubscriptionPlan
         * @throws InvalidBillingCycleException
         * @throws InvalidCyclePriceException
         * @throws InvalidFeatureException
         * @throws InvalidInitialPriceException
         * @throws InvalidSubscriptionPlanNameException
         * @throws ApplicationNotFoundException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         */
        public function createSubscriptionPlan(int $application_id, string $name, array $features, float $initial_price, float $cycle_price, int $billing_cycle): SubscriptionPlan
        {
            if(Validate::subscriptionPlanName($name) == false)
            {
                throw new InvalidSubscriptionPlanNameException();
            }

            if($initial_price < 0)
            {
                throw new InvalidInitialPriceException();
            }

            if($cycle_price < 0)
            {
                throw new InvalidCyclePriceException();
            }

            if($billing_cycle < 0)
            {
                throw new InvalidBillingCycleException();
            }

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

            $this->intellivoidAccounts->getApplicationManager()->getApplication(ApplicationSearchMethod::byId, $application_id);

            $PublicID = Hashing::SubscriptionPlanPublicID((int)$application_id, $name);
            $PublicID = $this->intellivoidAccounts->database->real_escape_string($PublicID);
            $PlanName = $this->intellivoidAccounts->database->real_escape_string($name);
            $decodedFeatures = array();
            /** @var Feature $feature */
            foreach($features as $feature)
            {
                $decodedFeatures[] = $feature->toArray();
            }
            $decodedFeatures = ZiProto::encode($decodedFeatures);
            $decodedFeatures = $this->intellivoidAccounts->database->real_escape_string($decodedFeatures);
            $flags = ZiProto::encode([]);
            $flags = $this->intellivoidAccounts->database->real_escape_string($flags);
            $last_updated = (int)time();
            $created_timestamp = $last_updated;

            $Query = QueryBuilder::insert_into('subscription_plans', array(
                'public_id' => $PublicID,
                'application_id' => (int)$application_id,
                'plan_name' => $PlanName,
                'features' => $decodedFeatures,
                'initial_price' => (float)$initial_price,
                'cycle_price' => (float)$cycle_price,
                'billing_cycle' => (int)$billing_cycle,
                'status' => SubscriptionPlanStatus::Available,
                'flags' => $flags,
                'last_updated' => $last_updated,
                'created_timestamp' => $created_timestamp
            ));
            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }

            return $this->getSubscriptionPlan(SubscriptionPlanSearchMethod::byPublicId, $PublicID);
        }

        /**
         * Returns an existing SubscriptionPlan object from the database
         *
         * @param string $search_method
         * @param string $value
         * @return SubscriptionPlan
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws SubscriptionPlanNotFoundException
         */
        public function getSubscriptionPlan(string $search_method, string $value): SubscriptionPlan
        {
            switch($search_method)
            {
                case SubscriptionPlanSearchMethod::byId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                case SubscriptionPlanSearchMethod::byPublicId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = (int)$this->intellivoidAccounts->database->real_escape_string($value);
                    break;

                default:
                    throw new InvalidSearchMethodException();
            }

            $Query = QueryBuilder::select('subscription_plans', [
                'id',
                'public_id',
                'application_id',
                'plan_name',
                'features',
                'initial_price',
                'cycle_price',
                'billing_cycle',
                'status',
                'flags',
                'last_updated',
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
                    throw new SubscriptionPlanNotFoundException();
                }

                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);
                $Row['features'] = ZiProto::decode($Row['features']);
                $Row['flags'] = ZiProto::decode($Row['flags']);
                return SubscriptionPlan::fromArray($Row);
            }
        }
    }