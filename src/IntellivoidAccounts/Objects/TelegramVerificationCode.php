<?php


    namespace IntellivoidAccounts\Objects;

    /** @noinspection PhpUnused */

    /**
     * Class TelegramVerificationCode
     * @package IntellivoidAccounts\Objects
     */
    class TelegramVerificationCode
    {
        /**
         * Internal unique database ID for this verification code
         *
         * @var int
         */
        public int $ID;

        /**
         * The generated verification code
         *
         * @var string
         */
        public string $VerificationCode;

        /**
         * The ID of the Telegram Client
         *
         * @var int
         */
        public int $TelegramClientID;

        /**
         * The current status of this verification code
         *
         * @var int
         */
        public int $Status;

        /**
         * The Unix Timestamp of when this verification code expires
         *
         * @var int
         */
        public int $Expires;

        /**
         * The Unix Timestamp of when this verification code was created
         *
         * @var int
         */
        public int $Created;

        public function toArray(): array
        {
            return array(
                'id' => $this->ID,
                'verification_code' => $this->VerificationCode,
                'telegram_client_id' => $this->TelegramClientID,
                'status' => $this->Status,
                'expires' => $this->Expires,
                'created' => $this->Created
            );
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return TelegramVerificationCode
         */
        public static function fromArray(array $data): TelegramVerificationCode
        {
            $TelegramVerificationCodeObject = new TelegramVerificationCode();

            if(isset($data['id']))
            {
                $TelegramVerificationCodeObject->ID = (int)$data['id'];
            }

            if(isset($data['verification_code']))
            {
                $TelegramVerificationCodeObject->VerificationCode = $data['verification_code'];
            }

            if(isset($data['telegram_client_id']))
            {
                $TelegramVerificationCodeObject->TelegramClientID = (int)$data['telegram_client_id'];
            }

            if(isset($data['status']))
            {
                $TelegramVerificationCodeObject->Status = (int)$data['status'];
            }

            if(isset($data['expires']))
            {
                $TelegramVerificationCodeObject->Expires = (int)$data['expires'];
            }

            if(isset($data['created']))
            {
                $TelegramVerificationCodeObject->Created = (int)$data['created'];
            }

            return $TelegramVerificationCodeObject;
        }
    }