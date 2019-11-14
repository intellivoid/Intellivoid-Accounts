<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationSearchMethod;
    use IntellivoidAccounts\Exceptions\ApplicationNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\InvalidBillingCycleException;
    use IntellivoidAccounts\Exceptions\InvalidCyclePriceException;
    use IntellivoidAccounts\Exceptions\InvalidFeatureException;
    use IntellivoidAccounts\Exceptions\InvalidInitialPriceException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\Exceptions\InvalidSubscriptionPlanNameException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\Subscription\Feature;
    use IntellivoidAccounts\Objects\SubscriptionPlan;
    use IntellivoidAccounts\Utilities\Converter;
    use IntellivoidAccounts\Utilities\Validate;

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

            // TODO: Check if the subscription already exists
        }
    }