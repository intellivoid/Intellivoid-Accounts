<?php


    namespace IntellivoidAccounts\Objects;


    use IntellivoidAccounts\Abstracts\KnownHostViolationStatus;
    use IntellivoidAccounts\Objects\KnownHost\Properties;

    /**
     * Class KnownHost
     * @package IntellivoidAccounts\Objects
     */
    class KnownHost
    {
        /**
         * The internal database ID for this host
         *
         * @var int
         */
        public int $ID;

        /**
         * The Public ID for this host record
         *
         * @var string
         */
        public string $PublicID;

        /**
         * The IP Address
         *
         * @var string
         */
        public string $IpAddress;

        /**
         * Indicates if this host was blocked by the user
         *
         * @var bool
         */
        public bool $Blocked;

        /**
         * The properties associated with this KnownHost
         *
         * @var Properties
         */
        public Properties $Properties;

        /**
         * Unix Timestamp for when this host was last used
         *
         * @var int
         */
        public int $LastUsed;

        /**
         * The location data associated with this host
         *
         * @var LocationData
         */
        public LocationData $LocationData;

        /**
         * The Unix Timestamp for when this host was registered into the system
         *
         * @var int
         */
        public int $Created;

        /**
         * KnownHost constructor.
         */
        public function __construct()
        {
            $this->Properties = new Properties();
        }

        /**
         * Checks the violation status and returns the status
         *
         * @return int
         */
        public function checkViolationStatus(): int
        {
            if($this->Blocked)
            {
                return KnownHostViolationStatus::HostBlockedByAdministrator;
            }

            // Check if the violation check fails
            if($this->Properties->AccountCreationLimitation->violationCheck() == false)
            {
                return KnownHostViolationStatus::HostBlockedAccountCreationLimit;
            }

            return KnownHostViolationStatus::NoViolation;
        }

        /**
         * Returns an array that represents this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                "id" => (int)$this->ID,
                "public_id" => $this->PublicID,
                "ip_address" => $this->IpAddress,
                "blocked" => (bool)$this->Blocked,
                "properties" => $this->Properties->toArray(),
                "last_used" => (int)$this->LastUsed,
                "location_data" => $this->LocationData->toArray(),
                "created" => $this->LastUsed
            );
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @return KnownHost
         */
        public static function fromArray(array $data): KnownHost
        {
            $KnownHostObject = new KnownHost();

            if(isset($data["id"]))
            {
                $KnownHostObject->ID = (int)$data["id"];
            }

            if(isset($data["public_id"]))
            {
                $KnownHostObject->PublicID = $data["public_id"];
            }

            if(isset($data["ip_address"]))
            {
                $KnownHostObject->IpAddress = $data["ip_address"];
            }

            if(isset($data["blocked"]))
            {
                $KnownHostObject->Blocked = (bool)$data["blocked"];
            }

            if(isset($data["properties"]))
            {
                $KnownHostObject->Properties = Properties::fromArray($data["properties"]);
            }

            if(isset($data["last_used"]))
            {
                $KnownHostObject->LastUsed = (int)$data["last_used"];
            }

            if(isset($data["location_data"]))
            {
                $KnownHostObject->LocationData = LocationData::fromArray($data["location_data"]);
            }
            else
            {
                $KnownHostObject->LocationData = new LocationData();
            }

            if(isset($data["created"]))
            {
                $KnownHostObject->Created = (int)$data["created"];
            }

            return $KnownHostObject;
        }
    }