<?php


    namespace IntellivoidAccounts\Services;


    use IntellivoidAccounts\IntellivoidAccounts;

    /**
     * Class Telegram
     * @package IntellivoidAccounts\Services
     */
    class Telegram
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * Telegram constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }
    }