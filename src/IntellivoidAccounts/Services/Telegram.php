<?php


    namespace IntellivoidAccounts\Services;


    use Exception;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\InvalidUrlException;
    use IntellivoidAccounts\Exceptions\TelegramActionFailedException;
    use IntellivoidAccounts\Exceptions\TelegramApiException;
    use IntellivoidAccounts\Exceptions\TelegramServicesNotAvailableException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\TelegramClient;
    use IntellivoidAccounts\Utilities\Validate;

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
            try
            {
                $ch = curl_init($location);
                curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($payload));
                curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                $result = curl_exec($ch);
                curl_close($ch);
            }
            catch(Exception $exception)
            {
                throw new TelegramApiException();
            }

            return $result;
        }

        /**
         * Builds the URL for the given endpoint via action
         *
         * @param string $action
         * @return string
         */
        private function getEndpoint(string $action): string
        {
            return "https://api.telegram.org/bot" . $this->intellivoidAccounts->getTelegramConfiguration()['TgBotToken'] . "/$action";
        }

        /**
         * @param TelegramClient $telegramClient
         * @param string $from
         * @param string $message
         * @param string|null $url
         * @return bool
         * @throws TelegramActionFailedException
         * @throws TelegramApiException
         * @throws TelegramServicesNotAvailableException
         * @throws InvalidUrlException
         * @throws DatabaseException
         */
        public function sendNotification(TelegramClient $telegramClient, string $from, string $message, string $url=null): bool
        {
            if(strtolower($this->intellivoidAccounts->getTelegramConfiguration()['TgBotEnabled']) !== "true")
            {
                throw new TelegramServicesNotAvailableException();
            }

            $keyboard = array();

            if($url == null)
            {
                if(Validate::url($url) == false)
                {
                    throw new InvalidUrlException();
                }

                $keyboard = array(
                    "inline_keyboard" => [
                        [array("text" => "Open URL", "url" => $url)],
                    ]
                );
            }

            $Response = json_decode($this->sendRequest($this->getEndpoint('sendMessage'), array(
                'chat_id' => $telegramClient->Chat->ID,
                'parse_mode' => 'html',
                'text' => $this->emojis['BELL'] . " <b>Notification from $from</b>\n\n$message",
                'reply_markup' => $keyboard
            )), true);

            if($Response['ok'] == false)
            {
                $Message = "unknown";
                $ErrorCode = 0;

                if(isset($Response['description']))
                {
                    $Message = $Response['description'];
                }

                if(isset($Response['error_code']))
                {
                    $ErrorCode = (int)$Response['error_code'];
                }

                $telegramClient->Available = false;
                $telegramClient->LastActivityTimestamp = (int)time();
                $this->intellivoidAccounts->getTelegramClientManager()->updateClient($telegramClient);

                throw new TelegramActionFailedException($Message, $ErrorCode);
            }

            $telegramClient->Available = true;
            $telegramClient->LastActivityTimestamp = (int)time();
            $this->intellivoidAccounts->getTelegramClientManager()->updateClient($telegramClient);

            return true;
        }
    }