<?php

    namespace IntellivoidAccounts\Objects\Account\Configuration;

    use IntellivoidAccounts\Abstracts\OpenBluPlan;

    /**
     * Class OpenBlu
     * @package IntellivoidAccounts\Objects\Account\Configuration
     */
    class OpenBlu
    {
        /**
         * The current plan of the API
         *
         * @var OpenBluPlan|int
         */
        public $CurrentPlan;

        /**
         * The next billing cycle
         *
         * @var int
         */
        public $NexCycle;

        /**
         * The price that gets charged each month
         *
         * @var float
         */
        public $Price;

        /**
         * Indicates if the current plan is active or not, this can
         * deactivate due to not paying the billing cycle
         *
         * @var bool
         */
        public $Active;

        /**
         * Indicates if the system has permission to take money
         * from the account balance to auto-renew
         *
         * @var bool
         */
        public $AutoRenew;

        /**
         * The access key associated with this account, by default
         * if none, it's set to 0.
         *
         * @var int
         */
        public $AccessKeyID;

        /**
         * OpenBlu constructor.
         */
        public function __construct()
        {
            $this->CurrentPlan = OpenBluPlan::None;
            $this->NexCycle = 0;
            $this->Price = 0;
            $this->Active = false;
            $this->AutoRenew = false;
            $this->AccessKeyID = 0;
        }

        /**
         * Converts object to array
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'current_plan' => (int)$this->CurrentPlan,
                'next_cycle' => (int)$this->NexCycle,
                'price' => (float)$this->Price,
                'active' => (bool)$this->Active,
                'auto_renew' => (bool)$this->AutoRenew,
                'access_key_id' => $this->AccessKeyID
            );
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @return OpenBlu
         */
        public static function fromArray(array $data): OpenBlu
        {
            $ConfigurationObject = new OpenBlu();

            if(isset($data['current_plan']))
            {
                $ConfigurationObject->CurrentPlan = (int)$data['current_plan'];
            }

            if(isset($data['next_cycle']))
            {
                $ConfigurationObject->NexCycle = (int)$data['next_cycle'];
            }

            if(isset($data['price']))
            {
                $ConfigurationObject->Price = (float)$data['price'];
            }

            if(isset($data['active']))
            {
                $ConfigurationObject->Active = (bool)$data['active'];
            }

            if(isset($data['auto_renew']))
            {
                $ConfigurationObject->AutoRenew = (bool)$data['auto_renew'];
            }

            if(isset($data['access_key_id']))
            {
                $ConfigurationObject->AccessKeyID = (int)$data['access_key_id'];
            }

            return $ConfigurationObject;
        }
    }