<?php


    namespace IntellivoidAccounts\Objects\ApplicationSettings;


    use IntellivoidAccounts\Abstracts\ApplicationSettingsDatumType;
    use IntellivoidAccounts\Exceptions\InvalidDataTypeForDatumException;
    use IntellivoidAccounts\Exceptions\InvalidDatumTypeException;
    use IntellivoidAccounts\Interfaces\iApplicationSettingsDatumType;
    use IntellivoidAccounts\Utilities\Validate;

    /**
     * Class DatumArray
     * @package IntellivoidAccounts\Objects\ApplicationSettings
     */
    class DatumArray implements iApplicationSettingsDatumType
    {
        /**
         * @var array
         */
        private array $value;

        /**
         * The Unix Timestamp for when this datum was created
         *
         * @var int
         */
        private int $created_timestamp;

        /**
         * The Unix Timestamp for when this datum was last updated
         *
         * @var int
         */
        private int $last_updated_timestamp;

        /**
         * DatumArray constructor.
         */
        public function __construct()
        {
            $this->created_timestamp = (int)time();
            $this->last_updated_timestamp = (int)time();
            $this->value = [];
        }

        /**
         * @inheritDoc
         */
        public static function getType(): int
        {
            return ApplicationSettingsDatumType::array;
        }

        /**
         * @inheritDoc
         */
        public function getCurrentType(): int
        {
            return self::getType();
        }

        /**
         * @inheritDoc
         */
        public function toArray(): array
        {
            return array(
                0x001 => $this->getType(),
                0x002 => $this->value,
                0x003 => $this->created_timestamp,
                0x004 => $this->last_updated_timestamp
            );
        }

        /**
         * @inheritDoc
         */
        public static function fromArray(array $data): DatumArray
        {
            if($data[0x001] !== DatumArray::getType())
            {
                throw new InvalidDatumTypeException("Expected " . DatumArray::getType() . ", got " . $data[0x001]);
            }

            $Object = new DatumArray();
            $Object->setValue($data[0x002]);
            $Object->created_timestamp = $data[0x003];
            $Object->last_updated_timestamp = $data[0x004];

            return $Object;
        }

        /**
         * @return array
         */
        public function getValue(): array
        {
            return $this->value;
        }

        /**
         * @param array $value
         * @throws InvalidDataTypeForDatumException
         */
        public function setValue(array $value): void
        {
            $current_count = 0;

            foreach($value as $key => $value_i)
            {
                if(Validate::validateDatumArrayValue($value_i) == false)
                {
                    throw new InvalidDataTypeForDatumException("Invalid type in position $current_count, " . gettype($value_i) . " value is unsupported for DatumArray, use string, integer, double, boolean or float");
                }

                if(gettype($key) !== "string")
                {
                    throw new InvalidDataTypeForDatumException("Invalid type in position $current_count, " . gettype($key) . " key is unsupported for DatumArray, use string");
                }

                $current_count += 1;
            }

            $results = array();

            foreach($value as $key => $value_i)
                $results[$key] = $value_i;

            $this->last_updated_timestamp = (int)time();
            $this->value = $results;
        }

        /**
         * Appends a value to the list
         *
         * @param $key
         * @param $value
         * @throws InvalidDataTypeForDatumException
         */
        public function add($key, $value): void
        {
            if(Validate::validateDatumArrayValue($value) == false)
            {
                throw new InvalidDataTypeForDatumException(gettype($value) . " is unsupported for DatumArray, use string, integer, double, boolean and float");
            }


            if(gettype($key) !== "string")
            {
                throw new InvalidDataTypeForDatumException(gettype($key) . " key is unsupported for DatumArray, use string");
            }

            $this->last_updated_timestamp = (int)time();
            $this->value[$key] = $value;
        }

        /**
         * Removes a value by key
         *
         * @param string $value
         * @noinspection PhpUnused
         */
        public function removeValueByKey(string $value): void
        {
            $this->last_updated_timestamp = (int)time();
            unset($this->value[$value]);
        }

        /**
         * @inheritDoc
         */
        public function getCreatedTimestamp(): int
        {
            return $this->created_timestamp;
        }

        /**
         * @inheritDoc
         */
        public function getLastUpdatedTimestamp(): int
        {
            return $this->last_updated_timestamp;
        }

        /**
         * @inheritDoc
         */
        public function setLastUpdatedTimestamp(int $value)
        {
            $this->last_updated_timestamp = $value;
        }

        /**
         * @inheritDoc
         */
        public function getData(): array
        {
            return $this->value;
        }
    }