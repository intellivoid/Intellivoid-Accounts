<?php

    namespace IntellivoidAccounts\Managers;

    use IntellivoidAccounts\Abstracts\LoginStatus;
    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\InvalidIpException;
    use IntellivoidAccounts\Exceptions\InvalidLoginStatusException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Utilities\Validate;

    /**
     * Class LoginRecordManager
     * @package IntellivoidAccounts\Managers
     */
    class LoginRecordManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * LoginRecordManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        public function createLoginRecord(int $account_id, string $ip_address, int $status, string $origin)
        {
            if(Validate::ip($ip_address) == false)
            {
                throw new InvalidIpException();
            }

            if($this->intellivoidAccounts->getAccountManager()->publicIdExists($account_id) == false)
            {
                throw new AccountNotFoundException();
            }

            switch($status)
            {
                case LoginStatus::Successful:
                    break;

                case LoginStatus::IncorrectCredentials:
                    break;

                case LoginStatus::IncorrectVerificationCode:
                    break;

                default:
                    throw new InvalidLoginStatusException();
            }

            $account_id = $this->intellivoidAccounts->database->real_escape_string($account_id);
            $ip_address = $this->intellivoidAccounts->database->real_escape_string($ip_address);
            $login_status = (int)$status;
            $origin = $this->intellivoidAccounts->database->real_escape_string($origin);
            $time = (int)time();

        }
    }