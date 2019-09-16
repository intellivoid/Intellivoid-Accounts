<?php


    namespace IntellivoidAccounts\Managers;

    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\AuthenticationAccess;
    use IntellivoidAccounts\Objects\COA\AuthenticationRequest;

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
            $accessToken
        }
    }