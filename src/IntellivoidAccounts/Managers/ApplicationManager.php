<?php /** @noinspection PhpUnused */


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\ApplicationStatus;
    use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationSearchMethod;
    use IntellivoidAccounts\Exceptions\ApplicationAlreadyExistsException;
    use IntellivoidAccounts\Exceptions\ApplicationNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\InvalidRequestPermissionException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\Application;
    use IntellivoidAccounts\Utilities\Hashing;
    use IntellivoidAccounts\Utilities\Validate;
    use msqg\QueryBuilder;
    use udp\Exceptions\ImageTooSmallException;
    use udp\Exceptions\InvalidImageException;
    use udp\Exceptions\UnsupportedFileTypeException;
    use ZiProto\ZiProto;

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

        /**
         * Registers an existing application to the database
         *
         * @param string $name
         * @param int $authentication_mode
         * @param array $permissions
         * @return Application
         * @throws ApplicationAlreadyExistsException
         * @throws ApplicationNotFoundException
         * @throws DatabaseException
         * @throws InvalidRequestPermissionException
         * @throws InvalidSearchMethodException
         * @throws ImageTooSmallException
         * @throws InvalidImageException
         * @throws UnsupportedFileTypeException
         */
        public function registerApplication(string $name, int $authentication_mode, array $permissions): Application
        {
            $ApplicationExists = false;

            try
            {
                $this->getApplication(ApplicationSearchMethod::byName, $name);
            }
            catch(ApplicationNotFoundException $applicationNotFoundException)
            {
                $ApplicationExists = true;
            }

            try
            {
                $this->getApplication(ApplicationSearchMethod::byNameSafe, str_ireplace(' ', '_', strtolower($name)));
            }
            catch(ApplicationNotFoundException $applicationNotFoundException)
            {
                $ApplicationExists = true;
            }

            if($ApplicationExists)
            {
                throw new ApplicationAlreadyExistsException();
            }

            $CreatedTimestamp = (int)time();
            $PublicApplicationId = Hashing::applicationPublicId($name, $CreatedTimestamp);
            $PublicApplicationId = $this->intellivoidAccounts->database->real_escape_string($PublicApplicationId);
            $SecretKey = Hashing::applicationSecretKey($PublicApplicationId, $CreatedTimestamp);
            $SecretKey = $this->intellivoidAccounts->database->real_escape_string($SecretKey);
            $Name = $this->intellivoidAccounts->database->real_escape_string($name);
            $NameSafe = str_ireplace(' ', '_', strtolower($name));
            $NameSafe = $this->intellivoidAccounts->database->real_escape_string($NameSafe);
            $Permissions = [];
            foreach($permissions as $permission)
            {
                if(Validate::verify_permission($permission) == false)
                {
                    throw new InvalidRequestPermissionException();
                }

                $Permissions[] = $permission;
            }
            $Permissions = $this->intellivoidAccounts->database->real_escape_string(ZiProto::encode($Permissions));
            $Status = (int)ApplicationStatus::Active;
            $AuthenticationMode = (int)$authentication_mode;
            $AccountID = 0;
            $LastUpdatedTimestamp = $CreatedTimestamp;

            $Query = QueryBuilder::insert_into('applications', array(
                'public_app_id' => $PublicApplicationId,
                'secret_key' => $SecretKey,
                'name' => $Name,
                'name_safe' => $NameSafe,
                'permissions' => $Permissions,
                'status' => $Status,
                'authentication_mode' => $AuthenticationMode,
                'account_id' => $AccountID,
                'creation_timestamp' => $CreatedTimestamp,
                'last_updated_timestamp' => $LastUpdatedTimestamp
            ));

            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }
            else
            {
                $this->intellivoidAccounts->getAppUdp()->getProfilePictureManager()->generate_avatar($PublicApplicationId);
                return $this->getApplication(ApplicationSearchMethod::byApplicationId, $PublicApplicationId);
            }
        }

        /**
         * Retrieves an existing application from the database
         *
         * @param string $search_method
         * @param string $value
         * @return Application
         * @throws ApplicationNotFoundException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         */
        public function getApplication(string $search_method, string $value): Application
        {
            switch($search_method)
            {
                case ApplicationSearchMethod::byId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = (int)$value;
                    break;
                case ApplicationSearchMethod::byApplicationId:
                case ApplicationSearchMethod::byName:
                case ApplicationSearchMethod::byNameSafe:
                case ApplicationSearchMethod::bySecretKey:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = $this->intellivoidAccounts->database->real_escape_string($value);
                    break;
                default:
                    throw new InvalidSearchMethodException();
            }

            $Query = QueryBuilder::select('applications', [
                'id',
                'public_app_id',
                'secret_key',
                'name',
                'name_safe',
                'permissions',
                'status',
                'authentication_mode',
                'account_id',
                'creation_timestamp',
                'last_updated_timestamp'
            ], $search_method, $value);

            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }
            else
            {
                if($QueryResults->num_rows !== 1)
                {
                    throw new ApplicationNotFoundException();
                }

                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);
                $Row['permissions'] = ZiProto::decode($Row['permissions']);
                return Application::fromArray($Row);
            }
        }

        /**
         * Updates an existing Application in the database
         *
         * @param Application $application
         * @return bool
         * @throws ApplicationNotFoundException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         */
        public function updateApplication(Application $application)
        {
            $this->getApplication(ApplicationSearchMethod::byId, $application->ID);

            $id = (int)$application->ID;
            $secret_key = $this->intellivoidAccounts->database->real_escape_string($application->SecretKey);
            $permissions = $this->intellivoidAccounts->database->real_escape_string(ZiProto::encode($application->Permissions));
            $status = (int)$application->Status;
            $authentication_mode = (int)$application->AuthenticationMode;
            $creation_timestamp = (int)$application->CreationTimestamp;
            $last_updated_timestamp = (int)$application->LastUpdatedTimestamp;

            $Query = QueryBuilder::update('applications', array(
                'secret_key' => $secret_key,
                'permissions' => $permissions,
                'status' => $status,
                'authentication_mode' => $authentication_mode,
                'creation_timestamp' => $creation_timestamp,
                'last_updated_timestamp' => $last_updated_timestamp
            ), 'id', $id);
            $QueryResults = $this->intellivoidAccounts->database->query($Query);

            if($QueryResults == true)
            {
                return true;
            }
            else
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }
        }
    }