<?php


    namespace IntellivoidAccounts\Objects;

    use IntellivoidAccounts\Abstracts\LoginStatus;

    /**
     * Class UserLoginRecord
     * @package IntellivoidAccounts\Objects
     */
    class UserLoginRecord
    {
        /**
         * Internal unique database ID for this login record
         *
         * @var int
         */
        public $ID;

        /**
         * Public unique ID for this login record
         *
         * @var string
         */
        public $PublicID;

        /**
         * The origin of where this login came from
         *
         * @var string
         */
        public $Origin;

        /**
         * The host ID associated with this login record
         *
         * @var int
         */
        public $HostID;

        /**
         * The account ID associated with this Login Record
         *
         * @var int
         */
        public $AccountID;

        /**
         * The status of the login
         *
         * @var LoginStatus|int
         */
        public $Status;

        /**
         * The Unix Timestamp of when this record was created
         *
         * @var int
         */
        public $Timestamp;
    }