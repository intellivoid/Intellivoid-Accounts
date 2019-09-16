<?php


    namespace IntellivoidAccounts\Managers;

    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\AuthenticationAccess;
    use IntellivoidAccounts\Objects\COA\AuthenticationRequest;
    use IntellivoidAccounts\Utilities\Hashing;

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

        public function createAuthenticationAccess(AuthenticationRequest $authenticationRequest): AuthenticationAccess
        {
            $current_timestamp = (int)time();
            $access_token = Hashing::authenticationAccessToken(
                $authenticationRequest->Id,
                $authenticationRequest->RequestToken,
                $current_timestamp,
                $authenticationRequest->AccountId,
                $authenticationRequest->HostId
            );
            $application_id = (int)$authenticationRequest->ApplicationId;
            $account_id = (int)$authenticationRequest->AccountId;
            $request_id = (int)$authenticationRequest->Id;
            $status =
        }
    }