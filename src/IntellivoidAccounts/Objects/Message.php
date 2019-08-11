<?php


    namespace IntellivoidAccounts\Objects;


    /**
     * Class Message
     * @package IntellivoidAccounts\Objects
     */
    class Message
    {
        /**
         * Internal Database ID For this message
         *
         * @var int
         */
        public $ID;

        /**
         * The unique Public ID for this message
         *
         * @var string
         */
        public $MessageID;

        /**
         * The Account ID from who sent this message
         *
         * @var int
         */
        public $FromID;

        /**
         * The Account ID for who this message is for
         * (0 = Broadcast)
         *
         * @var int
         */
        public $ToID;

        /**
         * The internal message ID if this message is a reply to another
         * (0 = Not a reply)
         *
         * @var int
         */
        public $ReplyToID;

        /**
         * The subject for this message
         *
         * @var string
         */
        public $Subject;

        /**
         * The message contents
         *
         * @var string
         */
        public $Contents;

        /**
         * Indicates if this message is verified
         *
         * @var bool
         */
        public $Verified;

        /**
         * Indicates if this message was seen by the user
         * This value is not available if the message was a broadcast
         *
         * @var bool
         */
        public $Seen;

        /**
         * Indicates if the user is allowed to reply to this message
         *
         * @var bool
         */
        public $AllowReply;

        /**
         * Indicates if the sender deleted this message from their inbox
         *
         * @var bool
         */
        public $FromDeleted;

        /**
         * Indicates if the receiver deleted this message from their inbox
         *
         * @var bool
         */
        public $ToDeleted;

        /**
         * The Unix Timestamp of when this message was sent
         *
         * @var int
         */
        public $Timestamp;

        /**
         * Returns an array that represents this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'id' => (int)$this->ID,
                'message_id' => $this->MessageID,
                'from_id' => (int)$this->FromID,
                'to_id' => (int)$this->ToID,
                'reply_to_id' => (int)$this->ReplyToID,
                'subject' => $this->Subject,
                'contents' => $this->Contents,
                'verified' => (bool)$this->Verified,
                'seen' => (bool)$this->Seen,
                'allow_reply' => (bool)$this->AllowReply,
                'from_deleted' => (bool)$this->FromDeleted,
                'to_deleted' => (bool)$this->ToDeleted,
                'timestamp' => (int)$this->Timestamp
            );
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @return Message
         */
        public static function fromArray(array $data): Message
        {
            $MessageObject = new Message();

            if(isset($data['id']))
            {
                $MessageObject->ID = (int)$data['id'];
            }

            if(isset($data['message_id']))
            {
                $MessageObject->MessageID = $data['message_id'];
            }

            if(isset($data['from_id']))
            {
                $MessageObject->FromID = (int)$data['from_id'];
            }

            if(isset($data['to_id']))
            {
                $MessageObject->ToID = (int)$data['to_id'];
            }

            if(isset($data['reply_to_id']))
            {
                $MessageObject->ReplyToID = (int)$data['reply_to_id'];
            }

            if(isset($data['subject']))
            {
                $MessageObject->Subject = $data['subject'];
            }

            if(isset($data['contents']))
            {
                $MessageObject->Contents = $data['contents'];
            }

            if(isset($data['verified']))
            {
                $MessageObject->Verified = (bool)$data['verified'];
            }

            if(isset($data['seen']))
            {
                $MessageObject->Seen = (bool)$data['seen'];
            }

            if(isset($data['allow_reply']))
            {
                $MessageObject->AllowReply = (bool)$data['allow_reply'];
            }

            if(isset($data['from_deleted']))
            {
                $MessageObject->FromDeleted = (bool)$data['from_deleted'];
            }

            if(isset($data['to_deleted']))
            {
                $MessageObject->ToDeleted = (bool)$data['to_deleted'];
            }

            if(isset($data['timestamp']))
            {
                $MessageObject->Timestamp = (int)$data['timestamp'];
            }

            return $MessageObject;
        }
    }