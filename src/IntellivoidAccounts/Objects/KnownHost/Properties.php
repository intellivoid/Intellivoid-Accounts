<?php /** @noinspection PhpUnused */


    namespace IntellivoidAccounts\Objects\KnownHost;

    use IntellivoidAccounts\Objects\KnownHost\Properties\AccountCreationLimitation;

    /**
     * Class Properties
     * @package IntellivoidAccounts\Objects\KnownHost
     */
    class Properties
    {
        /**
         * Tracks the amount of accounts created with this known host
         *
         * @var AccountCreationLimitation
         */
        public $AccountCreationLimitation;

        /**
         * Properties constructor.
         */
        public function __construct()
        {
            $this->AccountCreationLimitation = new AccountCreationLimitation();
        }

        /**
         * Returns an array structure of the object
         *
         * @return array
         */
        public function toArray()
        {
            return array(
                "account_creation_limitation" => $this->AccountCreationLimitation->toArray()
            );
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return Properties
         */
        public static function fromArray(array $data): Properties
        {
            $PropertiesObject = new Properties();

            if(isset($data["account_creation_limitation"]))
            {
                $PropertiesObject->AccountCreationLimitation = AccountCreationLimitation::fromArray($data["account_creation_limitation"]);
            }

            return $PropertiesObject;
        }
    }