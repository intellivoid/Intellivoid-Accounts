<?php

    namespace IntellivoidAccounts\Managers;

    use IntellivoidAccounts\Abstracts\LoginStatus;
    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\HostNotKnownException;
    use IntellivoidAccounts\Exceptions\InvalidIpException;
    use IntellivoidAccounts\Exceptions\InvalidLoginStatusException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Utilities\Hashing;

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
    }