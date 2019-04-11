<?php

    namespace IntellivoidAccounts\Objects\Account\Configuration;

    /**
     * Class OpenBlu
     * @package IntellivoidAccounts\Objects\Account\Configuration
     */
    class OpenBlu
    {

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
         * @return OpenBlu
         */
        public static function fromArray(array $data): OpenBlu
        {
            $ConfigurationObject = new OpenBlu();

            return $ConfigurationObject;
        }
    }