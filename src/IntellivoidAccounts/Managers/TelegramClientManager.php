<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\SearchMethods\TelegramClientSearchMethod;
    use IntellivoidAccounts\Abstracts\TelegramChatType;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\Exceptions\TelegramClientNotFoundException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\TelegramClient;
    use IntellivoidAccounts\Objects\TelegramClient\Chat;
    use IntellivoidAccounts\Objects\TelegramClient\User;
    use IntellivoidAccounts\Utilities\Hashing;
    use msqg\QueryBuilder;
    use ZiProto\ZiProto;

    /**
     * Class TelegramClientManager
     * @package IntellivoidAccounts\Managers
     * @deprecated use net.intellivoid.telegram_client_manager
     */
    class TelegramClientManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * TelegramClientManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        /**
         * Registers a new client into the database, if it already exists then update it
         *
         * @param Chat $chat
         * @param User $user
         * @return TelegramClient
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws TelegramClientNotFoundException
         */
        public function registerClient(Chat $chat, User $user): TelegramClient
        {
            $CurrentTime = (int)time();
            $PublicID = Hashing::telegramClientPublicID($chat->ID, $user->ID);

            try
            {
                // Make sure duplicate usernames are not possible
                $this->fixDuplicateUsername($chat, $user);

                $ExistingClient = $this->getClient(TelegramClientSearchMethod::byPublicId, $PublicID);

                $ExistingClient->LastActivityTimestamp = $CurrentTime;
                $ExistingClient->Available = true;
                $ExistingClient->User = $user;
                $ExistingClient->Chat = $chat;

                $this->updateClient($ExistingClient);

                return $ExistingClient;
            }
            catch (TelegramClientNotFoundException $e)
            {
                // Ignore this exception
                unset($e);
            }

            $PublicID = $this->intellivoidAccounts->database->real_escape_string($PublicID);
            $Available = (int)true;
            $AccountID = 0;
            $User = ZiProto::encode($user->toArray());
            $User = $this->intellivoidAccounts->database->real_escape_string($User);
            $Chat = ZiProto::encode($chat->toArray());
            $Chat = $this->intellivoidAccounts->database->real_escape_string($Chat);
            $SessionData = new TelegramClient\SessionData();
            $SessionData = ZiProto::encode($SessionData->toArray());
            $SessionData = $this->intellivoidAccounts->database->real_escape_string($SessionData);
            $ChatID = $this->intellivoidAccounts->database->real_escape_string($chat->ID);
            $UserID = $this->intellivoidAccounts->database->real_escape_string($user->ID);
            $Username = null;
            $LastActivity = $CurrentTime;
            $Created = $CurrentTime;

            if((int)$ChatID == (int)$UserID)
            {
                if($user->Username !== null)
                {
                    $Username = $this->intellivoidAccounts->database->real_escape_string($user->Username);
                }

                if($chat->Username !== null)
                {
                    $Username = $this->intellivoidAccounts->database->real_escape_string($chat->Username);
                }
            }

            $Query = QueryBuilder::insert_into('telegram_clients', array(
                    'public_id' => $PublicID,
                    'available' => $Available,
                    'account_id' => $AccountID,
                    'user' => $User,
                    'chat' => $Chat,
                    'session_data' => $SessionData,
                    'chat_id' => $ChatID,
                    'user_id' => $UserID,
                    'username' => $Username,
                    'last_activity' => $LastActivity,
                    'created' => $Created
                )
            );

            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }

            return $this->getClient(TelegramClientSearchMethod::byPublicId, $PublicID);
        }

        /**
         * Registers the client as a user only (private)
         *
         * @param User $user
         * @return TelegramClient
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws TelegramClientNotFoundException
         */
        public function registerUser(User $user): TelegramClient
        {
            $ChatObject = new Chat();
            $ChatObject->ID = $user->ID;
            $ChatObject->Type = TelegramChatType::Private;
            $ChatObject->Title = null;
            $ChatObject->Username = $user->Username;
            $ChatObject->FirstName = $user->FirstName;
            $ChatObject->LastName = $user->LastName;

            return $this->registerClient($ChatObject, $user);
        }

        /**
         * Registers the client as a chat only (bot based)
         *
         * @param Chat $chat
         * @return TelegramClient
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws TelegramClientNotFoundException
         */
        public function registerChat(Chat $chat): TelegramClient
        {
            $UserObject = new User();
            $UserObject->ID = $chat->ID;
            $UserObject->FirstName = $chat->Title;
            $UserObject->LastName = null;
            $UserObject->LanguageCode = null;
            $UserObject->IsBot = false;
            $UserObject->Username = $chat->Username;

            return $this->registerClient($chat, $UserObject);
        }

        /**
         * @param string $search_method
         * @param string $value
         * @return TelegramClient
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws TelegramClientNotFoundException
         * @noinspection DuplicatedCode
         */
        public function getClient(string $search_method, string $value): TelegramClient
        {
            switch($search_method)
            {
                case TelegramClientSearchMethod::byId:
                case TelegramClientSearchMethod::byAccountId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                case TelegramClientSearchMethod::byChatId:
                case TelegramClientSearchMethod::byUserId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string("public_id");
                    $value = Hashing::telegramClientPublicID((int)$value, (int)$value);
                    break;

                case TelegramClientSearchMethod::byPublicId:
                case TelegramClientSearchMethod::byUsername:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = $this->intellivoidAccounts->database->real_escape_string($value);
                    break;

                default:
                    throw new InvalidSearchMethodException();
            }

            $Query = QueryBuilder::select('telegram_clients', [
                'id',
                'public_id',
                'available',
                'account_id',
                'user',
                'chat',
                'session_data',
                'chat_id',
                'user_id',
                'username',
                'last_activity',
                'created'
            ], $search_method, $value);

            $QueryResults = $this->intellivoidAccounts->database->query($Query);

            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }
            else
            {
                if($QueryResults->num_rows !== 1)
                {
                    throw new TelegramClientNotFoundException();
                }

                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);
                $Row['user'] = ZiProto::decode($Row['user']);
                $Row['chat'] = ZiProto::decode($Row['chat']);
                $Row['session_data'] = ZiProto::decode($Row['session_data']);
                return TelegramClient::fromArray($Row);
            }
        }

        /**
         * Returns a telegram client by username returns null if not found
         *
         * @param string $username
         * @return TelegramClient|null
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         */
        public function getClientByUsername(string $username)
        {
            try
            {
                return $this->getClient(TelegramClientSearchMethod::byUsername, $username);
            }
            catch(TelegramClientNotFoundException $telegramClientNotFoundException)
            {
                return null;
            }
        }

        /**
         * Returns an array of Telegram Clients associated with the given search query
         *
         * @param string $search_method
         * @param string $value
         * @return array
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @noinspection DuplicatedCode
         */
        public function getAssociatedClients(string $search_method, string $value): array
        {
            switch($search_method)
            {
                case TelegramClientSearchMethod::byId:
                case TelegramClientSearchMethod::byAccountId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                case TelegramClientSearchMethod::byChatId:
                case TelegramClientSearchMethod::byUserId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string("public_id");
                    $value = Hashing::telegramClientPublicID((int)$value, (int)$value);
                    break;

                case TelegramClientSearchMethod::byPublicId:
                case TelegramClientSearchMethod::byUsername:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = $this->intellivoidAccounts->database->real_escape_string($value);
                    break;

                default:
                    throw new InvalidSearchMethodException();
            }

            $Query = QueryBuilder::select('telegram_clients', [
                'id',
                'public_id',
                'available',
                'account_id',
                'user',
                'chat',
                'session_data',
                'chat_id',
                'user_id',
                'last_activity',
                'created'
            ], $search_method, $value);

            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($this->intellivoidAccounts->database->error, $Query);
            }
            else
            {
                $ResultsArray = [];

                while($Row = $QueryResults->fetch_assoc())
                {
                    $Row['user'] = ZiProto::decode($Row['user']);
                    $Row['chat'] = ZiProto::decode($Row['chat']);
                    $Row['session_data'] = ZiProto::decode($Row['session_data']);
                    $ResultsArray[] = TelegramClient::fromArray($Row);
                }

                return $ResultsArray;
            }
        }

        /**
         * Updates an existing TelegramClient in the Database
         *
         * @param TelegramClient $telegramClient
         * @return bool
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         */
        public function updateClient(TelegramClient $telegramClient): bool
        {
            $id = (int)$telegramClient->ID;
            $available = (int)$telegramClient->Available;
            $account_id = (int)$telegramClient->AccountID;
            $user = ZiProto::encode($telegramClient->User->toArray());
            $user = $this->intellivoidAccounts->database->real_escape_string($user);
            $chat = ZiProto::encode($telegramClient->Chat->toArray());
            $chat = $this->intellivoidAccounts->database->real_escape_string($chat);
            $session_data = ZiProto::encode($telegramClient->SessionData->toArray());
            $session_data = $this->intellivoidAccounts->database->real_escape_string($session_data);
            $chat_id = $this->intellivoidAccounts->database->real_escape_string($telegramClient->Chat->ID);
            $user_id = $this->intellivoidAccounts->database->real_escape_string($telegramClient->User->ID);
            $username = null;
            $last_activity = (int)time();

            if($telegramClient->getUsername() !== null)
            {
                $username = $this->intellivoidAccounts->database->real_escape_string($telegramClient->getUsername());
                $this->fixDuplicateUsername($telegramClient->Chat, $telegramClient->User);
            }

            $Query = QueryBuilder::update('telegram_clients', array(
                'available' => $available,
                'account_id' => $account_id,
                'user' => $user,
                'chat' => $chat,
                'session_data' => $session_data,
                'chat_id' => $chat_id,
                'user_id' => $user_id,
                'username' => $username,
                'last_activity' => $last_activity
            ), 'id', $id);
            $QueryResults = $this->intellivoidAccounts->database->query($Query);

            if($QueryResults == true)
            {
                return true;
            }
            else
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }
        }

        /**
         * Searches and overwrites old duplicate usernames
         *
         * @param Chat $chat
         * @param User $user
         * @return bool
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         */
        public function fixDuplicateUsername(Chat $chat, User $user): bool
        {
            if((int)$user->ID == (int)$chat->ID)
            {
                $Username = null;

                if($user->Username !== null)
                {
                    $Username = $user->Username;
                }

                if($chat->Username !== null)
                {
                    $Username = $chat->Username;
                }

                if($Username !== null)
                {
                    $ExistingClient = $this->getClientByUsername($Username);

                    if($ExistingClient !== null)
                    {
                        $DuplicateUsername = false;

                        if($ExistingClient->User->ID == $user->ID)
                        {
                            $DuplicateUsername = true;
                        }

                        if($ExistingClient->Chat->ID == $chat->ID)
                        {
                            $DuplicateUsername = true;
                        }

                        if($DuplicateUsername == true)
                        {
                            $ExistingClient->User->Username = null;
                            $ExistingClient->Chat->Username = null;
                            $this->updateClient($ExistingClient);

                            return true;
                        }
                    }
                }
            }

            return false;
        }
    }