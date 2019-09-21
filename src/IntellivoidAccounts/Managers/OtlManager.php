<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\OtlStatus;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Utilities\Hashing;
    use msqg\QueryBuilder;

    /**
     * Class OtlManager
     * @package IntellivoidAccounts\Managers
     */
    class OtlManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * OtlManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        /**
         * Generates a one-time login code for the given account ID
         *
         * @param int $account_id
         * @return string
         * @throws DatabaseException
         */
        public function generateLoginCode(int $account_id): string
        {
            $created_timestamp = (int)time();
            $expires_timestamp = $created_timestamp + 300;

            $code = Hashing::OneTimeLoginCode($account_id, $created_timestamp, $expires_timestamp);
            $code = $this->intellivoidAccounts->database->real_escape_string($code);
            $account_id = (int)$account_id;
            $status = (int)OtlStatus::Available;
            $vendor = 'None';

            $Query = QueryBuilder::insert_into('otl_codes', array(
                'code' => $code,
                'vendor' => $vendor,
                'account_id' => $account_id,
                'status' => $status,
                'expires' => $expires_timestamp,
                'created' => $created_timestamp
            ));
            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }
            else
            {
                return $code;
            }
        }
    }