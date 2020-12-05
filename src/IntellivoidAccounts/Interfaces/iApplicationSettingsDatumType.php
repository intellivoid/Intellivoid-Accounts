<?php


    namespace IntellivoidAccounts\Interfaces;

    use IntellivoidAccounts\Exceptions\InvalidDataTypeForDatumException;
    use IntellivoidAccounts\Exceptions\InvalidDatumTypeException;

    /**
     * Interface iApplicationSettingsDatumType
     * @package IntellivoidAccounts\Interfaces
     */
    interface iApplicationSettingsDatumType
    {
        /**
         * Gets the Datum's type
         *
         * @return int
         */
        public static function getType(): int;

        /**
         * Same as the static method but strict
         *
         * @return int
         */
        public function getCurrentType(): int;

        /**
         * Returns the object's array structure
         *
         * @return array
         */
        public function toArray(): array;

        /**
         * Construct the object from an array
         *
         * @throws InvalidDataTypeForDatumException
         * @throws InvalidDatumTypeException
         * @param array $data
         * @return iApplicationSettingsDatumType
         */
        public static function fromArray(array $data): iApplicationSettingsDatumType;

        /**
         * Returns a Unix Timestamp for when this datum type was created
         *
         * @return int
         */
        public function getCreatedTimestamp(): int;

        /**
         * Returns a Unix Timestamp for when this datum type was last updated
         *
         * @return int
         */
        public function getLastUpdatedTimestamp(): int;

        /**
         * Sets the last updated timestamp value
         *
         * @param int $value
         * @return mixed
         */
        public function setLastUpdatedTimestamp(int $value);
    }