<?php

    namespace IntellivoidAccounts\Objects\Account;

    use IntellivoidAccounts\Objects\Account\Configuration\VerificationMethods;

    /**
     * Class Configuration
     * @package IntellivoidAccounts\Objects\Account
     */
    class Configuration
    {
        /**
         * Verification Methods that are available for this account
         *
         * @var VerificationMethods
         */
        public $VerificationMethods;

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
                'balance' => (float)$this->Balance,
                'verification_methods' => $this->VerificationMethods
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

            if(isset($data['verification_methods']))
            {
                $ConfigurationObject->VerificationMethods = VerificationMethods::fromArray($data['verification_methods']);
            }

            return $ConfigurationObject;
        }
    }