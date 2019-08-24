<?php


    namespace IntellivoidAccounts\Objects\TelegramClient;

    /**
     * Class SessionData
     * @package IntellivoidAccounts\Objects\TelegramClient
     */
    class SessionData
    {
        /**
         * Session data for this Telegram Client
         *
         * @var array
         */
        public $Data;

        /**
         * Sets session data to the unique identifier
         *
         * @param string $identifier
         * @param string $key
         * @param $data
         */
        public function setData(string $identifier, string $key, $data)
        {
            if(isset($this->Data[$identifier]) == false)
            {
                $this->Data[$identifier] = array();
            }

            $this->Data[$identifier][$key] = $data;
        }

        /**
         * @param string $identifier
         * @param string $key
         * @return mixed|null
         */
        public function getData(string $identifier, string $key)
        {
            if(isset($this->Data[$identifier][$key]))
            {
                return $this->Data[$identifier][$key];
            }

            return null;
        }

        /**
         * Removes a data key from the session
         *
         * @param string $identifier
         * @param string $key
         */
        public function removeData(string $identifier, string $key)
        {
            if(isset($this->Data[$identifier][$key]))
            {
                unset($this->Data[$identifier][$key]);
            }
        }

        /**
         * Determines if the key exists
         *
         * @param string $identifier
         * @param string $key
         * @return bool
         */
        public function keyExists(string $identifier, string $key): bool
        {
            if(isset($this->Data[$identifier][$key]))
            {
                return true;
            }

            return false;
        }

        /**
         * Returns the array of this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return $this->Data;
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @return SessionData
         */
        public static function fromArray(array $data): SessionData
        {
            $SessionDataObject = new SessionData();

            $SessionDataObject->Data = $data;

            return $SessionDataObject;
        }
    }