<?php

    namespace IntellivoidAccounts\Objects\Account;
    use IntellivoidAccounts\Objects\Account\PersonalInformation\BirthDate;

    /**
     * Class PersonalInformation
     * @package IntellivoidAccounts\Objects\Account
     */
    class PersonalInformation
    {
        /**
         * The first name of the user
         *
         * @var string
         */
        public $FirstName;

        /**
         * The last name of the user
         *
         * @var string
         */
        public $LastName;

        /**
         * The country that this user is located in
         *
         * @var string
         */
        public $Country;

        /**
         * The date of birth
         *
         * @var BirthDate
         */
        public $BirthDate;

        /**
         * User's phone number
         *
         * @var string
         */
        public $PhoneNumber;
    }