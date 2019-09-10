<?php


    namespace IntellivoidAccounts\Objects\COA;

    /**
     * Class AuthenticationRequest
     * @package IntellivoidAccounts\Objects\COA
     */
    class AuthenticationRequest
    {
        /**
         * Internal unique database ID
         *
         * @var int
         */
        public $Id;

        /**
         * Public Request Token
         *
         * @var string
         */
        public $RequestToken;

        /**
         * The application ID that issued this authentication request
         *
         * @var int
         */
        public $ApplicationId;

        /**
         * The status of the request authentication
         *
         * @var int
         */
        public $Status;

        /**
         * The account ID authenticated with this request. 0 means none
         *
         * @var int
         */
        public $AccountId;

        /**
         * The ID of the known host that generated this Request
         *
         * @var int
         */
        public $HostId;

        /**
         * The Unix Timestamp of when this request was generated
         *
         * @var int
         */
        public $CreatedTimestamp;

        /**
         * The Unix Timestamp of when this request expires
         *
         * @var int
         */
        public $ExpiresTimestamp;

        /**
         * Creates an array that represents this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'id' => (int)$this->Id,
                'request_token' => $this->RequestToken,
                'application_id' => (int)$this->ApplicationId,
                'status' => (int)$this->Status,
                'account_id' => (int)$this->AccountId,
                'host_id' => (int)$this->HostId,
                'created_timestamp' => (int)$this->CreatedTimestamp,
                'expires_timestamp' => (int)$this->ExpiresTimestamp
            );
        }

        /**
         * Constructs the object from an array
         *
         * @param array $data
         * @return AuthenticationRequest
         */
        public static function fromArray(array $data): AuthenticationRequest
        {
            $AuthenticationRequestObject = new AuthenticationRequest();

            if(isset($data['id']))
            {
                $AuthenticationRequestObject->ID  = (int)$data['id'];
            }

            if(isset($data['request_token']))
            {
                $AuthenticationRequestObject->RequestToken = $data['request_token'];
            }

            if(isset($data['application_id']))
            {
                $AuthenticationRequestObject->ApplicationId = (int)$data['application_id'];
            }

            if(isset($data['status']))
            {
                $AuthenticationRequestObject->Status = (int)$data['status'];
            }

            if(isset($data['account_id']))
            {
                $AuthenticationRequestObject->AccountId = (int)$data['account_id'];
            }
            else
            {
                $AuthenticationRequestObject->AccountId = 0;
            }

            if(isset($data['host_id']))
            {
                $AuthenticationRequestObject->HostId = (int)$data['host_id'];
            }

            if(isset($data['created_timestamp']))
            {
                $AuthenticationRequestObject->CreatedTimestamp = (int)$data['created_timestamp'];
            }

            if(isset($data['expires_timestamp']))
            {
                $AuthenticationRequestObject->ExpiresTimestamp = (int)$data['expires_timestamp'];
            }

            return $AuthenticationRequestObject;
        }
    }