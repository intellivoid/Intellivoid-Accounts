<?php


    namespace IntellivoidAccounts\Objects\ApplicationSettings;

    use IntellivoidAccounts\Abstracts\ApplicationSettingsDatumType;

    /**
     * Class Datum
     * @package IntellivoidAccounts\Objects\ApplicationSettings
     */
    class Datum
    {
        /**
         * The type of data the object contains
         *
         * @var string|ApplicationSettingsDatumType
         */
        public $Type;

        /**
         * The data object storing multiple types of data types, use builtin methods to interact
         * with this property
         *
         * @var
         */
        public $DataObject;
    }