<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationSearchMethod;
    use IntellivoidAccounts\Exceptions\InvalidCyclePriceException;
    use IntellivoidAccounts\Exceptions\InvalidInitialPriceException;
    use IntellivoidAccounts\Exceptions\InvalidSubscriptionPlanNameException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\SubscriptionPlan;
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

            $this->intellivoidAccounts->getApplicationManager()->getApplication(ApplicationSearchMethod::byId, $application_id);


        }
    }