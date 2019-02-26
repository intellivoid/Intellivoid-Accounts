<?php

    namespace IntellivoidAccounts\Managers;
    use IntellivoidAccounts\Exceptions\InvalidEmailException;
    use IntellivoidAccounts\Exceptions\InvalidPasswordException;
    use IntellivoidAccounts\Exceptions\InvalidUsernameException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\Account;
    use IntellivoidAccounts\Utilities\Validate;

    /**
     * Class AccountManager
     * @package IntellivoidAccounts\Managers
     */
    class AccountManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * AccountManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        public function registerAccount(string $username, string $email, string $password): Account
        {
            if(Validate::username($username) == false)
            {
                throw new InvalidUsernameException();
            }

            if(Validate::email($email) == false)
            {
                throw new InvalidEmailException();
            }

            if(Validate::password($password) == false)
            {
                throw new InvalidPasswordException();
            }


        }
    }