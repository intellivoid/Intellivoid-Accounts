<?php /** @noinspection PhpMissingFieldTypeInspection */


    namespace IntellivoidAccounts\Objects;

    use IntellivoidAccounts\Abstracts\ApplicationSettingsDatumType;
    use IntellivoidAccounts\Exceptions\InvalidDataTypeForDatumException;
    use IntellivoidAccounts\Exceptions\InvalidDatumTypeException;
    use IntellivoidAccounts\Exceptions\MalformedJsonDataException;
    use IntellivoidAccounts\Exceptions\VariableNameAlreadyExistsException;
    use IntellivoidAccounts\Exceptions\VariableNotFoundException;
    use IntellivoidAccounts\Interfaces\iApplicationSettingsDatumType;
    use IntellivoidAccounts\Objects\ApplicationSettings\DatumArray;
    use IntellivoidAccounts\Objects\ApplicationSettings\DatumBoolean;
    use IntellivoidAccounts\Objects\ApplicationSettings\DatumInteger;
    use IntellivoidAccounts\Objects\ApplicationSettings\DatumList;
    use IntellivoidAccounts\Objects\ApplicationSettings\DatumString;
    use IntellivoidAccounts\Utilities\Converter;
    use ZiProto\ZiProto;

    /**
     * Class ApplicationSettings
     * @package IntellivoidAccounts\Objects
     */
    class ApplicationSettings
    {
        /**
         * The ID of the record
         *
         * @var int
         */
        public $ID;

        /**
         * The Public ID for this record
         *
         * @var string
         */
        public $PublicID;

        /**
         * The Application ID that manages this record
         *
         * @var int
         */
        public $ApplicationID;

        /**
         * The Account ID that this record belongs to
         *
         * @var int
         */
        public $AccountID;

        /**
         * The data associated with this record
         *
         * @var iApplicationSettingsDatumType[]
         */
        public $Data;

        /**
         * The Unix Timestamp for when this record was last updated
         *
         * @var int
         */
        public $LastUpdatedTimestamp;

        /**
         * The Unix Timestamp for when this record was initially created
         *
         * @var int
         */
        public $CreatedTimestamp;

        /**
         * ApplicationSettings constructor.
         */
        public function __construct()
        {
            $this->Data = [];
        }

        /**
         * Adds a new value to the Application Settings and variables
         *
         * @param int $type
         * @param bool $overwrite
         * @param string $name
         * @param $value
         * @return iApplicationSettingsDatumType
         * @throws InvalidDataTypeForDatumException
         * @throws VariableNameAlreadyExistsException
         * @throws InvalidDatumTypeException
         * @throws MalformedJsonDataException
         */
        public function add(int $type, string $name, $value, bool $overwrite=true): iApplicationSettingsDatumType
        {
            $LastUpdatedTimestamp = null;
            if(isset($this->Data[$name]))
            {
                if($overwrite == false)
                {
                    throw new VariableNameAlreadyExistsException("The variable '$name' already exists");
                }

                $LastUpdatedTimestamp = $this->Data[$name]->getLastUpdatedTimestamp();
            }

            switch($type)
            {
                case ApplicationSettingsDatumType::string:
                    $this->Data[$name] = new DatumString();
                    if($value !== null)
                        $this->Data[$name]->setValue((string)$value);
                    if($LastUpdatedTimestamp !== null)
                        $this->Data[$name]->setLastUpdatedTimestamp($LastUpdatedTimestamp);
                    break;

                case ApplicationSettingsDatumType::boolean:
                    $this->Data[$name] = new DatumBoolean();
                    if($value !== null)
                        $this->Data[$name]->setValue((bool)$value);
                    if($LastUpdatedTimestamp !== null)
                        $this->Data[$name]->setLastUpdatedTimestamp($LastUpdatedTimestamp);
                    break;

                case ApplicationSettingsDatumType::integer:
                    $this->Data[$name] = new DatumInteger();
                    if($value !== null)
                        $this->Data[$name]->setValue((int)$value);
                    if($LastUpdatedTimestamp !== null)
                        $this->Data[$name]->setLastUpdatedTimestamp($LastUpdatedTimestamp);
                    break;

                case ApplicationSettingsDatumType::list:
                    $this->Data[$name] = new DatumList();
                    if($value !== null)
                    {
                        $DecodedContents = json_decode($value, true);

                        if ($DecodedContents === null && json_last_error() !== JSON_ERROR_NONE)
                           throw new MalformedJsonDataException("Expected JSON data, failed to parse.");

                        $this->Data[$name]->setValue($DecodedContents);
                    }
                    if($LastUpdatedTimestamp !== null)
                        $this->Data[$name]->setLastUpdatedTimestamp($LastUpdatedTimestamp);
                    break;

                case ApplicationSettingsDatumType::array:
                    $this->Data[$name] = new DatumArray();
                    if($value !== null)
                    {
                        $DecodedContents = json_decode($value, true);

                        if ($DecodedContents === null && json_last_error() !== JSON_ERROR_NONE)
                            throw new MalformedJsonDataException("Expected JSON data, failed to parse.");

                        $this->Data[$name]->setValue($DecodedContents);
                    }
                    if($LastUpdatedTimestamp !== null)
                        $this->Data[$name]->setLastUpdatedTimestamp($LastUpdatedTimestamp);
                    break;

                default:
                    throw new InvalidDatumTypeException("The datum type '$type' is not valid");

            }

            return $this->Data[$name];
        }

        /**
         * Returns the requested variable
         *
         * @param string $name
         * @return iApplicationSettingsDatumType
         * @throws VariableNotFoundException
         */
        public function get(string $name): iApplicationSettingsDatumType
        {
            if(isset($this->Data[$name]) == false)
            {
                throw new VariableNotFoundException("The variable '$name' does not exist in the current context");
            }

            return $this->Data[$name];
        }

        /**
         * Deletes the requested variable
         *
         * @param string $name
         * @throws VariableNotFoundException
         */
        public function delete(string $name)
        {
            if(isset($this->Data[$name]) == false)
            {
                throw new VariableNotFoundException("The variable '$name' does not exist in the current context");
            }

            unset($this->Data[$name]);
        }

        /**
         * Calculates the size of the object when encoded
         *
         * @return int
         */
        public function calculateSize(): int
        {
            return strlen(ZiProto::encode($this->dataToArray()));
        }

        /**
         * Returns a summary of all the variables
         *
         * @return array
         * @noinspection PhpUnused
         */
        public function getSummary(): array
        {
            $Results = [
                "variables" => [],
                "size" => $this->calculateSize()
            ];

            foreach($this->Data as $name => $datum)
            {
                $Results["variables"][$name] = [
                    "type" => Converter::applicationDatumTypeToString($datum->getCurrentType()),
                    "created_timestamp" => $datum->getCreatedTimestamp(),
                    "last_updated_timestamp" => $datum->getLastUpdatedTimestamp(),
                    "size" => strlen(ZiProto::encode($datum->toArray()))
                ];
            }

            return $Results;
        }

        /**
         * Dumps the data
         *
         * @param bool $include_meta
         * @return array
         */
        public function dump(bool $include_meta=false): array
        {
            $Results = [];

            foreach($this->Data as $name => $datum)
            {
                if($include_meta)
                {
                    $Results[$name] = [
                        "type" => Converter::applicationDatumTypeToString($datum->getCurrentType()),
                        "value" => $datum->getData(),
                        "created_timestamp" => $datum->getCreatedTimestamp(),
                        "last_updated_timestamp" => $datum->getLastUpdatedTimestamp(),
                        "size" => strlen(ZiProto::encode($datum->toArray()))
                    ];
                }
                else
                {
                    $Results[$name] = $datum->getData();

                }
            }

            return $Results;
        }

        /**
         * Returns an array structure for this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                "id" => $this->ID,
                "public_id" => $this->PublicID,
                "application_id" => $this->ApplicationID,
                "account_id" => $this->AccountID,
                "data" => $this->dataToArray(),
                "last_updated_timestamp" => $this->LastUpdatedTimestamp,
                "created_timestamp" => $this->CreatedTimestamp
            );
        }

        /**
         * Returns an array representation of the data
         *
         * @return array
         */
        public function dataToArray(): array
        {
            $results = array();

            foreach($this->Data as $name => $datum)
            {
                $results[$name] = $datum->toArray();
            }

            return $results;
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return ApplicationSettings
         * @throws InvalidDataTypeForDatumException
         * @throws InvalidDatumTypeException
         */
        public static function fromArray(array $data): ApplicationSettings
        {
            $ApplicationSettingsObject = new ApplicationSettings();

            if(isset($data["id"]))
            {
                $ApplicationSettingsObject->ID = (int)$data["id"];
            }

            if(isset($data["public_id"]))
            {
                $ApplicationSettingsObject->PublicID = $data["public_id"];
            }

            if(isset($data["application_id"]))
            {
                $ApplicationSettingsObject->ApplicationID = (int)$data["application_id"];
            }

            if(isset($data["account_id"]))
            {
                $ApplicationSettingsObject->AccountID = (int)$data["account_id"];
            }

            if(isset($data["data"]))
            {
                foreacH($data["data"] as $name => $datum)
                {
                    switch($datum[0x001])
                    {
                        case ApplicationSettingsDatumType::string:
                            $ApplicationSettingsObject->Data[$name] = DatumString::fromArray($datum);
                            break;

                        case ApplicationSettingsDatumType::boolean:
                            $ApplicationSettingsObject->Data[$name] = DatumBoolean::fromArray($datum);
                            break;

                        case ApplicationSettingsDatumType::integer:
                            $ApplicationSettingsObject->Data[$name] = DatumInteger::fromArray($datum);
                            break;

                        case ApplicationSettingsDatumType::list:
                            $ApplicationSettingsObject->Data[$name] = DatumList::fromArray($datum);
                            break;

                        case ApplicationSettingsDatumType::array:
                            $ApplicationSettingsObject->Data[$name] = DatumArray::fromArray($datum);
                            break;
                    }

                }
            }

            if(isset($data["last_updated_timestamp"]))
            {
                $ApplicationSettingsObject->LastUpdatedTimestamp = $data["last_updated_timestamp"];
            }

            if(isset($data["created_timestamp"]))
            {
                $ApplicationSettingsObject->CreatedTimestamp = $data["created_timestamp"];
            }

            return $ApplicationSettingsObject;
        }
    }