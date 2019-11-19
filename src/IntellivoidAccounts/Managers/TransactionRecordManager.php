<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Utilities\Hashing;
    use msqg\QueryBuilder;

    /**
     * Class TransactionRecordManager
     * @package IntellivoidAccounts\Managers
     */
    class TransactionRecordManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * TransactionRecordManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        /**
         * Logs the transaction
         *
         * @param int $account_id
         * @param string $vendor
         * @param float $amount
         * @return bool
         * @throws DatabaseException
         */
        public function logTransaction(int $account_id, string $vendor, float $amount): bool
        {
            $timestamp = (int)time();
            $public_id = Hashing::TransactionRecordPublicID($account_id, $vendor, $timestamp);
            $account_id = (int)$account_id;
            $vendor = $this->intellivoidAccounts->database->real_escape_string($vendor);;
            $amount = (float)$amount;

            $Query = QueryBuilder::insert_into('transaction_records', array(
                'public_id' => $public_id,
                'account_id' => $account_id,
                'vendor' => $vendor,
                'amount' => $amount,
                'timestamp' => $timestamp
            ));
            $QueryResults = $this->intellivoidAccounts->database->query($Query);

            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }

            return true;
        }
    }