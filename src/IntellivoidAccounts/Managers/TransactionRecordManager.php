<?php


    namespace IntellivoidAccounts\Managers;

    use IntellivoidAccounts\Abstracts\OperatorType;
    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
    use IntellivoidAccounts\Abstracts\TransactionType;
    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\InsufficientFundsException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\Exceptions\InvalidTransactionTypeException;
    use IntellivoidAccounts\Exceptions\InvalidVendorException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\TransactionRecord;
    use IntellivoidAccounts\Utilities\Hashing;
    use IntellivoidAccounts\Utilities\Validate;

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
         * @param int $account_id
         * @param float $amount
         * @param string $vendor
         * @param int $type
         * @return TransactionRecord
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws InsufficientFundsException
         * @throws InvalidVendorException
         * @throws InvalidTransactionTypeException
         */
        public function createTransaction(int $account_id, float $amount, string $vendor, int $type): TransactionRecord
        {
            if(Validate::vendor($vendor) == false)
            {
                throw new InvalidVendorException();
            }

            switch($type)
            {
                case TransactionType::Payment:
                    $type = (int)$type;
                    break;

                case TransactionType::SubscriptionPayment:
                    $type = (int)$type;
                    break;

                case TransactionType::Deposit:
                    $type = (int)$type;
                    break;

                case TransactionType::Withdraw:
                    $type = (int)$type;
                    break;

                case TransactionType::Refund:
                    $type = (int)$type;
                    break;

                default:
                    throw new InvalidTransactionTypeException();
            }

            $Account = $this->intellivoidAccounts->getAccountManager()->getAccount(AccountSearchMethod::byId, $account_id);

            $OperatorType = OperatorType::None;

            if($amount > 0)
            {
                $OperatorType = OperatorType::Deposit;
                $Account->Configuration->Balance += abs($amount);
            }
            elseif($amount < 0)
            {
                $OperatorType = OperatorType::Withdraw;
                $Calculation = $Account->Configuration->Balance - abs($amount);

                if($Calculation < 0)
                {
                    throw new InsufficientFundsException();
                }

                $Account->Configuration->Balance -= abs($amount);
            }

            $Timestamp = (int)time();
            $PublicID = Hashing::transactionRecordPublicID(
                $account_id, $Timestamp, abs($amount), $vendor, $OperatorType
            );
            $PublicID = $this->intellivoidAccounts->database->real_escape_string($PublicID);
            $AccountID = (int)$account_id;
            $Amount = abs($amount);
            $OperatorType = (int)$OperatorType;
            $Type = (int)$type;
            $Vendor = $this->intellivoidAccounts->database->real_escape_string($vendor);

            $Query = "INSERT INTO `transaction_records` (public_id, account_id, amount, operator_type, type, vendor, timestamp) VALUES ('$PublicID', $AccountID, $Amount, $OperatorType, $Type, '$Vendor', $Timestamp)";
            $QueryResults = $this->intellivoidAccounts->database->query($Query);

            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }

            $this->intellivoidAccounts->getAccountManager()->updateAccount($Account);
            // return new transaction record
        }
    }