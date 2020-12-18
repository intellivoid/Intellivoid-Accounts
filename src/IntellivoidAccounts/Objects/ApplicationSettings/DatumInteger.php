<?php


    namespace IntellivoidAccounts\Objects\ApplicationSettings;

    use IntellivoidAccounts\Abstracts\ApplicationSettingsDatumType;
    use IntellivoidAccounts\Exceptions\InvalidDatumTypeException;
    use IntellivoidAccounts\Interfaces\iApplicationSettingsDatumType;

    /**
     * Class DatumInteger
     * @package IntellivoidAccounts\Objects\ApplicationSettings
     */
    class DatumInteger implements iApplicationSettingsDatumType
    {
        /**
         * @var int
         */
        private int $value;

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
            $this->value = 0;
        }

        /**
         * @inheritDoc
         */
        public static function getType(): int
        {
            return ApplicationSettingsDatumType::integer;
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
                0x002 => (int)$this->value,
                0x003 => $this->created_timestamp,
                0x004 => $this->last_updated_timestamp
            );
        }

        /**
         * @inheritDoc
         */
        public static function fromArray(array $data): DatumInteger
        {
            if($data[0x001] !== DatumInteger::getType())
            {
                throw new InvalidDatumTypeException("Expected " . DatumInteger::getType() . ", got " . $data[0x001]);
            }

            $Object = new DatumInteger();
            $Object->setValue((int)$data[0x002]);
            $Object->created_timestamp = $data[0x003];
            $Object->last_updated_timestamp = $data[0x004];

            return $Object;
        }

        /**
         * @return int
         */
        public function getValue(): int
        {
            return $this->value;
        }

        /**
         * @param int $value
         */
        public function setValue(int $value): void
        {
            $this->value = $value;
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
        public function getData(): int
        {
            return $this->value;
        }
    }