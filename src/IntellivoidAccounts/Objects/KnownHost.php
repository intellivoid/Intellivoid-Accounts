<?php


    namespace IntellivoidAccounts\Objects;


    class KnownHost
    {
        /**
         * The internal database ID for this host
         *
         * @var int
         */
        public $ID;

        /**
         * The Public ID for this host record
         *
         * @var string
         */
        public $PublicID;

        /**
         * The IP Address
         *
         * @var string
         */
        public $IpAddress;

        /**
         * The account ID associated with this host
         *
         * @var int
         */
        public $AccountID;

        /**
         * Indicates if this host is verified and marked as safe by the user
         *
         * @var bool
         */
        public $Verified;

        /**
         * Indicates if this host was blocked by the user
         *
         * @var bool
         */
        public $Blocked;

        /**
         * Unix Timestamp for when this host was last used
         *
         * @var int
         */
        public $LastUsed;

        /**
         * The Unix Timestamp for when this host was registered into the system
         *
         * @var int
         */
        public $Created;

        /**
         * Returns an array that represents this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'id' => (int)$this->ID,
                'public_id' => $this->PublicID,
                'ip_address' => $this->IpAddress,
                'account_id' => (int)$this->AccountID,
                'verified' => (bool)$this->Verified,
                'blocked' => (bool)$this->Blocked,
                'last_used' => (int)$this->LastUsed,
                'created' => $this->LastUsed
            );
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @return KnownHost
         */
        public static function fromArray(array $data): KnownHost
        {
            $KnownHostObject = new KnownHost();

            if(isset($data['id']))
            {
                $KnownHostObject->ID = (int)$data['id'];
            }

            if(isset($data['public_id']))
            {
                $KnownHostObject->PublicID = $data['public_id'];
            }

            if(isset($data['ip_address']))
            {
                $KnownHostObject->IpAddress = $data['ip_address'];
            }

            if(isset($data['account_id']))
            {
                $KnownHostObject->AccountID = (int)$data['account_id'];
            }

            if(isset($data['verified']))
            {
                $KnownHostObject->Verified = (bool)$data['verified'];
            }

            if(isset($data['blocked']))
            {
                $KnownHostObject->Blocked = (bool)$data['blocked'];
            }

            if(isset($data['last_used']))
            {
                $KnownHostObject->LastUsed = (int)$data['last_used'];
            }

            if(isset($data['created']))
            {
                $KnownHostObject->Created = (int)$data['created'];
            }

            return $KnownHostObject;
        }
    }