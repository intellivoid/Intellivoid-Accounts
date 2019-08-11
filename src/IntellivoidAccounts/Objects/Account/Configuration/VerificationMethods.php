<?php


    namespace IntellivoidAccounts\Objects\Account\Configuration;

    use IntellivoidAccounts\Objects\VerificationMethods\RecoveryCodes;
    use IntellivoidAccounts\Objects\VerificationMethods\TwoFactorAuthentication;

    /**
     * Verification methods that this account uses
     *
     * Class VerificationMethods
     * @package IntellivoidAccounts\Objects\Account\Configuration
     */
    class VerificationMethods
    {

        /**
         * Indicates if TwoFactorAuthentication is enabled on this account
         *
         * @var bool
         */
        public $TwoFactorAuthenticationEnabled;

        /**
         * TwoFactorAuthentication Configuration
         *
         * @var TwoFactorAuthentication
         */
        public $TwoFactorAuthentication;

        /**
         * Indicates if RecoveryCodes are enabled on this account
         *
         * @var bool
         */
        public $RecoveryCodesEnabled;

        /**
         * RecoveryCodes Configuration
         *
         * @var RecoveryCodes
         */
        public $RecoveryCodes;

        /**
         * Creates array from object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                '2fa_auth_enabled' => (bool)$this->TwoFactorAuthenticationEnabled,
                '2fa_auth' => $this->TwoFactorAuthentication->toArray(),
                'recovery_codes_enabled' => (bool)$this->RecoveryCodesEnabled,
                'recovery_codes' => $this->RecoveryCodes->toArray()
            );
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

            if(isset($data['2fa_auth_enabled']) == false)
            {
                $VerificationMethodsObject->TwoFactorAuthenticationEnabled = false;
            }
            else
            {
                $VerificationMethodsObject->TwoFactorAuthenticationEnabled = (bool)$data['2fa_auth_enabled'];
            }

            if(isset($data['2fa_auth']) == false)
            {
                $VerificationMethodsObject->TwoFactorAuthentication = new TwoFactorAuthentication();
                $VerificationMethodsObject->TwoFactorAuthentication->disable();
            }
            else
            {
                $VerificationMethodsObject->TwoFactorAuthentication = TwoFactorAuthentication::fromArray($data['2fa_auth']);
            }

            if(isset($data['recovery_codes_enabled']) == false)
            {
                $VerificationMethodsObject->RecoveryCodesEnabled = false;
            }
            else
            {
                $VerificationMethodsObject->RecoveryCodesEnabled = (bool)$data['recovery_codes_enabled'];
            }

            if(isset($data['recovery_codes']) == false)
            {
                $VerificationMethodsObject->RecoveryCodes = new RecoveryCodes();
                $VerificationMethodsObject->RecoveryCodes->disable();
            }
            else
            {
                $VerificationMethodsObject->RecoveryCodes = RecoveryCodes::fromArray($data['recovery_codes']);
            }

            return $VerificationMethodsObject;
        }
    }