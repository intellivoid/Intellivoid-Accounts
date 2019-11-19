<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\Exceptions\InvalidVendorException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Utilities\Validate;

    /**
     * Class TransactionManager
     * @package IntellivoidAccounts\Managers
     */
    class TransactionManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * TransactionManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        /**
         * Adds funds to an account
         *
         * @param int $account_id
         * @param string $vendor
         * @param float $funds
         * @return bool
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws InvalidVendorException
         */
        public function addFunds(int $account_id, string $vendor, float $funds): bool
        {
            if(Validate::vendor($vendor) == false)
            {
                throw new InvalidVendorException();
            }

            $Account = $this->intellivoidAccounts->getAccountManager()->getAccount(AccountSearchMethod::byId, $account_id);
        }
    }