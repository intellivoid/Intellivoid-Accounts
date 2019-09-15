<?php


    namespace IntellivoidAccounts\Managers;

    use IntellivoidAccounts\IntellivoidAccounts;

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
    }