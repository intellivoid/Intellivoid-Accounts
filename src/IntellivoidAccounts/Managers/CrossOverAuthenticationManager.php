<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\IntellivoidAccounts;

    /**
     * Class CrossOverAuthenticationManager
     * @package IntellivoidAccounts\Managers
     */
    class CrossOverAuthenticationManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * @var AuthenticationRequestManager
         */
        private $authenticationRequestManager;

        /**
         * @var AuthenticationAccessManager
         */
        private $authenticationAccessManager;

        /**
         * CrossOverAuthenticationManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
            $this->authenticationRequestManager = new AuthenticationRequestManager($intellivoidAccounts);
            $this->authenticationAccessManager = new AuthenticationAccessManager($intellivoidAccounts);
        }

        /**
         * @return AuthenticationRequestManager
         */
        public function getAuthenticationRequestManager(): AuthenticationRequestManager
        {
            return $this->authenticationRequestManager;
        }

        /**
         * @return AuthenticationAccessManager
         */
        public function getAuthenticationAccessManager(): AuthenticationAccessManager
        {
            return $this->authenticationAccessManager;
        }
    }