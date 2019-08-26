<?php


    namespace IntellivoidAccounts\Managers;

    use Exception;
    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\HostNotKnownException;
    use IntellivoidAccounts\Exceptions\InvalidIpException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\KnownHost;
    use IntellivoidAccounts\Objects\LocationData;
    use IntellivoidAccounts\Objects\UserAgent;
    use IntellivoidAccounts\Utilities\Hashing;
    use IntellivoidAccounts\Utilities\Validate;
    use IPStack\IPStack;
    use ZiProto\ZiProto;


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
            $this->ip_stack = new IPStack('');
        }

        /**
         * Syncs the host into the database
         *
         * @param string $ip_address
         * @param string $user_agent
         * @return KnownHost
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws HostNotKnownException
         * @throws InvalidIpException
         * @throws InvalidSearchMethodException
         */
        public function syncHost(string $ip_address, string $user_agent): KnownHost
        {
            if($this->hostKnown($ip_address) == true)
            {
                $KnownHost = $this->getHost($ip_address);
                $KnownHost->LastUsed = time();
                $this->updateKnownHost($KnownHost);
                return $KnownHost;
            }

            if(Validate::ip($ip_address) == false)
            {
                throw new InvalidIpException();
            }

            $timestamp = (int)time();
            $public_id = Hashing::knownHostPublicID($ip_address, $user_agent, $timestamp);
            $public_id = $this->intellivoidAccounts->database->real_escape_string($public_id);
            $ip_address = $this->intellivoidAccounts->database->real_escape_string($ip_address);
            $blocked = 0;
            $last_used = $timestamp;

            // Fetch location data
            $location_data = new LocationData();
            $location_data->LastUpdated = time();

            try
            {
                $UseSSL = false;

                if(strtolower($this->intellivoidAccounts->getIpStackConfiguration()['UseSSL']) == 'true')
                {
                    $UseSSL = true;
                }

                $IPStack = new IPStack(
                    $this->intellivoidAccounts->getIpStackConfiguration()["AccessKey"],
                    $UseSSL,
                    $this->intellivoidAccounts->getIpStackConfiguration()['IpStackHost']
                );

                $Results = $IPStack->lookup($ip_address);

                $location_data->CountryName = $Results->CountryName;
                $location_data->ContinentCode = $Results->ContinentCode;
                $location_data->ZipCode = $Results->Zip;
                $location_data->ContinentName = $Results->ContinentName;
                $location_data->CountryCode = $Results->CountryCode;
                $location_data->City = $Results->City;
                $location_data->Longitude = $Results->Longitude;
                $location_data->Latitude = $Results->Latitude;
            }
            catch(Exception $exception)
            {
                // Ignore the error
            }

            $location_data = ZiProto::encode($location_data->toArray());
            $location_data = $this->intellivoidAccounts->database->real_escape_string($location_data);

            // Parse the user agent if available
            $user_agent = null;

            if(Validate::userAgent($user_agent) == false)
            {
                $user_agent = new UserAgent();
                $user_agent->UserAgentString = "None";
            }
            else
            {
                $user_agent = UserAgent::fromString($user_agent);
            }

            $user_agents = [];
            $user_agents[] = $user_agent->toArray();
            $user_agents = ZiProto::encode($user_agents);
            $user_agents = $this->intellivoidAccounts->database->real_escape_string($user_agents);

            $Query = "INSERT INTO `users_known_hosts` (public_id, ip_address, blocked, last_used, location_data, user_agents, created) VALUES ('$public_id', '$ip_address', $blocked, $last_used, '$location_data', '$user_agents', $timestamp)";
            $QueryResults = $this->intellivoidAccounts->database->query($Query);

            if($QueryResults)
            {
                return $this->getHost($ip_address);
            }

            throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
        }

        /**
         * Gets the known host from the database if it exists
         *
         * @param string $ip_address
         * @return KnownHost
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws HostNotKnownException
         * @throws InvalidIpException
         * @throws InvalidSearchMethodException
         */
        public function getHost(string $ip_address): KnownHost
        {
            if(Validate::ip($ip_address) == false)
            {
                throw new InvalidIpException();
            }

            $ip_address = $this->intellivoidAccounts->database->real_escape_string($ip_address);
            $account_id = (int)$account_id;

            $Query = "SELECT * FROM `users_known_hosts` WHERE ip_address='$ip_address'";
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