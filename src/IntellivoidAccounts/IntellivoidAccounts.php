<?php

    namespace IntellivoidAccounts;
    use IntellivoidAccounts\Exceptions\ConfigurationNotFoundException;

    /**
     * Class IntellivoidAccounts
     * @package IntellivoidAccounts
     */
    class IntellivoidAccounts
    {
        /**
         * @var array|bool
         */
        public $configuration;

        /**
         * @var \mysqli
         */
        public $database;

        /**
         * IntellivoidAccounts constructor.
         * @throws ConfigurationNotFoundException
         */
        public function __construct()
        {
            if(file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'configuration.ini') == false)
            {
                throw new ConfigurationNotFoundException();
            }

            $this->configuration = parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'configuration.ini');

            $this->database = new \mysqli(
                $this->configuration['DatabaseHost'],
                $this->configuration['DatabaseUsername'],
                $this->configuration['DatabasePassword'],
                $this->configuration['DatabaseName'],
                $this->configuration['DatabasePort']
            );
        }
    }