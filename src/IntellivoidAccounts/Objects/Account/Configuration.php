<?php

    namespace IntellivoidAccounts\Objects\Account;

    /**
     * Class Configuration
     * @package IntellivoidAccounts\Objects\Account
     */
    class Configuration
    {
        /**
         * The current balance in the account
         *
         * @var float
         */
        public $Balance;

        /**
         * Configuration constructor.
         */
        public function __construct()
        {
            $this->Balance = 0;
        }

        /**
         * Converts object to array
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'balance' => (float)$this->Balance
            );
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @return Configuration
         */
        public static function fromArray(array $data): Configuration
        {
            $ConfigurationObject = new Configuration();

            if(isset($data['balance']))
            {
                $ConfigurationObject->Balance = (float)$data['balance'];
            }

            return $ConfigurationObject;
        }
    }