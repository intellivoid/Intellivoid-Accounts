<?php


    namespace IntellivoidAccounts\Objects\COA;

    /**
     * Class AuthenticationAccess
     * @package IntellivoidAccounts\Objects\COA
     */
    class AuthenticationAccess
    {
        /**
         * The ID of the authentication access
         *
         * @var int
         */
        public $ID;

        /**
         * The access token used to retrieve information about the authenticated account
         *
         * @var string
         */
        public $AccessToken;

        /**
         * The ID of the application that issued this authentication access
         *
         * @var int
         */
        public $ApplicationId;

        /**
         * The id of the account that's authenticated
         *
         * @var int
         */
        public $AccountId;

        /**
         * The id of the authentication request that created this authentication access
         *
         * @var int
         */
        public $RequestId;

        /**
         * The current status of the access
         *
         * @var int
         */
        public $Status;

        /**
         * The Unix Timestamp of when this access expires
         *
         * @var int
         */
        public $ExpiresTimestamp;

        /**
         * The Unix Timestamp of when this record was last used
         *
         * @var int
         */
        public $LastUsedTimestamp;

        /**
         * The Unix Timestamp of when this record was created
         *
         * @var int
         */
        public $CreatedTimestamp;

        /**
         * Creates array from object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'id' => (int)$this->ID,
                'access_token' => $this->AccessToken,
                'application_id' => (int)$this->ApplicationId,
                'account_id' => (int)$this->AccountId,
                'request_id' => (int)$this->RequestId,
                'status' => (int)$this->Status,
                'expires_timestamp' => (int)$this->ExpiresTimestamp,
                'last_used_timestamp' => (int)$this->LastUsedTimestamp,
                'created_timestamp' => $this->CreatedTimestamp
            );
        }
    }