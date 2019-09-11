<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\ApplicationStatus;
    use IntellivoidAccounts\Abstracts\AuthenticationMode;
    use IntellivoidAccounts\Exceptions\InvalidRequestPermissionException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\Application;
    use IntellivoidAccounts\Utilities\Hashing;
    use IntellivoidAccounts\Utilities\Validate;

    /**
     * Class ApplicationManager
     * @package IntellivoidAccounts\Managers
     */
    class ApplicationManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * ApplicationManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        public function register_application(string $name, int $authentication_mode, array $permissions): Application
        {
            $CreatedTimestamp = (int)time();
            $PublicApplicationId = Hashing::applicationPublicId($name, $CreatedTimestamp);
            $PublicApplicationId = $this->intellivoidAccounts->database->real_escape_string($PublicApplicationId);
            $SecretKey = Hashing::applicationSecretKey($PublicApplicationId, $CreatedTimestamp);
            $SecretKey = $this->intellivoidAccounts->database->real_escape_string($SecretKey);
            $Name = $this->intellivoidAccounts->database->real_escape_string($name);
            $NameSafe = str_ireplace(' ', '_', strtolower($name));
            $Permissions = [];
            foreach($permissions as $permission)
            {
                if(Validate::verify_permission($permission) == false)
                {
                    throw new InvalidRequestPermissionException();
                }

                $Permissions[] = $permission;
            }
            $Status = ApplicationStatus::Active;
            $AuthenticationMode = AuthenticationMode::
        }
    }