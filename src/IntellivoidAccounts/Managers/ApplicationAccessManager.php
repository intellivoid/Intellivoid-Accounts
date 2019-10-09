<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\ApplicationAccess;
    use IntellivoidAccounts\Utilities\Hashing;

    /**
     * Class ApplicationAccessManager
     * @package IntellivoidAccounts\Managers
     */
    class ApplicationAccessManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * ApplicationAccessManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        public function createApplicationAccess(int $application_id, int $account_id): ApplicationAccess
        {
            $creation_timestamp = (int)time();
            $application_id = (int)$application_id;
            $account_id = (int)$account_id;
            $public_id = Hashing::ApplicationAccess($account_id, $application_id);
            $public_id = $this->intellivoidAccounts->database->real_escape_string($public_id);

            $
        }
    }