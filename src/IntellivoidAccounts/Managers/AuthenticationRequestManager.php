<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\AuthenticationRequestStatus;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\Application;
    use IntellivoidAccounts\Objects\COA\AuthenticationRequest;
    use IntellivoidAccounts\Utilities\Hashing;
    use msqg\QueryBuilder;

    /**
     * Class AuthenticationRequestManager
     * @package IntellivoidAccounts\Managers
     */
    class AuthenticationRequestManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * AuthenticationRequestManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        /**
         * Creates a new Authentication Request
         *
         * @param Application $application
         * @param int $host_id
         * @return AuthenticationRequest
         * @throws DatabaseException
         */
        public function create_authentication_request(Application $application, int $host_id): AuthenticationRequest
        {
            $current_timestamp = (int)time();
            $request_token = Hashing::authenticationRequestToken(
                $application->ID, $application->Name, $host_id, $current_timestamp
            );
            $request_token = $this->intellivoidAccounts->database->real_escape_string($request_token);
            $application_id = (int)$application->ID;
            $status = (int)AuthenticationRequestStatus::Active;
            $account_id = 0;
            $host_id = (int)$host_id;
            $created_timestamp = $current_timestamp;
            $expires_timestamp = $current_timestamp + 600;

            $Query = QueryBuilder::insert_into('authentication_requests', array(
                'request_token' => $request_token,
                'application_id' => $application_id,
                'status' => $status,
                'account_id' => $account_id,
                'host_id' => $host_id,
                'created_timestamp' => $created_timestamp,
                'expires_timestamp' => $expires_timestamp
            ));

            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }
            else
            {
                // TODO:  Add return
            }
        }
    }