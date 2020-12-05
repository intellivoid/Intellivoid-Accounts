<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationSettingsSearchMethod;
    use IntellivoidAccounts\Exceptions\ApplicationSettingsRecordAlreadyExistsException;
    use IntellivoidAccounts\Exceptions\ApplicationSettingsRecordNotFoundException;
    use IntellivoidAccounts\Exceptions\ApplicationSettingsSizeExceededException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\InvalidDataTypeForDatumException;
    use IntellivoidAccounts\Exceptions\InvalidDatumTypeException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\ApplicationSettings;
    use IntellivoidAccounts\Utilities\Hashing;
    use msqg\QueryBuilder;
    use ZiProto\ZiProto;

    /**
     * Class ApplicationSettingsManager
     * @package IntellivoidAccounts\Managers
     */
    class ApplicationSettingsManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private IntellivoidAccounts $intellivoidAccounts;

        /**
         * ApplicationSettingsManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        /**
         * Creates a new Application Settings record if one does not exist
         *
         * @param int $application_id
         * @param int $account_id
         * @return bool
         * @throws ApplicationSettingsRecordAlreadyExistsException
         * @throws DatabaseException
         * @throws InvalidDataTypeForDatumException
         * @throws InvalidDatumTypeException
         * @throws InvalidSearchMethodException
         */
        public function createRecord(int $application_id, int $account_id): bool
        {
            try
            {
                $this->getRecordByApplicationAndAccount($application_id, $account_id);
                throw new ApplicationSettingsRecordAlreadyExistsException();
            }
            catch(ApplicationSettingsRecordNotFoundException $exception)
            {
                unset($exception);
            }

            $application_id = (int)$application_id;
            $account_id = (int)$account_id;
            $public_id = Hashing::ApplicationSettingsPublicId($application_id, $account_id);
            $last_updated_timestamp = (int)time();
            $created_timestamp = $last_updated_timestamp;
            $data = array();

            $Query = QueryBuilder::insert_into("application_settings", array(
                "public_id" => $this->intellivoidAccounts->database->real_escape_string($public_id),
                "account_id" => $account_id,
                "application_id" => $application_id,
                "created_timestamp" => $created_timestamp,
                "last_updated_timestamp" => $created_timestamp,
                "data" => $this->intellivoidAccounts->database->real_escape_string(ZiProto::encode($data))
            ));

            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }

            return True;
        }

        /**
         * Settings
         *
         * @param string $search_method
         * @param string $value
         * @return ApplicationSettings
         * @throws ApplicationSettingsRecordNotFoundException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws InvalidDataTypeForDatumException
         * @throws InvalidDatumTypeException
         */
        public function getRecord(string $search_method, string $value): ApplicationSettings
        {
            switch($search_method)
            {
                case ApplicationSettingsSearchMethod::byId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                case ApplicationSettingsSearchMethod::byPublicId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = $this->intellivoidAccounts->database->real_escape_string($value);
                    break;

                default:
                    throw new InvalidSearchMethodException();
            }

            $Query = QueryBuilder::select("application_settings", [
                "id",
                "public_id",
                "account_id",
                "application_id",
                "created_timestamp",
                "last_updated_timestamp",
                "data"
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
                    throw new ApplicationSettingsRecordNotFoundException();
                }

                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);
                $Row['data'] = ZiProto::decode($Row['data']);

                return ApplicationSettings::fromArray($Row);
            }
        }

        /**
         * Returns the ApplicationSettings record or creates one if it doesn't exist
         *
         * @param int $application_id
         * @param int $account_id
         * @return ApplicationSettings
         * @throws ApplicationSettingsRecordAlreadyExistsException
         * @throws ApplicationSettingsRecordNotFoundException
         * @throws DatabaseException
         * @throws InvalidDataTypeForDatumException
         * @throws InvalidDatumTypeException
         * @throws InvalidSearchMethodException
         */
        public function smartGetRecord(int $application_id, int $account_id): ApplicationSettings
        {
            $public_id = Hashing::ApplicationSettingsPublicId($application_id, $account_id);

            try
            {
                return $this->getRecord(ApplicationSettingsSearchMethod::byPublicId, $public_id);
            }
            catch(ApplicationSettingsRecordNotFoundException $exception)
            {
                $this->createRecord($application_id, $account_id);
            }

            return $this->getRecord(ApplicationSettingsSearchMethod::byPublicId, $public_id);
        }

        /**
         * Searches a record by the Application ID and Account ID combination
         *
         * @param int $application_id
         * @param int $account_id
         * @return ApplicationSettings
         * @throws ApplicationSettingsRecordNotFoundException
         * @throws DatabaseException
         * @throws InvalidDataTypeForDatumException
         * @throws InvalidDatumTypeException
         * @throws InvalidSearchMethodException
         * @noinspection PhpUnused
         */
        public function getRecordByApplicationAndAccount(int $application_id, int $account_id): ApplicationSettings
        {
            $public_id = Hashing::ApplicationSettingsPublicId($application_id, $account_id);
            return $this->getRecord(ApplicationSettingsSearchMethod::byPublicId, $public_id);
        }

        /**
         * Updates an existing Application Settings record in the database
         *
         * @param ApplicationSettings $applicationSettings
         * @return bool
         * @throws ApplicationSettingsSizeExceededException
         * @throws DatabaseException
         */
        public function updateRecord(ApplicationSettings $applicationSettings)
        {
            if($applicationSettings->calculateSize() > 16777216)
            {
                throw new ApplicationSettingsSizeExceededException();
            }

            $results = $applicationSettings->toArray();

            $Query = QueryBuilder::update("application_settings", array(
                "data" => $this->intellivoidAccounts->database->real_escape_string(ZiProto::encode($results["data"])),
                "last_updated_timestamp" => (int)time()
            ), "id", $applicationSettings->ID);
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