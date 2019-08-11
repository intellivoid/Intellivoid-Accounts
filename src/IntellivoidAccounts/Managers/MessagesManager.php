<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\InvalidMessageContentException;
    use IntellivoidAccounts\Exceptions\InvalidMessageSubjectException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\Message;
    use IntellivoidAccounts\Utilities\Hashing;
    use IntellivoidAccounts\Utilities\Validate;

    /**
     * Class MessagesManager
     * @package IntellivoidAccounts\Managers
     */
    class MessagesManager
    {

        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * MessagesManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        /**
         * Composes a new message object
         *
         * @param string $from_id
         * @param string $to_id
         * @param string $subject
         * @param string $content
         * @return Message
         */
        public function composeMessage(string $from_id, string $to_id, string $subject, string $content): Message
        {
            $MessageObject = new Message();

            $MessageObject->Timestamp = (int)time();
            $MessageObject->MessageID = Hashing::messagePublicID($from_id, $to_id, $MessageObject->Timestamp);
            $MessageObject->ToID = $to_id;
            $MessageObject->FromID = $from_id;
            $MessageObject->Subject = $subject;
            $MessageObject->ReplyToID = 0;
            $MessageObject->AllowReply = true;
            $MessageObject->Verified = false;
            $MessageObject->Seen = false;
            $MessageObject->FromDeleted = false;
            $MessageObject->ToDeleted = false;
            $MessageObject->Contents = $content;

            return $MessageObject;
        }

        /**
         * Composes a reply to an existing message
         *
         * @param Message $message
         * @param string $subject
         * @param string $content
         * @return Message
         */
        public function composeReply(Message $message, string $subject, string $content): Message
        {
            $MessageObject = new Message();

            $MessageObject->Timestamp = (int)time();
            $MessageObject->MessageID = Hashing::messagePublicID($message->ToID, $message->FromID, $MessageObject->Timestamp);
            $MessageObject->ToID = $message->FromID;
            $MessageObject->FromID = $message->ToID;
            $MessageObject->Subject = $subject;
            $MessageObject->ReplyToID = $message->ID;
            $MessageObject->AllowReply = true;
            $MessageObject->Verified = false;
            $MessageObject->Seen = false;
            $MessageObject->FromDeleted = false;
            $MessageObject->ToDeleted = false;
            $MessageObject->Contents = $content;

            return $MessageObject;
        }

        /**
         * @param Message $message
         * @return bool
         * @throws AccountNotFoundException
         * @throws InvalidMessageContentException
         * @throws InvalidMessageSubjectException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         */
        public function sendMessage(Message $message): bool
        {
            if(Validate::messageSubject($message->Subject) == false)
            {
                throw new InvalidMessageSubjectException();
            }

            if(Validate::messageContent($message->Contents) == false)
            {
                throw new InvalidMessageContentException();
            }

            if($this->intellivoidAccounts->getAccountManager()->IdExists($message->FromID) == false)
            {
                throw new AccountNotFoundException();
            }

            if($message->ToID !== 0)
            {
                if($this->intellivoidAccounts->getAccountManager()->IdExists($message->ToID) == false)
                {
                    throw new AccountNotFoundException();
                }
            }

            // TODO: If reply_to_id is not 0, verify if the ID of the message exists.

            $MessageID = $this->intellivoidAccounts->database->real_escape_string($message->MessageID);
            $FromID = (int)$message->FromID;
            $ToID = (int)$message->ToID;
            $ReplyToID = (int)$message->ReplyToID;
            $Subject = $this->intellivoidAccounts->database->real_escape_string(base64_encode($message->Subject));
            $Contents = $this->intellivoidAccounts->database->real_escape_string(base64_encode($message->Contents));
            $Verified = (int)$message->Verified;
            $Seen = (int)$message->Seen;
            $AllowReply = (int)$message->AllowReply;
            $FromDeleted = (int)$message->FromDeleted;
            $ToDeleted = (int)$message->ToDeleted;
            $Timestamp = (int)$message->Timestamp;

            $Query = "INSERT INTO `user_messages` (message_id, from_id, to_id, reply_to_id, subject, contents, verified, seen, allow_reply, from_deleted, to_deleted, timestamp) VALUES ('$MessageID', $FromID, $ToID, $ReplyToID, '$Subject', '$Contents', $Verified, $Seen, $AllowReply, $FromDeleted, $ToDeleted, $Timestamp)";
            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults)
            {
                return True;
            }

            throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
        }


    }