<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\IntellivoidAccounts;

    /**
     * Class TelegramVerificationCodeManager
     * @package IntellivoidAccounts\Managers
     */
    class TelegramVerificationCodeManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * TelegramVerificationCodeManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

    }