<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\ApplicationAccessStatus;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\ApplicationAccess;
    use IntellivoidAccounts\Utilities\Hashing;
    use msqg\QueryBuilder;

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

        /**
         * Registers the Application Access into the Database
         *
         * @param int $application_id
         * @param int $account_id
         * @return bool
         * @throws DatabaseException
         */
        public function createApplicationAccess(int $application_id, int $account_id): bool
        {
            $creation_timestamp = (int)time();
            $application_id = (int)$application_id;
            $account_id = (int)$account_id;
            $public_id = Hashing::ApplicationAccess($account_id, $application_id);
            $public_id = $this->intellivoidAccounts->database->real_escape_string($public_id);
            $status = (int)ApplicationAccessStatus::Authorized;
            $last_authenticated_timestamp = $creation_timestamp;

            $Query = QueryBuilder::insert_into('application_access', array(
                'public_id' => $public_id,
                'application_id' => $application_id,
                'account_id' => $account_id,
                'status' => $status,
                'creation_timestamp' => $creation_timestamp,
                'last_authenticated_timestamp' => $last_authenticated_timestamp
            ));
            $QueryResults = $this->intellivoidAccounts->database->query($Query);

            if($QueryResults == true)
            {
                return true;
            }
            else
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }
        }

        public function getApplicationAccess(string $search_method, string $value): ApplicationAccess
        {

        }
    }