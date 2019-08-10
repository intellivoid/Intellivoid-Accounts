<?php


    namespace IntellivoidAccounts\Classes;


    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\AccountSuspendedException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\HostBlockedFromAccountException;
    use IntellivoidAccounts\Exceptions\HostNotKnownException;
    use IntellivoidAccounts\Exceptions\IncorrectLoginDetailsException;
    use IntellivoidAccounts\Exceptions\InvalidIpException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\IntellivoidAccounts;

    /**
     * Processes a secured login
     *
     * Class LoginProcessor
     * @package IntellivoidAccounts\Classes
     */
    class LoginProcessor
    {

        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * LoginProcessor constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        /**
         * Determines if the given credentials are correct and the host is not blocked
         * from accessing this account
         *
         * @param string $ip_address
         * @param string $username_email
         * @param string $password
         * @return bool
         * @throws HostBlockedFromAccountException
         * @throws AccountNotFoundException
         * @throws AccountSuspendedException
         * @throws DatabaseException
         * @throws HostNotKnownException
         * @throws IncorrectLoginDetailsException
         * @throws InvalidIpException
         * @throws InvalidSearchMethodException
         */
        public function verifyCredentials(string $ip_address, string $username_email, string $password): bool
        {
            $Account = $this->intellivoidAccounts->getAccountManager()->getAccountByAuth($username_email, $password);
            $Host = $this->intellivoidAccounts->getKnownHostsManager()->syncHost($ip_address, $Account->ID);

            if($Host->Blocked)
            {
                throw new HostBlockedFromAccountException();
            }

            return True;
        }
    }