<?php

    namespace IntellivoidAccounts\Managers;

    use IntellivoidAccounts\Abstracts\LoginStatus;
    use IntellivoidAccounts\Abstracts\SearchMethods\LoginRecordSearchMethod;
    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\HostNotKnownException;
    use IntellivoidAccounts\Exceptions\InvalidIpException;
    use IntellivoidAccounts\Exceptions\InvalidLoginStatusException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\Exceptions\LoginRecordNotFoundException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\LoginRecord;
    use IntellivoidAccounts\Utilities\Hashing;
    use msqg\QueryBuilder;

    /**
     * Class LoginRecordManager
     * @package IntellivoidAccounts\Managers
     */
    class LoginRecordManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * LoginRecordManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }


        /**
         * @param int $account_id
         * @param string $ip_address
         * @param int $status
         * @param string $origin
         * @return bool
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws InvalidIpException
         * @throws InvalidLoginStatusException
         * @throws InvalidSearchMethodException
         * @throws HostNotKnownException
         */
        public function createLoginRecord(int $account_id, string $ip_address, int $status, string $origin): bool
        {
            if($this->intellivoidAccounts->getAccountManager()->IdExists($account_id) == false)
            {
                throw new AccountNotFoundException();
            }

            $KnownHost = $this->intellivoidAccounts->getKnownHostsManager()->syncHost($ip_address, $account_id);

            switch($status)
            {
                case LoginStatus::Unknown:
                    break;

                case LoginStatus::Successful:
                    break;

                case LoginStatus::VerificationFailed:
                    break;

                case LoginStatus::UntrustedIpBlocked:
                    break;

                case LoginStatus::BlockedSuspiciousActivities:
                    break;

                default:
                    throw new InvalidLoginStatusException();
            }

            $account_id = $this->intellivoidAccounts->database->real_escape_string($account_id);
            $host_id = $this->intellivoidAccounts->database->real_escape_string($KnownHost->ID);
            $login_status = (int)$status;
            $origin = $this->intellivoidAccounts->database->real_escape_string($origin);
            $timestamp = (int)time();
            $public_id = Hashing::loginPublicID($account_id, $timestamp, $login_status, $origin, $ip_address);
            $public_id = $this->intellivoidAccounts->database->real_escape_string($public_id);

            $Query = "INSERT INTO `users_logins` (public_id, origin, host_id, account_id, status, timestamp) VALUES ('$public_id', '$origin', $host_id, $account_id, $status, $timestamp)";
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

        /**
         * Gets an existing Login Record from the database
         *
         * @param string $search_method
         * @param string $value
         * @return LoginRecord
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws LoginRecordNotFoundException
         */
        public function getLoginRecord(string $search_method, string $value): LoginRecord
        {
            switch($search_method)
            {
                case LoginRecordSearchMethod::byPublicId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = $this->intellivoidAccounts->database->real_escape_string($value);
                    break;

                case LoginRecordSearchMethod::byId:
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                default:
                    throw new InvalidSearchMethodException();
            }

            $Query = QueryBuilder::select("login_records", [
                'id',
                'public_id',
                'account_id',
                'ip_address',
                'origin',
                'time',
                'status'
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
                    throw new LoginRecordNotFoundException();
                }

                return LoginRecord::fromArray($QueryResults->fetch_array(MYSQLI_ASSOC));
            }
        }
    }