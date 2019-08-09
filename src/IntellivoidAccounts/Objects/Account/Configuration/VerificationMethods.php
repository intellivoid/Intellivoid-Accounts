<?php


    namespace IntellivoidAccounts\Objects\Account\Configuration;

    /**
     * Verification methods that this account uses
     *
     * Class VerificationMethods
     * @package IntellivoidAccounts\Objects\Account\Configuration
     */
    class VerificationMethods
    {

        /**
         * Creates array from object
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
         * @return VerificationMethods
         */
        public static function fromArray(array $data): VerificationMethods
        {
            $VerificationMethodsObject = new VerificationMethods();

            return $VerificationMethodsObject;
        }
    }