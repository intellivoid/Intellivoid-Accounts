<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\IntellivoidAccounts;

    /**
     * Class BalanceTransactions
     * @package IntellivoidAccounts\Managers
     */
    class BalanceTransactions
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * BalanceTransactions constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function  __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts =  $intellivoidAccounts;
        }
    }