<?php


    namespace IntellivoidAccounts\Objects\ApplicationSettings;


    use IntellivoidAccounts\Abstracts\ApplicationSettingsDatumType;
    use IntellivoidAccounts\Exceptions\InvalidDatumTypeException;
    use IntellivoidAccounts\Interfaces\iApplicationSettingsDatumType;

    /**
     * Class DatumBoolean
     * @package IntellivoidAccounts\Objects\ApplicationSettings
     */
    class DatumBoolean implements iApplicationSettingsDatumType
    {

        /**
         * @var bool
         */
        private bool $value;

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
            $this->value = false;
        }

        /**
         * @inheritDoc
         */
        public static function getType(): int
        {
            return ApplicationSettingsDatumType::boolean;
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
                0x002 => (bool)$this->value,
                0x003 => $this->created_timestamp,
                0x004 => $this->last_updated_timestamp
            );
        }

        /**
         * @inheritDoc
         */
        public static function fromArray(array $data): DatumBoolean
        {
            if($data[0x001] !== DatumBoolean::getType())
            {
                throw new InvalidDatumTypeException("Expected " . DatumBoolean::getType() . ", got " . $data[0x001]);
            }

            $Object = new DatumBoolean();
            $Object->setValue((bool)$data[0x002]);
            $Object->created_timestamp = $data[0x003];
            $Object->last_updated_timestamp = $data[0x004];

            return $Object;
        }

        /**
         * @param bool $value
         */
        public function setValue(bool $value)
        {
            $this->value = $value;
        }

        /**
         * @return bool
         */
        public function getValue(): bool
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
    }