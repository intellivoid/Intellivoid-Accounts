<?php

    namespace IntellivoidAccounts\Objects\Account;

    use IntellivoidAccounts\Objects\Account\Configuration\OpenBlu;

    /**
     * Class Configuration
     * @package IntellivoidAccounts\Objects\Account
     */
    class Configuration
    {
        /**
         * @var OpenBlu
         */
        public $OpenBlu;

        /**
         * Configuration constructor.
         */
        public function __construct()
        {
            $this->OpenBlu = new OpenBlu();
        }

        /**
         * Converts object to array
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'openblu' => $this->OpenBlu->toArray()
            );
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @return Configuration
         */
        public static function fromArray(array $data): Configuration
        {
            $ConfigurationObject = new Configuration();

            if(isset($data['openblu']))
            {
                $ConfigurationObject->OpenBlu = OpenBlu::fromArray($data['openblu']);
            }

            return $ConfigurationObject;
        }
    }