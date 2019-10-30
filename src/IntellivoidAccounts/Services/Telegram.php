<?php


    namespace IntellivoidAccounts\Services;


    use Exception;
    use IntellivoidAccounts\Exceptions\TelegramApiException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\TelegramClient;

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
         * Array of Emojis that are used for Telegram
         *
         * @var array
         */
        private $emojis;

        /**
         * Telegram constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
            $this->emojis = array(
                'BELL' => "\u{1F514}"
            );
        }

        /**
         * Sends a HTTP/HTTPs POST Request to the given location and returns the response as a string
         *
         * @param string $location
         * @param array $payload
         * @return string
         * @throws TelegramApiException
         */
        private function sendRequest(string $location, array $payload): string
        {
            $context = stream_context_create(array(
                'http' => array(
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => http_build_query($payload)
                )
            ));

            $result = file_get_contents($location, false, $context);

            if($result == null)
            {
                throw new TelegramApiException();
            }

            return $result;
        }

        public function sendNotification(TelegramClient $telegramClient, string $from, string $message): bool
        {

        }
    }