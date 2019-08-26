<?php


    namespace IntellivoidAccounts\Managers;

    use Exception;
    use IntellivoidAccounts\Abstracts\SearchMethods\KnownHostsSearchMethod;
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
         * @var IPStack
         */
        private $ip_stack;

        /**
         * KnownHostsManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
            
            $UseSSL = false;

            if(strtolower($this->intellivoidAccounts->getIpStackConfiguration()['UseSSL']) == 'true')
            {
                $UseSSL = true;
            }
            
            $this->ip_stack = new IPStack(
                $intellivoidAccounts->getIpStackConfiguration()["AccessKey"],
                $UseSSL,
                $intellivoidAccounts->getIpStackConfiguration()['IpStackHost']
            );
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
                $KnownHost = $this->getHost(KnownHostsSearchMethod::byPublicId, $ip_address);
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
                $Results = $this->ip_stack->lookup($ip_address);
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
                return $this->getHost(KnownHostsSearchMethod::byPublicId, $ip_address);
            }

            throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
        }

        /**
         * Gets the known host from the database if it exists
         *
         * @param string $search_method
         * @param string $value
         * @return KnownHost
         * @throws DatabaseException
         * @throws HostNotKnownException
         * @throws InvalidIpException
         */
        public function getHost(string $search_method, string $value): KnownHost
        {
            switch($search_method)
            {
                case KnownHostsSearchMethod::byId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                case KnownHostsSearchMethod::byPublicId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = "'" . $this->intellivoidAccounts->database->real_escape_string($value) . "'";
                    break;

                case KnownHostsSearchMethod::byIpAddress:
                    if(Validate::ip($value) == false)
                    {
                        throw new InvalidIpException();
                    }
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = "'" . $this->intellivoidAccounts->database->real_escape_string($value) . "'";
                    break;
            }

            $Query = "SELECT * FROM `users_known_hosts` WHERE $search_method=$value";
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
         * @throws DatabaseException
         * @throws HostNotKnownException
         * @throws InvalidIpException
         */
        public function updateKnownHost(KnownHost $knownHost): bool
        {
            if($this->hostKnown($knownHost->IpAddress) == false)
            {
                throw new HostNotKnownException();
            }

            if(Validate::ip($knownHost->IpAddress) == false)
            {
                throw new InvalidIpException();
            }

            $public_id = $this->intellivoidAccounts->database->real_escape_string($knownHost->PublicID);
            $ip_address = $this->intellivoidAccounts->database->real_escape_string($knownHost->IpAddress);
            $blocked = (int)$knownHost->Blocked;
            $location_data = ZiProto::encode($knownHost->LocationData->toArray());
            $location_data = $this->intellivoidAccounts->database->real_escape_string($location_data);
            $user_agents = ZiProto::encode($knownHost->toArray()['user_agents']);
            $user_agents = $this->intellivoidAccounts->database->real_escape_string($user_agents);
            $last_used = (int)$knownHost->LastUsed;

            $Query = "UPDATE `users_known_hosts` SET ip_address='$ip_address', blocked=$blocked, location_data='$location_data', user_agents='$user_agents', last_used=$last_used WHERE public_id='$public_id'";
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
         * @return bool
         * @throws DatabaseException
         * @throws InvalidIpException
         */
        public function hostKnown(string $ip_address): bool
        {
            try
            {
                $this->getHost(KnownHostsSearchMethod::byIpAddress, $ip_address);
                return True;
            }
            catch(HostNotKnownException $hostNotKnownException)
            {
                return False;
            }
        }

    }