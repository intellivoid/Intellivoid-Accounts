<?php


    namespace IntellivoidAccounts\Objects;


    /**
     * Class ApplicationAccess
     * @package IntellivoidAccounts\Objects
     */
    class ApplicationAccess
    {
        /**
         * The internal Unique database ID for this record
         *
         * @var int
         */
        public int $ID;

        /**
         * The Public unique ID for this record
         *
         * @var string
         */
        public string $PublicID;

        /**
         * The internal Application ID that has access to the Account
         *
         * @var int
         */
        public int $ApplicationID;

        /**
         * The internal Account ID that has authentication relations with this Application
         *
         * @var int
         */
        public int $AccountID;

        /**
         * Permissions that this Application currently requests from the Account
         *
         * @var array
         */
        public array $Permissions;

        /**
         * The current status of the access of the Application has over the Account
         *
         * @var int
         */
        public int $Status;

        /**
         * The Unix Timestamp of when this record was created
         *
         * @var int
         */
        public int $CreationTimestamp;

        /**
         * The Unix Timestamp of when the Account has last authenticated to this Application
         *
         * @var int
         */
        public int $LastAuthenticatedTimestamp;

        /**
         * Returns an array for this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'id' => (int)$this->ID,
                'public_id' => $this->PublicID,
                'application_id' => (int)$this->ApplicationID,
                'account_id' => (int)$this->AccountID,
                'permissions' => $this->Permissions,
                'status' => (int)$this->Status,
                'creation_timestamp' => (int)$this->CreationTimestamp,
                'last_authenticated_timestamp' => $this->LastAuthenticatedTimestamp
            );
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return ApplicationAccess
         */
        public static function fromArray(array $data): ApplicationAccess
        {
            $ApplicationAccessObject = new ApplicationAccess();

            if(isset($data['id']))
            {
                $ApplicationAccessObject->ID = (int)$data['id'];
            }

            if(isset($data['public_id']))
            {
                $ApplicationAccessObject->PublicID = $data['public_id'];
            }

            if(isset($data['application_id']))
            {
                $ApplicationAccessObject->ApplicationID = (int)$data['application_id'];
            }

            if(isset($data['account_id']))
            {
                $ApplicationAccessObject->AccountID = (int)$data['account_id'];
            }

            if(isset($data['permissions']))
            {
                $ApplicationAccessObject->Permissions = $data['permissions'];
            }
            else
            {
                $ApplicationAccessObject->Permissions = [];
            }

            if(isset($data['status']))
            {
                $ApplicationAccessObject->Status = (int)$data['status'];
            }

            if(isset($data['creation_timestamp']))
            {
                $ApplicationAccessObject->CreationTimestamp = (int)$data['creation_timestamp'];
            }

            if(isset($data['last_authenticated_timestamp']))
            {
                $ApplicationAccessObject->LastAuthenticatedTimestamp = (int)$data['last_authenticated_timestamp'];
            }

            return $ApplicationAccessObject;
        }
    }