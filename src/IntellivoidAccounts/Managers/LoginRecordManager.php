<?php

    namespace IntellivoidAccounts\Managers;

    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\IntellivoidAccounts;

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

        public function createLoginRecord(string $account_id, string $ip_address, int $status, string $origin)
        {
            if($this->intellivoidAccounts->getAccountManager()->publicIdExists($account_id) == false)
            {
                throw new AccountNotFoundException();
            }



            $account_id = $this->intellivoidAccounts->database->real_escape_string($account_id);
            $ip_address = $this->intellivoidAccounts->database->real_escape_string($ip_address);
        }
    }