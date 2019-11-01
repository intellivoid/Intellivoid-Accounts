<?php


    namespace IntellivoidAccounts\Services;


    use Exception;
    use IntellivoidAccounts\Exceptions\AuthNotPromptedException;
    use IntellivoidAccounts\Exceptions\AuthPromptAlreadyApprovedException;
    use IntellivoidAccounts\Exceptions\AuthPromptExpiredException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\InvalidUrlException;
    use IntellivoidAccounts\Exceptions\TelegramActionFailedException;
    use IntellivoidAccounts\Exceptions\TelegramApiException;
    use IntellivoidAccounts\Exceptions\TelegramServicesNotAvailableException;
    use IntellivoidAccounts\Exceptions\TooManyPromptRequestsException;
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
                'BELL' => "\u{1F514}",
                'LOCK' => "\u{1F512}",
                'CHECK' => "\u{2705}",
                'DENY' => "\u{1F6AB}"
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

        /**
         * Prompts the user for authentication
         *
         * @param TelegramClient $telegramClient
         * @param string $username
         * @param string $ip_address
         * @return bool
         * @throws DatabaseException
         * @throws TelegramActionFailedException
         * @throws TelegramApiException
         * @throws TelegramServicesNotAvailableException
         * @throws TooManyPromptRequestsException
         */
        public function promptAuth(TelegramClient $telegramClient, string $username): bool
        {
            if(strtolower($this->intellivoidAccounts->getTelegramConfiguration()['TgBotEnabled']) !== "true")
            {
                throw new TelegramServicesNotAvailableException();
            }

            // Check the current attempts and expire sessions
            if($telegramClient->SessionData->keyExists('auth', 'current_attempts') == false)
            {
                $telegramClient->SessionData->setData('auth', 'current_attempts', 0);
            }
            else
            {
                $current_time = (int)time();
                /** @var int $attempts_reset */
                $attempts_reset = $telegramClient->SessionData->getData('auth', 'attempts_reset');
                /** @var int $current_attempts */
                $current_attempts = $telegramClient->SessionData->getData('auth', 'current_attempts');

                if($current_time > $attempts_reset)
                {
                    $telegramClient->SessionData->setData('auth', 'current_attempts', 0);
                }
                else
                {
                    if($current_attempts == 3)
                    {
                        throw new TooManyPromptRequestsException();
                    }
                    else
                    {
                        $current_attempts += 1;
                        $telegramClient->SessionData->setData('auth', 'current_attempts', $current_attempts);
                    }
                }
            }

            $telegramClient->SessionData->setData('auth', 'attempts_reset', (int)time() + 1800);
            $telegramClient->SessionData->setData('auth', 'currently_active', true);
            $telegramClient->SessionData->setData('auth', 'expires', (int)time() + 300);
            $telegramClient->SessionData->setData('auth', 'approved', false);

            $this->intellivoidAccounts->getTelegramClientManager()->updateClient($telegramClient);

            $Response = json_decode($this->sendRequest($this->getEndpoint('sendMessage'), array(
                'chat_id' => $telegramClient->Chat->ID,
                'parse_mode' => 'html',
                'text' =>
                    $this->emojis['LOCK'] . " Hi " . $username . ", please confirm the authentication request\n\n" .
                    "<b>IP:</b> <code>12.0.0.1</code>\n" .
                    "<b>Country:</b> <code>Unknown</code>\n" .
                    "<b>Device:</b> <code>Android</code>\n" .
                    "<b>Browser:</b> <code>Chrome</code>\n\n" .
                    "<i>If this was not you, click deny and change your password immediately</i>",
                    'reply_markup' =>  array(
                    "inline_keyboard" => [
                        [
                            array("text" => $this->emojis['DENY'] . ' Deny', "callback_data" => "auth_deny"),
                            array("text" => $this->emojis['CHECK'] . ' Authenticate', "callback_data" => "auth_allow")
                        ]
                    ]
                )
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

            return true;
        }

        /**
         * Checks if the state of the prompt is valid or not
         *
         * @param TelegramClient $telegramClient
         * @return bool
         * @throws AuthNotPromptedException
         * @throws AuthPromptExpiredException
         * @throws AuthPromptAlreadyApprovedException
         */
        private function checkPromptState(TelegramClient $telegramClient): bool
        {
            // Check the prompt status
            if($telegramClient->SessionData->keyExists('auth', 'attempts_reset') == false)
            {
                throw new AuthNotPromptedException();
            }

            if($telegramClient->SessionData->keyExists('auth', 'current_attempts') == false)
            {
                throw new AuthNotPromptedException();
            }

            if($telegramClient->SessionData->keyExists('auth', 'currently_active') == false)
            {
                throw new AuthNotPromptedException();
            }

            if($telegramClient->SessionData->keyExists('auth', 'expires') == false)
            {
                throw new AuthNotPromptedException();
            }

            if($telegramClient->SessionData->keyExists('auth', 'approved') == false)
            {
                throw new AuthNotPromptedException();
            }

            /** @var bool $currently_active */
            $currently_active = $telegramClient->SessionData->getData('auth', 'currently_active');
            /** @var int $expires */
            $expires = $telegramClient->SessionData->getData('auth', 'expires');
            /** @var bool $expires */
            $approved = $telegramClient->SessionData->getData('auth', 'approved');

            if($currently_active == false)
            {
                throw new AuthNotPromptedException();
            }

            if($approved == true)
            {
                throw new AuthPromptAlreadyApprovedException();
            }

            if((int)time() > $expires)
            {
                throw new AuthPromptExpiredException();
            }

            return true;
        }

        /**
         * Returns the state of the authentication method
         *
         * @param TelegramClient $telegramClient
         * @return array
         */
        private function getAuth(TelegramClient $telegramClient): array
        {
            /** @var int $attempts_reset */
            $attempts_reset = $telegramClient->SessionData->getData('auth', 'attempts_reset');
            /** @var int $current_attempts */
            $current_attempts = $telegramClient->SessionData->getData('auth', 'current_attempts');
            /** @var bool $currently_active */
            $currently_active = $telegramClient->SessionData->getData('auth', 'currently_active');
            /** @var int $expires */
            $expires = $telegramClient->SessionData->getData('auth', 'expires');
            /** @var bool $approved */
            $approved = $telegramClient->SessionData->getData('auth', 'approved');

            return array(
                'attempts_reset' => (int)$attempts_reset,
                'current_attempts' => (int)$current_attempts,
                'currently_active' => (bool)$currently_active,
                'expires' => (int)$expires,
                'approved' => (bool)$approved
            );
        }

        /**
         * @param TelegramClient $telegramClient
         * @throws AuthNotPromptedException
         * @throws AuthPromptAlreadyApprovedException
         * @throws AuthPromptExpiredException
         * @throws TelegramServicesNotAvailableException
         */
        public function approveAuth(TelegramClient $telegramClient)
        {
            if(strtolower($this->intellivoidAccounts->getTelegramConfiguration()['TgBotEnabled']) !== "true")
            {
                throw new TelegramServicesNotAvailableException();
            }

            $this->checkPromptState($telegramClient);


        }
    }