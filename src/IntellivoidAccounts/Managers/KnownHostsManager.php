<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\IntellivoidAccounts

    public class KnownHostsManager
    {

        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * KnownHostsManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }


    }