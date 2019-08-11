<?php


    namespace IntellivoidAccounts\Managers;

    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\HostNotKnownException;
    use IntellivoidAccounts\Exceptions\InvalidIpException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\KnownHost;
    use IntellivoidAccounts\Utilities\Hashing;
    use IntellivoidAccounts\Utilities\Validate;


    /**
     * Class KnownHostsManager
     * @package IntellivoidAccounts\Managers
     */
    class KnownHostsManager
    {

        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * KnownHostsManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        /**
         * Syncs the host into the database
         *
         * @param string $ip_address
         * @param int $account_id
         * @return KnownHost
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws HostNotKnownException
         * @throws InvalidIpException
         * @throws InvalidSearchMethodException
         */
        public function syncHost(string $ip_address, int $account_id): KnownHost
        {
            if($this->hostKnown($ip_address, $account_id) == true)
            {
                $KnownHost = $this->getHost($ip_address, $account_id);
                $KnownHost->LastUsed = time();
                $this->updateKnownHost($KnownHost);
                return $KnownHost;
            }

            if(Validate::ip($ip_address) == false)
            {
                throw new InvalidIpException();
            }

            if($this->intellivoidAccounts->getAccountManager()->IdExists($account_id) == false)
            {
                throw new AccountNotFoundException();
            }

            $timestamp = (int)time();
            $public_id = Hashing::knownHostPublicID($account_id, $ip_address, $timestamp);
            $public_id = $this->intellivoidAccounts->database->real_escape_string($public_id);
            $ip_address = $this->intellivoidAccounts->database->real_escape_string($ip_address);
            $account_id = (int)$account_id;
            $verified = 0;
            $blocked = 0;
            $last_used = $timestamp;

            $Query = "INSERT INTO `users_known_hosts` (public_id, ip_address, account_id, verified, blocked, last_used, created) VALUES ('$public_id', '$ip_address', $account_id, $verified, $blocked, $last_used, $timestamp)";
            $QueryResults = $this->intellivoidAccounts->database->query($Query);

            if($QueryResults)
            {
                return $this->getHost($ip_address, $account_id);
            }

            throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
        }

        /**
         * Gets the known host from the database if it exists
         *
         * @param string $ip_address
         * @param int $account_id
         * @return KnownHost
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws HostNotKnownException
         * @throws InvalidIpException
         * @throws InvalidSearchMethodException
         */
        public function getHost(string $ip_address, int $account_id): KnownHost
        {
            if(Validate::ip($ip_address) == false)
            {
                throw new InvalidIpException();
            }

            if($this->intellivoidAccounts->getAccountManager()->IdExists($account_id) == false)
            {
                throw new AccountNotFoundException();
            }

            $ip_address = $this->intellivoidAccounts->database->real_escape_string($ip_address);
            $account_id = (int)$account_id;

            $Query = "SELECT * FROM `users_known_hosts` WHERE ip_address='$ip_address' AND account_id='$account_id'";
            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }
            else
            {
                if($QueryResults->num_rows !== 1)
                {
                    throw new HostNotKnownException();
                }

                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);
                return KnownHost::fromArray($Row);
            }
        }

        /**
         * Updates an existing host in the database
         *
         * @param KnownHost $knownHost
         * @return bool
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws HostNotKnownException
         * @throws InvalidIpException
         * @throws InvalidSearchMethodException
         */
        public function updateKnownHost(KnownHost $knownHost): bool
        {
            if($this->hostKnown($knownHost->IpAddress, $knownHost->AccountID) == false)
            {
                throw new HostNotKnownException();
            }

            if(Validate::ip($knownHost->IpAddress) == false)
            {
                throw new InvalidIpException();
            }

            if($this->intellivoidAccounts->getAccountManager()->IdExists($knownHost->AccountID) == false)
            {
                throw new AccountNotFoundException();
            }

            $public_id = $this->intellivoidAccounts->database->real_escape_string($knownHost->PublicID);
            $ip_address = $this->intellivoidAccounts->database->real_escape_string($knownHost->IpAddress);
            $account_id = (int)$knownHost->AccountID;
            $verified = (int)$knownHost->Verified;
            $blocked = (int)$knownHost->Blocked;
            $last_used = (int)$knownHost->LastUsed;

            $Query = "UPDATE `users_known_hosts` SET ip_address='$ip_address', account_id=$account_id, verified=$verified, blocked=$blocked, last_used=$last_used WHERE public_id='$public_id'";
            $QueryResults = $this->intellivoidAccounts->database->query($Query);

            if($QueryResults)
            {
                return True;
            }

            throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
        }

        /**
         * Determines if the host is known or not
         *
         * @param string $ip_address
         * @param int $account_id
         * @return bool
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws InvalidIpException
         * @throws InvalidSearchMethodException
         */
        public function hostKnown(string $ip_address, int $account_id): bool
        {
            try
            {
                $this->getHost($ip_address, $account_id);
                return True;
            }
            catch(HostNotKnownException $hostNotKnownException)
            {
                return False;
            }
        }

    }