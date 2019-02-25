<?php

    namespace IntellivoidAccounts\Objects;
    use IntellivoidAccounts\Objects\Account\PersonalInformation;

    /**
     * Class Account
     * @package IntellivoidAccounts\Objects
     */
    class Account
    {
        /**
         * The unique ID which identifies the account in the database
         *
         * @var int
         */
        public $ID;

        /**
         * The public ID for this Account
         *
         * @var string
         */
        public $PublicID;

        /**
         * The username for this account
         *
         * @var string
         */
        public $Username;

        /**
         * The Email Address for this Account
         *
         * @var string
         */
        public $Email;

        /**
         * The access password for this account (hashed)
         *
         * @var string
         */
        public $Password;

        /**
         * The status of the account
         *
         * @var int
         */
        public $Status;

        /**
         * Personal information related to the user
         *
         * @var PersonalInformation
         */
        public $PersonalInformation;

        /**
         * The ID which points to the last login record in the database
         *
         * @var int
         */
        public $LastLoginID;

        /**
         * The date that this account was created
         *
         * @var int
         */
        public $CreationDate;

    }