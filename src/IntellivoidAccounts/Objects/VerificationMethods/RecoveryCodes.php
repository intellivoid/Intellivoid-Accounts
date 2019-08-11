<?php


    namespace IntellivoidAccounts\Objects\VerificationMethods;


    use IntellivoidAccounts\Utilities\Hashing;
    use tsa\Exceptions\BadLengthException;
    use tsa\Exceptions\SecuredRandomProcessorNotFoundException;

    class RecoveryCodes
    {
        public $Enabled;

        public $RecoveryCodes;

        /**
         * Enables this method of verification and creates the recovery codes set
         *
         * @throws BadLengthException
         * @throws SecuredRandomProcessorNotFoundException
         */
        public function enable()
        {
            $this->Enabled = true;
            $this->RecoveryCodes = [];

            while(true)
            {
                if(count($this->RecoveryCodes) > 12)
                {
                    break;
                }

                $this->RecoveryCodes[] = Hashing::recoveryCode();
            }
        }

        /**
         * Disables this method of verification and clears the recovery codes
         */
        public function disable()
        {
            $this->Enabled = false;
            $this->RecoveryCodes = [];
        }


        /**
         * Verifies the given recovery code
         *
         * @param string $input
         * @param bool $remove_code
         * @return bool
         */
        public function verifyCode(string $input, bool $remove_code = false): bool
        {

            if($this->Enabled == false)
            {
                return False;
            }

            if(isset($this->RecoveryCodes[$input]) == false)
            {
                return False;
            }

            if($remove_code == true)
            {
                unset($this->RecoveryCodes[$input]);
            }

            return True;
        }

        /**
         * Returns an array that represents this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'enabled' => (bool)$this->Enabled,
                'recovery_codes' => $this->RecoveryCodes
            );
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @return RecoveryCodes
         */
        public static function fromArray(array $data): RecoveryCodes
        {
            $RecoveryCodesObject = new RecoveryCodes();

            if(isset($data['enabled']))
            {
                $RecoveryCodesObject->Enabled = (bool)$data['enabled'];
            }

            if(isset($data['recovery_codes']))
            {
                $RecoveryCodesObject->RecoveryCodes = $data['recovery_codes'];
            }

            return $RecoveryCodesObject;
        }
    }