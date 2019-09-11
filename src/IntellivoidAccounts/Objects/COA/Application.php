<?php


    namespace IntellivoidAccounts\Objects\COA;

    use IntellivoidAccounts\Abstracts\AccountRequestPermissions;
    use IntellivoidAccounts\Exceptions\InvalidRequestPermissionException;
    use IntellivoidAccounts\Utilities\Validate;

    /**
     * Class Application
     * @package IntellivoidAccounts\Objects\COA
     */
    class Application
    {
        /**
         * Unique Internal Databsae ID
         *
         * @var int
         */
        public $ID;

        /**
         * Public Application ID
         *
         * @var string
         */
        public $PublicAppId;

        /**
         * Secret Key for issuing access requests
         *
         * @var string
         */
        public $SecretKey;

        /**
         * The name of the application
         *
         * @var string
         */
        public $Name;

        /**
         * Safe name of the application
         *
         * @var string
         */
        public $NameSafe;

        /**
         * Permissions required by the Application
         *
         * @var array
         */
        public $Permissions;

        /**
         * The current status of the application
         *
         * @var int
         */
        public $Status;

        /**
         * The authentication mode that this application uses
         *
         * @var int
         */
        public $AuthenticationMode;

        /**
         * Account ID that owns this application
         *
         * @var int
         */
        public $AccountID;

        /**
         * The Unix Timestamp of when this Application was registered
         *
         * @var int
         */
        public $CreationTimestamp;

        /**
         * The Unix Timestamp of when this application was last updated
         *
         * @var int
         */
        public $LastUpdatedTimestamp;

        /**
         * Application constructor.
         */
        public function __construct()
        {
            $this->Permissions = [];
        }

        /**
         * Applies a permission to the application
         *
         * @param string|AccountRequestPermissions $permission
         * @return bool
         * @throws InvalidRequestPermissionException
         */
        public function apply_permission(string $permission): bool
        {
            if(isset($this->Permissions[$permission]))
            {
                return false;
            }

            if(Validate::verify_permission($permission) == false)
            {
                throw new InvalidRequestPermissionException();
            }

            $this->Permissions[] = $permission;
            return true;
        }

        /**
         * Revokes an existing permission
         *
         * @param string $permission
         * @return bool
         */
        public function revoke_permission(string $permission): bool
        {
            if(isset($this->Permissions[$permission]) == false)
            {
                return false;
            }

            unset($this->Permissions[$permission]);
            return true;
        }

        /**
         * Returns an array that represents this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'id' => $this->ID,
                'public_app_id' => $this->PublicAppId,
                'secret_key' => $this->SecretKey,
                'name' => $this->Name,
                'name_safe' => str_ireplace(' ', '_', strtolower($this->Name)),
                'permissions' => $this->Permissions,
                'status' => $this->Status,
                'authentication_mode' => $this->AuthenticationMode,
                'account_id' => $this->AccountID,
                'creation_timestamp' => $this->CreationTimestamp,
                'last_updated_timestamp' => $this->LastUpdatedTimestamp
            );
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @return Application
         */
        public static function fromArray(array $data): Application
        {
            $ApplicationObject = new Application();

            if(isset($data['id']))
            {
                $ApplicationObject->ID = (int)$data['id'];
            }

            if(isset($data['public_app_id']))
            {
                $ApplicationObject->PublicAppId = $data['public_app_id'];
            }

            if(isset($data['secret_key']))
            {
                $ApplicationObject->SecretKey = $data['secret_key'];
            }

            if(isset($data['name']))
            {
                $ApplicationObject->Name = $data['name'];
                $ApplicationObject->NameSafe = str_ireplace(' ', '_', strtolower($data['name']));
            }

            if(isset($data['permissions']))
            {
                $ApplicationObject->Permissions = $data['permissions'];
            }

            if(isset($data['status']))
            {
                $ApplicationObject->Status = (int)$data['status'];
            }

            if(isset($data['authentication_mode']))
            {
                $ApplicationObject->AuthenticationMode = (int)$data['authentication_mode'];
            }

            if(isset($data['account_id']))
            {
                $ApplicationObject->AccountID = (int)$data['account_id'];
            }

            return $ApplicationObject;
        }
    }