<?php /** @noinspection PhpUnused */


    namespace IntellivoidAccounts\Managers;

    use Exception;
    use IntellivoidAccounts\Abstracts\SearchMethods\KnownHostsSearchMethod;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\HostNotKnownException;
    use IntellivoidAccounts\Exceptions\InvalidIpException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\Exceptions\UserAgentNotFoundException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\KnownHost;
    use IntellivoidAccounts\Objects\LocationData;
    use IntellivoidAccounts\Utilities\Hashing;
    use IntellivoidAccounts\Utilities\Validate;
    use IPStack\IPStack;
    use msqg\QueryBuilder;
    use VerboseAdventure\Abstracts\EventType;
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
         * @throws DatabaseException
         * @throws HostNotKnownException
         * @throws InvalidIpException
         * @throws InvalidSearchMethodException
         * @throws UserAgentNotFoundException
         */
        public function syncHost(string $ip_address, string $user_agent): KnownHost
        {
            $this->intellivoidAccounts->getLogHandler()->log(EventType::INFO, "Syncing host '$ip_address' ($user_agent)", get_class($this));

            if($this->hostKnown($ip_address) == true)
            {
                $KnownHost = $this->getHost(KnownHostsSearchMethod::byIpAddress, $ip_address);
                $KnownHost->LastUsed = time();
                if((time() - $KnownHost->LocationData->LastUpdated) > 172800)
                {
                    $KnownHost->LocationData = $this->getLocationData($ip_address);
                }

                // NEW: Added TrackingUserAgents instead.
                $this->intellivoidAccounts->getTrackingUserAgentManager()->syncRecord($user_agent, $KnownHost->ID);

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

            $location_data = $this->getLocationData($ip_address);
            $location_data = ZiProto::encode($location_data->toArray());
            $location_data = $this->intellivoidAccounts->database->real_escape_string($location_data);

            $properties = ZiProto::encode(new KnownHost\Properties());
            $properties = $this->intellivoidAccounts->database->real_escape_string($properties);

            $Query = QueryBuilder::insert_into("users_known_hosts", array(
                "public_id" => $public_id,
                "ip_address" => $ip_address,
                "blocked" => $blocked,
                "properties" => $properties,
                "last_used" => $last_used,
                "location_data" => $location_data,
                "created" => $timestamp
            ));
            $QueryResults = $this->intellivoidAccounts->database->query($Query);

            if($QueryResults)
            {
                $host = $this->getHost(KnownHostsSearchMethod::byPublicId, $public_id);
                $this->intellivoidAccounts->getTrackingUserAgentManager()->syncRecord($user_agent, $host->ID);
                return $host;
            }

            $exception = new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            $this->intellivoidAccounts->getLogHandler()->logException($exception, get_class($this));
            throw $exception;
        }

        /**
         * Fetches location data of the given IP Address
         *
         * @param string $ip_address
         * @return LocationData
         */
        private function getLocationData(string $ip_address)
        {
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
                $this->intellivoidAccounts->getLogHandler()->log(EventType::WARNING, $exception->getMessage(), get_class($this));
                $this->intellivoidAccounts->getLogHandler()->logException($exception, get_class($this));
                // Ignore the error
            }

            return $location_data;
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
                    $value = $this->intellivoidAccounts->database->real_escape_string($value);
                    break;

                case KnownHostsSearchMethod::byIpAddress:
                    if(Validate::ip($value) == false)
                    {
                        throw new InvalidIpException();
                    }
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = $this->intellivoidAccounts->database->real_escape_string($value);
                    break;
            }

            $Query = QueryBuilder::select("users_known_hosts", [
                "id",
                "public_id",
                "ip_address",
                "blocked",
                "properties",
                "location_data",
                "last_used",
                "created"
            ], $search_method, $value);
            $QueryResults = $this->intellivoidAccounts->database->query($Query);

            if($QueryResults == false)
            {
                $exception = new DatabaseException($Query, $this->intellivoidAccounts->database->error);
                $this->intellivoidAccounts->getLogHandler()->logException($exception, get_class($this));
                throw $exception;
            }
            else
            {
                if($QueryResults->num_rows !== 1)
                {
                    throw new HostNotKnownException();
                }

                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);
                $Row["location_data"] = ZiProto::decode($Row["location_data"]);

                if($Row["properties"] == null)
                {
                    $PropertiesObject = new KnownHost\Properties();
                    $Row["properties"] = $PropertiesObject->toArray();
                }
                else
                {
                    $Row["properties"] = ZiProto::decode($Row["properties"]);
                }

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
            $properties = $this->intellivoidAccounts->database->real_escape_string(ZiProto::encode($knownHost->Properties->toArray()));
            $location_data = ZiProto::encode($knownHost->LocationData->toArray());
            $location_data = $this->intellivoidAccounts->database->real_escape_string($location_data);
            $last_used = (int)$knownHost->LastUsed;

            $Query = QueryBuilder::update("users_known_hosts", array(
                "ip_address" => $ip_address,
                "blocked" => $blocked,
                "properties" => $properties,
                "location_data" => $location_data,
                "last_used" => $last_used
            ), "public_id", $public_id);
            $QueryResults = $this->intellivoidAccounts->database->query($Query);

            if($QueryResults)
            {
                return True;
            }

            $exception = new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            $this->intellivoidAccounts->getLogHandler()->logException($exception, get_class($this));
            throw $exception;
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