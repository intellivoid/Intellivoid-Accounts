<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Utilities\Hashing;

    /**
     * Class OtlManager
     * @package IntellivoidAccounts\Managers
     */
    class OtlManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * OtlManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        public function generateLoginCode(int $account_id): string
        {
            $created_timestamp = (int)time();
            $expires_timestamp = $created_timestamp + 300;

            $code = Hashing::OneTimeLoginCode($account_id, $created_timestamp, $expires_timestamp);
            $code = $this->intellivoidAccounts->database->real_escape_string($code);
            $account_id = (int)$account_id;
            $status = Otl
        }
    }