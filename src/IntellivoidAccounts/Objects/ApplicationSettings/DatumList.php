<?php


    namespace IntellivoidAccounts\Objects\ApplicationSettings;

    use IntellivoidAccounts\Abstracts\ApplicationSettingsDatumType;
    use IntellivoidAccounts\Exceptions\InvalidDataTypeForDatumException;
    use IntellivoidAccounts\Exceptions\InvalidDatumTypeException;
    use IntellivoidAccounts\Interfaces\iApplicationSettingsDatumType;
    use IntellivoidAccounts\Utilities\Validate;

    /**
     * Class DatumList
     * @package IntellivoidAccounts\Objects\ApplicationSettings
     */
    class DatumList implements iApplicationSettingsDatumType
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
            return ApplicationSettingsDatumType::list;
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
        public static function fromArray(array $data): DatumList
        {
            if($data[0x001] !== DatumList::getType())
            {
                throw new InvalidDatumTypeException("Expected " . DatumList::getType() . ", got " . $data[0x001]);
            }

            $Object = new DatumList();
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

            foreach($value as $item)
            {
                if(Validate::validateDatumArrayValue($item) == false)
                {
                    throw new InvalidDataTypeForDatumException("Invalid type in position $current_count, " . gettype($item) . " is unsupported for DatumList, use string, integer, double, boolean and float");
                }

                $current_count += 1;
            }

            $results = array();

            foreach($value as $item)
            {
                $results[] = $item;
            }

            $this->value = $results;
        }

        /**
         * Appends a value to the list
         *
         * @param $value
         * @throws InvalidDataTypeForDatumException
         */
        public function appendValue($value): void
        {
            if(Validate::validateDatumArrayValue($value) == false)
            {
                throw new InvalidDataTypeForDatumException(gettype($value) . " is unsupported for DatumList, use string, integer, double, boolean and float");
            }

            $this->value[] = $value;
        }

        /**
         * Removes a value by index
         *
         * @param int $value
         * @noinspection PhpUnused
         */
        public function removeValueByIndex(int $value): void
        {
            unset($this->value[$value]);
            $this->reorder();
        }

        /**
         * Removes a value by value
         *
         * @param $value
         * @noinspection PhpUnused
         */
        public function removeValueByValue($value): void
        {
            if(($key = array_search($value, $this->value)) !== false)
            {
                unset($this->value[$key]);
                $this->reorder();
            }
        }

        /**
         * Updates an existing value or adds it if it didn't exist
         *
         * @param int $index
         * @param $value
         * @throws InvalidDataTypeForDatumException
         */
        public function updateValue(int $index, $value): void
        {
            if(Validate::validateDatumArrayValue($value) == false)
            {
                throw new InvalidDataTypeForDatumException(gettype($value) . " is unsupported for DatumList, use string, integer, double, boolean and float");
            }

            $this->value[$index] = $value;
            $this->reorder();

        }

        /**
         * Reorders the array back into a list to prevent out-of-index errors
         */
        public function reorder(): void
        {
            $results = array();

            foreach($this->value as $item)
            {
                $results[] = $item;
            }

            $this->value = $results;
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
    }