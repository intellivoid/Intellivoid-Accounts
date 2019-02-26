<?php

    namespace IntellivoidAccounts\Objects\Account;

    /**
     * Class Configuration
     * @package IntellivoidAccounts\Objects\Account
     */
    class Configuration
    {
        // TODO: Add configuration data for the account

        /**
         * Converts object to array
         *
         * @return array
         */
        public function toArray(): array
        {
            return array();
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

            return $ConfigurationObject;
        }
    }