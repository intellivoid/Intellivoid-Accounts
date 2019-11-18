<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\IntellivoidAccounts;

    /**
     * Class TransactionRecordManager
     * @package IntellivoidAccounts\Managers
     */
    class TransactionRecordManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * TransactionRecordManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }
    }