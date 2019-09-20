<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\TelegramVerificationCode;
    use IntellivoidAccounts\Utilities\Hashing;

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

        public function generateCode(int $telegram_client_id): TelegramVerificationCode
        {
            $time = (int)time();
            $verification_code = Hashing::telegramVerificationCode()
        }
    }