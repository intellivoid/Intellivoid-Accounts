<?php

    namespace IntellivoidAccounts\Managers;

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

        }
    }