<?php


    namespace IntellivoidAccounts\Objects;


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
         * The host ID associated with
         *
         * @var int
         */
        public $HostID;

        public $AccountID;

        public $Status;

        public $Timestamp;
    }