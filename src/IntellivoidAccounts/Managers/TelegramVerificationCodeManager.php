<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\TelegramVerificationCodeStatus;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\TelegramVerificationCode;
    use IntellivoidAccounts\Utilities\Hashing;
    use msqg\QueryBuilder;

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

        /**
         * Generates a verification code that will last for 5 minutes.
         *
         * @param int $telegram_client_id
         * @return TelegramVerificationCode
         * @throws DatabaseException
         */
        public function generateCode(int $telegram_client_id): TelegramVerificationCode
        {
            $time = (int)time();
            $verification_code = Hashing::telegramVerificationCode($telegram_client_id, $time);
            $verification_code = $this->intellivoidAccounts->database->real_escape_string($verification_code);
            $status = (int)TelegramVerificationCodeStatus::Active;
            $expires = $time + 300;
            $created = $time;

            $Query = QueryBuilder::insert_into('telegram_verification_codes', array(
                'verification_code' => $verification_code,
                'telegram_client_id' => (int)$telegram_client_id,
                'status' => $status,
                'expires' => $expires,
                'created' => $created
            ));

            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }
            else
            {
                // TODO: Add return method
            }
        }
    }