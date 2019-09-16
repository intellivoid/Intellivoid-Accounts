<?php


    namespace IntellivoidAccounts\Managers;

    use IntellivoidAccounts\Abstracts\AuthenticationAccessStatus;
    use IntellivoidAccounts\Abstracts\SearchMethods\AuthenticationAccessSearchMethod;
    use IntellivoidAccounts\Exceptions\AuthenticationAccessNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\AuthenticationAccess;
    use IntellivoidAccounts\Objects\COA\AuthenticationRequest;
    use IntellivoidAccounts\Utilities\Hashing;
    use msqg\QueryBuilder;

    /**
     * Class AuthenticationAccessManager
     * @package IntellivoidAccounts\Managers
     */
    class AuthenticationAccessManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * AuthenticationAccessManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        /**
         * Creates a new Authentication Access Token
         *
         * @param AuthenticationRequest $authenticationRequest
         * @return AuthenticationAccess
         * @throws DatabaseException
         */
        public function createAuthenticationAccess(AuthenticationRequest $authenticationRequest): AuthenticationAccess
        {
            // TODO: Determine if the Request Token has already been used before

            $current_timestamp = (int)time();
            $access_token = Hashing::authenticationAccessToken(
                $authenticationRequest->Id,
                $authenticationRequest->RequestToken,
                $current_timestamp,
                $authenticationRequest->AccountId,
                $authenticationRequest->HostId
            );
            $access_token = $this->intellivoidAccounts->database->real_escape_string($access_token);
            $application_id = (int)$authenticationRequest->ApplicationId;
            $account_id = (int)$authenticationRequest->AccountId;
            $request_id = (int)$authenticationRequest->Id;
            $status = (int)AuthenticationAccessStatus::Active;
            $expires_timestamp = $current_timestamp + 43200;
            $last_used_timestamp = $current_timestamp;
            $created_timestamp = $current_timestamp;

            $Query = QueryBuilder::insert_into('authentication_access', array(
                'access_token' => $access_token,
                'application' => $application_id,
                'account_id' => $account_id,
                'request_id' => $request_id,
                'status' => $status,
                'expires_timestamp' => $expires_timestamp,
                'last_used_timestamp' => $last_used_timestamp,
                'created_timestamp' => $created_timestamp
            ));

            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }
            else
            {
                // TODO: Add return method
            }
        }

        /**
         * Returns an existing AuthenticationAccess record from the database
         *
         * @param string $search_method
         * @param string $value
         * @return AuthenticationAccess
         * @throws AuthenticationAccessNotFoundException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         */
        public function getAuthenticationAccess(string $search_method, string $value): AuthenticationAccess
        {
            switch($search_method)
            {
                case AuthenticationAccessSearchMethod::byRequestId:
                case AuthenticationAccessSearchMethod::byId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                case AuthenticationAccessSearchMethod::byAccessToken:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = $this->intellivoidAccounts->database->real_escape_string($value);
                    break;

                default:
                    throw new InvalidSearchMethodException();
            }

            $Query = QueryBuilder::select('authentication_access', [
                'id',
                'access_token',
                'account_id',
                'request_id',
                'status',
                'expires_timestamp',
                'last_used_timestamp',
                'created_timestamp'
            ], $search_method, $value);
            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }
            else
            {
                if($QueryResults->num_rows !== 1)
                {
                    throw new AuthenticationAccessNotFoundException();
                }

                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);
                return AuthenticationAccess::fromArray($Row);
            }
        }
    }