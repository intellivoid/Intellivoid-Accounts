<?php


    namespace IntellivoidAccounts\Objects\ApplicationSettings;

    use IntellivoidAccounts\Abstracts\ApplicationSettingsDatumType;
    use IntellivoidAccounts\Exceptions\InvalidDatumTypeException;
    use IntellivoidAccounts\Interfaces\iApplicationSettingsDatumType;

    /**
     * Class DatumString
     * @package IntellivoidAccounts\Objects\ApplicationSettings
     */
    class DatumString implements iApplicationSettingsDatumType
    {
        /**
         * @var string
         */
        private string $value;

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
            $this->value = (string)null;
        }

        /**
         * @inheritDoc
         */
        public static function getType(): int
        {
            return ApplicationSettingsDatumType::string;
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
                0x002 => (string)$this->value,
                0x003 => $this->created_timestamp,
                0x004 => $this->last_updated_timestamp
            );
        }

        /**
         * @inheritDoc
         */
        public static function fromArray(array $data): DatumString
        {
            if($data[0x001] !== DatumString::getType())
            {
                throw new InvalidDatumTypeException("Expected " . DatumString::getType() . ", got " . $data[0x001]);
            }

            $Object = new DatumString();
            $Object->setValue((string)$data[0x002]);
            $Object->created_timestamp = $data[0x003];
            $Object->last_updated_timestamp = $data[0x004];

            return $Object;
        }

        /**
         * Sets the string value to the datum
         *
         * @param string $value
         */
        public function setValue(string $value)
        {
            $this->value = $value;
        }

        /**
         * @return string
         */
        public function getValue(): string
        {
            return $this->value;
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
        public function getData(): string
        {
            return $this->value;
        }
    }