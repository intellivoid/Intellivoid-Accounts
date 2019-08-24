<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Exceptions\DatabaseException;
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
         * Registers a new client into the database
         *
         * @param Chat $chat
         * @param User $user
         * @return TelegramClient
         * @throws DatabaseException
         */
        public function registerClient(Chat $chat, User $user): TelegramClient
        {
            $CurrentTime = (int)time();
            $PublicID = Hashing::telegramClientPublicID($chat->ID, $CurrentTime);
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
            $LastActivity = $CurrentTime;
            $Created = $CurrentTime;

            $Query = QueryBuilder::insert_into('telegram_clients', array(
                    'public_id' => $PublicID,
                    'available' => $Available,
                    'account_id' => $AccountID,
                    'user' => $User,
                    'chat' => $Chat,
                    'session_data' => $SessionData,
                    'chat_id' => $ChatID,
                    'user_id' => $UserID,
                    'last_activity' => $LastActivity,
                    'created' => $Created
                )
            );

            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }

            return new TelegramClient();
        }
    }