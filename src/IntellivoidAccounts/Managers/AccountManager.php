<?php

    namespace IntellivoidAccounts\Managers;

    use Exception;
    use IntellivoidAccounts\Abstracts\AccountStatus;
    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
    use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationSearchMethod;
    use IntellivoidAccounts\Exceptions\AccountLimitedException;
    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\AccountSuspendedException;
    use IntellivoidAccounts\Exceptions\ApplicationNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\EmailAlreadyExistsException;
    use IntellivoidAccounts\Exceptions\GovernmentBackedAttackModeEnabledException;
    use IntellivoidAccounts\Exceptions\IncorrectLoginDetailsException;
    use IntellivoidAccounts\Exceptions\InsufficientFundsException;
    use IntellivoidAccounts\Exceptions\InvalidAccountStatusException;
    use IntellivoidAccounts\Exceptions\InvalidEmailException;
    use IntellivoidAccounts\Exceptions\InvalidFundsValueException;
    use IntellivoidAccounts\Exceptions\InvalidPasswordException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\Exceptions\InvalidUsernameException;
    use IntellivoidAccounts\Exceptions\InvalidVendorException;
    use IntellivoidAccounts\Exceptions\UsernameAlreadyExistsException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\Account;
    use IntellivoidAccounts\Utilities\Hashing;
    use IntellivoidAccounts\Utilities\Validate;
    use IntellivoidSubscriptionManager\Abstracts\SearchMethods\SubscriptionPlanSearchMethod;
    use IntellivoidSubscriptionManager\Abstracts\SearchMethods\SubscriptionPromotionSearchMethod;
    use IntellivoidSubscriptionManager\Abstracts\SearchMethods\SubscriptionSearchMethod;
    use IntellivoidSubscriptionManager\Exceptions\InvalidSubscriptionPromotionNameException;
    use IntellivoidSubscriptionManager\Exceptions\SubscriptionNotFoundException;
    use IntellivoidSubscriptionManager\Exceptions\SubscriptionPlanNotFoundException;
    use IntellivoidSubscriptionManager\Exceptions\SubscriptionPromotionNotFoundException;
    use IntellivoidSubscriptionManager\IntellivoidSubscriptionManager;
    use IntellivoidSubscriptionManager\Objects\Subscription;
    use msqg\QueryBuilder;
    use TelegramClientManager\Abstracts\SearchMethods\TelegramClientSearchMethod;
    use TelegramClientManager\Exceptions\InvalidSearchMethod;
    use TelegramClientManager\Exceptions\TelegramClientNotFoundException;
    use ZiProto\ZiProto;

    /**
     * Class AccountManager
     * @package IntellivoidAccounts\Managers
     */
    class AccountManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * AccountManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        /**
         * Registers a new Account into the Database
         *
         * @param string $username
         * @param string $email
         * @param string $password
         * @return Account
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws EmailAlreadyExistsException
         * @throws InvalidEmailException
         * @throws InvalidPasswordException
         * @throws InvalidSearchMethodException
         * @throws InvalidUsernameException
         * @throws UsernameAlreadyExistsException
         * @noinspection PhpUnused
         */
        public function registerAccount(string $username, string $email, string $password): Account
        {
            if(Validate::username($username) == false)
            {
                throw new InvalidUsernameException();
            }

            if(Validate::email($email) == false)
            {
                throw new InvalidEmailException();
            }

            if(Validate::password($password) == false)
            {
                throw new InvalidPasswordException();
            }

            if($this->usernameExists($username) == true)
            {
                throw new UsernameAlreadyExistsException();
            }

            if($this->emailExists($email) == true)
            {
                throw new EmailAlreadyExistsException();
            }

            $public_id = Hashing::publicID($username, $password, $email);
            $username = $this->intellivoidAccounts->database->real_escape_string($username);
            $email = $this->intellivoidAccounts->database->real_escape_string($email);
            $password = $this->intellivoidAccounts->database->real_escape_string(Hashing::password($password));
            $status = (int)AccountStatus::Active;
            $personal_information = new Account\PersonalInformation();
            $personal_information = $this->intellivoidAccounts->database->real_escape_string(ZiProto::encode($personal_information->toArray()));
            $configuration = new Account\Configuration();
            $configuration = $this->intellivoidAccounts->database->real_escape_string(ZiProto::encode($configuration->toArray()));
            $last_login_id = (int)0;
            $creation_date = (int)time();

            $Query = QueryBuilder::insert_into('users',
                array(
                    'public_id' => $public_id,
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'status' => $status,
                    'personal_information' => $personal_information,
                    'configuration' => $configuration,
                    'last_login_id' => $last_login_id,
                    'creation_date' => $creation_date
                )
            );
            $QueryResults = $this->intellivoidAccounts->database->query($Query);

            if($QueryResults == true)
            {
                return $this->getAccount(AccountSearchMethod::byPublicID, $public_id);
            }
            else
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }
        }

        /**
         * Returns an existing Account from the Database
         *
         * @param string $search_method
         * @param string $input
         * @return Account
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         */
        public function getAccount(string $search_method, string $input): Account
        {
            switch($search_method)
            {
                case AccountSearchMethod::byId:
                    $input = (int)$input;
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    break;

                case AccountSearchMethod::byUsername:
                case AccountSearchMethod::byEmail:
                case AccountSearchMethod::byPublicID:
                    $input = $this->intellivoidAccounts->database->real_escape_string($input);
                    $search_method = $this->intellivoidAccounts->database->real_escape_string($search_method);
                    break;

                default:
                    throw new InvalidSearchMethodException();
            }

            $query = QueryBuilder::select('users', [
                'id',
                'public_id',
                'username',
                'email',
                'password',
                'status',
                'personal_information',
                'configuration',
                'last_login_id',
                'creation_date'
            ], $search_method, $input);
            $query_results = $this->intellivoidAccounts->database->query($query);

            if($query_results == false)
            {
                throw new DatabaseException($query, $this->intellivoidAccounts->database->error);
            }
            else
            {
                if($query_results->num_rows !== 1)
                {
                    throw new AccountNotFoundException();
                }

                $Row = $query_results->fetch_array(MYSQLI_ASSOC);
                $Row['personal_information'] = ZiProto::decode($Row['personal_information']);
                $Row['configuration'] = ZiProto::decode($Row['configuration']);
                return Account::fromArray($Row);
            }
        }

        /**
         * Updates an existing account in teh database
         *
         * @param Account $account
         * @return bool
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws InvalidAccountStatusException
         * @throws InvalidEmailException
         * @throws InvalidSearchMethodException
         * @throws InvalidUsernameException
         */
        public function updateAccount(Account $account): bool
        {
            if($this->IdExists($account->ID) == false)
            {
                throw new AccountNotFoundException();
            }

            if(Validate::email($account->Email) == false)
            {
                throw new InvalidEmailException();
            }

            if(Validate::username($account->Username) == false)
            {
                throw new InvalidUsernameException();
            }

            switch($account->Status)
            {
                case AccountStatus::VerificationRequired:
                case AccountStatus::Suspended:
                case AccountStatus::Limited:
                case AccountStatus::BlockedDueToGovernmentBackedAttack;
                case AccountStatus::PasswordRecoveryMode;
                case AccountStatus::Active:
                    break;
                default:
                    throw new InvalidAccountStatusException();
            }

            $ID = (int)$account->ID;
            $PublicID = $this->intellivoidAccounts->database->real_escape_string($account->PublicID);
            $Username = $this->intellivoidAccounts->database->real_escape_string($account->Username);
            $Password = $this->intellivoidAccounts->database->real_escape_string($account->Password);
            $Email = $this->intellivoidAccounts->database->real_escape_string($account->Email);
            $Status = (int)$account->Status;
            $PersonalInformation = $this->intellivoidAccounts->database->real_escape_string(
                ZiProto::encode($account->PersonalInformation->toArray())
            );
            $Configuration = $this->intellivoidAccounts->database->real_escape_string(
                ZiProto::encode($account->Configuration->toArray())
            );
            $LastLoginId = (int)$account->LastLoginID;

            $Query = QueryBuilder::update('users',
                array(
                    'public_id' => $PublicID,
                    'username' => $Username,
                    'password' => $Password,
                    'email' => $Email,
                    'status' => $Status,
                    'personal_information' => $PersonalInformation,
                    'configuration' => $Configuration,
                    'last_login_id' => $LastLoginId
                ), 'id', $ID);
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
         * Checks the login details of the account
         *
         * @param string $username_or_email
         * @param string $password
         * @return bool
         * @throws AccountNotFoundException
         * @throws AccountSuspendedException
         * @throws DatabaseException
         * @throws IncorrectLoginDetailsException
         * @throws InvalidSearchMethodException
         * @noinspection DuplicatedCode
         * @noinspection PhpUnused
         */
        public function checkLogin(string $username_or_email, string $password): bool
        {
            $account_details = null;

            if($this->usernameExists($username_or_email) == true)
            {
                $account_details = $this->getAccount(AccountSearchMethod::byUsername, $username_or_email);
            }
            elseif($this->emailExists($username_or_email) == true)
            {
                $account_details = $this->getAccount(AccountSearchMethod::byEmail, $username_or_email);
            }
            else
            {
                throw new IncorrectLoginDetailsException();
            }

            if($account_details->Status == AccountStatus::Suspended)
            {
                throw new AccountSuspendedException();
            }

            if($account_details->Password !== Hashing::password($password))
            {
                throw new IncorrectLoginDetailsException();
            }

            return true;
        }

        /**
         * Returns the account if the authentication is correct
         *
         * @param string $username_or_email
         * @param string $password
         * @return Account
         * @throws AccountNotFoundException
         * @throws AccountSuspendedException
         * @throws DatabaseException
         * @throws GovernmentBackedAttackModeEnabledException
         * @throws IncorrectLoginDetailsException
         * @throws InvalidSearchMethodException
         * @noinspection DuplicatedCode
         * @noinspection PhpUnused
         */
        public function getAccountByAuth(string $username_or_email, string $password): Account
        {
            if(Validate::email($username_or_email) == false)
            {
                if(Validate::username($username_or_email) == false)
                {
                    throw new IncorrectLoginDetailsException();
                }
            }

            if(Validate::password($password) == false)
            {
                throw new IncorrectLoginDetailsException();
            }

            $account_details = null;

            if($this->usernameExists($username_or_email) == true)
            {
                $account_details = $this->getAccount(AccountSearchMethod::byUsername, $username_or_email);
            }
            elseif($this->emailExists($username_or_email) == true)
            {
                $account_details = $this->getAccount(AccountSearchMethod::byEmail, $username_or_email);
            }
            else
            {
                throw new IncorrectLoginDetailsException();
            }

            if($account_details->Status == AccountStatus::Suspended)
            {
                throw new AccountSuspendedException();
            }

            if($account_details->Password !== Hashing::password($password))
            {
                throw new IncorrectLoginDetailsException();
            }

            if($account_details->Status == AccountStatus::BlockedDueToGovernmentBackedAttack)
            {
                throw new GovernmentBackedAttackModeEnabledException();
            }

            return $account_details;
        }

        /**
         * Determines if the Email exists on the Database
         *
         * @param string $email
         * @return bool
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         */
        public function emailExists(string $email): bool
        {
            try
            {
                $this->getAccount(AccountSearchMethod::byEmail, $email);
                return true;
            }
            catch(AccountNotFoundException $accountNotFoundException)
            {
                return false;
            }
        }

        /**
         * Determines if the Username exists on the Database
         *
         * @param string $username
         * @return bool
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         */
        public function usernameExists(string $username): bool
        {
            try
            {
                $this->getAccount(AccountSearchMethod::byUsername, $username);
                return true;
            }
            catch(AccountNotFoundException $accountNotFoundException)
            {
                return false;
            }
        }

        /**
         * Determines if the Public ID exists on the database
         *
         * @param string $public_id
         * @return bool
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @noinspection PhpUnused
         */
        public function publicIdExists(string $public_id): bool
        {
            try
            {
                $this->getAccount(AccountSearchMethod::byPublicID, $public_id);
                return true;
            }
            catch(AccountNotFoundException $accountNotFoundException)
            {
                return false;
            }
        }

        /**
         * Determines if the ID exists on the database
         *
         * @param int $id
         * @return bool
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         */
        public function IdExists(int $id): bool
        {
            try
            {
                $this->getAccount(AccountSearchMethod::byId, $id);
                return true;
            }
            catch(AccountNotFoundException $accountNotFoundException)
            {
                return false;
            }
        }

        /**
         * Disables all verification methods, puts the account into a password recovery mode
         * and returns a temporary password
         *
         * @param Account $account
         * @return string
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws InvalidAccountStatusException
         * @throws InvalidEmailException
         * @throws InvalidSearchMethodException
         * @throws InvalidUsernameException
         * @throws \TelegramClientManager\Exceptions\DatabaseException
         * @throws InvalidSearchMethod
         * @throws TelegramClientNotFoundException
         */
        public function enterPasswordRecoveryMode(Account $account): string
        {
            // Verify the account
            $this->getAccount(AccountSearchMethod::byId, $account->ID);

            // Unlink Telegram Account
            if($account->Configuration->VerificationMethods->TelegramClientLinked)
            {
                $TelegramClient = $this->intellivoidAccounts->getTelegramClientManager()->getClient(
                    TelegramClientSearchMethod::byId, $account->Configuration->VerificationMethods->TelegramLink->ClientId
                );

                $TelegramClient->AccountID = 0;
                $this->intellivoidAccounts->getTelegramClientManager()->updateClient($TelegramClient);
                try
                {
                    $this->intellivoidAccounts->getTelegramService()->sendPasswordResetNotification($TelegramClient);
                }
                catch(Exception $e)
                {
                    unset($e);
                }

            }

            // Disable verification methods
            $account->Configuration->VerificationMethods->RecoveryCodes->disable();
            $account->Configuration->VerificationMethods->RecoveryCodesEnabled = false;
            $account->Configuration->VerificationMethods->TelegramLink->disable();
            $account->Configuration->VerificationMethods->TelegramClientLinked = false;
            $account->Configuration->VerificationMethods->TwoFactorAuthentication->disable();
            $account->Configuration->VerificationMethods->TwoFactorAuthenticationEnabled = false;

            // Set a temporary password
            $TemporaryPassword = Hashing::TemporaryPassword($account->ID, (int)time());
            $account->Password = Hashing::password($TemporaryPassword);

            // Alter the account status and update it
            $account->Status = AccountStatus::PasswordRecoveryMode;
            $this->updateAccount($account);

            // Return the temporary password
            return $TemporaryPassword;
        }

        /**
         * Enters the account into a government backed attack mode
         *
         * @param Account $account
         * @return bool
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws InvalidAccountStatusException
         * @throws InvalidEmailException
         * @throws InvalidSearchMethodException
         * @throws InvalidUsernameException
         * @noinspection PhpUnused
         */
        public function enterGovernmentBackedAttackMode(Account $account): bool
        {
            // Verify the account
            $this->getAccount(AccountSearchMethod::byId, $account->ID);

            // Set the mode and update it
            $account->Status = AccountStatus::BlockedDueToGovernmentBackedAttack;
            $this->updateAccount($account);

            return true;
        }

        /**
         * Removes the account from a government backed attack mode
         *
         * @param Account $account
         * @return bool
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws InvalidAccountStatusException
         * @throws InvalidEmailException
         * @throws InvalidSearchMethodException
         * @throws InvalidUsernameException
         * @noinspection PhpUnused
         */
        public function disableGovernmentBackedAttackMode(Account $account): bool
        {
            // Verify the account
            $this->getAccount(AccountSearchMethod::byId, $account->ID);

            // Set the mode and update it
            $account->Status = AccountStatus::Active;
            $this->updateAccount($account);

            return true;
        }

        /**
         * Processes the billing for the subscription if applicable
         *
         * @param IntellivoidSubscriptionManager $subscriptionManager
         * @param Subscription $subscription
         * @return bool
         * @throws AccountNotFoundException
         * @throws ApplicationNotFoundException
         * @throws DatabaseException
         * @throws InsufficientFundsException
         * @throws InvalidAccountStatusException
         * @throws InvalidEmailException
         * @throws InvalidFundsValueException
         * @throws InvalidSearchMethodException
         * @throws InvalidSubscriptionPromotionNameException
         * @throws InvalidUsernameException
         * @throws InvalidVendorException
         * @throws SubscriptionPlanNotFoundException
         * @throws SubscriptionPromotionNotFoundException
         * @throws \IntellivoidSubscriptionManager\Exceptions\DatabaseException
         * @throws \IntellivoidSubscriptionManager\Exceptions\InvalidSearchMethodException
         * @noinspection PhpUnused
         */
        public function processBilling(IntellivoidSubscriptionManager $subscriptionManager, Subscription $subscription): bool
        {
            if($subscription->NextBillingCycle > (int)time())
            {
                return False;
            }

            $SubscriptionPlan = $subscriptionManager->getPlanManager()->getSubscriptionPlan(
                SubscriptionPlanSearchMethod::byId, $subscription->SubscriptionPlanID
            );
            $Application = $this->intellivoidAccounts->getApplicationManager()->getApplication(
                ApplicationSearchMethod::byId, $SubscriptionPlan->ApplicationID
            );

            $this->intellivoidAccounts->getTransactionManager()->processPayment(
                $subscription->AccountID, $Application->Name . ' (' . $SubscriptionPlan->PlanName . ')',
                $subscription->Properties->CyclePrice
            );

            if($subscription->Properties->PromotionID !== 0)
            {
                $SubscriptionPromotion = $subscriptionManager->getPromotionManager()->getSubscriptionPromotion(
                    SubscriptionPromotionSearchMethod::byId, $subscription->Properties->PromotionID
                );

                /** @noinspection DuplicatedCode */
                if($SubscriptionPromotion->AffiliationAccountID !== 0)
                {
                    if($SubscriptionPromotion->AffiliationCycleShare > 0)
                    {
                        if($SubscriptionPromotion->CyclePrice >  $SubscriptionPlan->CyclePrice)
                        {
                            $SubscriptionPromotion->CyclePrice = $SubscriptionPlan->CyclePrice;
                        }

                        $this->intellivoidAccounts->getTransactionManager()->addFunds(
                            $SubscriptionPromotion->AffiliationAccountID, $Application->Name . ' (' . $SubscriptionPlan->PlanName . ')',
                            $SubscriptionPromotion->AffiliationInitialShare
                        );
                    }
                }
            }

            return True;
        }

        /**
         * Starts a new subscription for the account
         *
         * @param IntellivoidSubscriptionManager $subscriptionManager
         * @param int $account_id
         * @param int $application_id
         * @param string $plan_name
         * @param string $promotion_code
         * @return Subscription
         * @throws AccountLimitedException
         * @throws AccountNotFoundException
         * @throws ApplicationNotFoundException
         * @throws DatabaseException
         * @throws InsufficientFundsException
         * @throws InvalidAccountStatusException
         * @throws InvalidEmailException
         * @throws InvalidFundsValueException
         * @throws InvalidSearchMethodException
         * @throws InvalidSubscriptionPromotionNameException
         * @throws InvalidUsernameException
         * @throws InvalidVendorException
         * @throws SubscriptionPlanNotFoundException
         * @throws SubscriptionPromotionNotFoundException
         * @throws \IntellivoidSubscriptionManager\Exceptions\DatabaseException
         * @throws \IntellivoidSubscriptionManager\Exceptions\InvalidSearchMethodException
         * @throws SubscriptionNotFoundException
         * @noinspection DuplicatedCode
         * @noinspection PhpUnused
         */
        public function startSubscription(IntellivoidSubscriptionManager $subscriptionManager, int $account_id, int $application_id, string $plan_name, string $promotion_code = "NONE"): Subscription
        {
            // Retrieve the required information
            /** @noinspection DuplicatedCode */
            $Application = $this->intellivoidAccounts->getApplicationManager()->getApplication(ApplicationSearchMethod::byId, $application_id);
            $Account = $this->intellivoidAccounts->getAccountManager()->getAccount(AccountSearchMethod::byId, $account_id);
            if($Account->Status == AccountStatus::Limited)
            {
                throw new AccountLimitedException();
            }

            $SubscriptionPlan = $subscriptionManager->getPlanManager()->getSubscriptionPlanByName(
                $application_id, $plan_name
            );


            $properties = new Subscription\Properties();
            $SubscriptionPromotion = null;

            if($promotion_code !== "NONE")
            {
                $SubscriptionPromotion = $subscriptionManager->getPromotionManager()->getSubscriptionPromotion(
                    SubscriptionPromotionSearchMethod::byPromotionCode, $promotion_code
                );
            }

            /** @noinspection DuplicatedCode */
            if(count($SubscriptionPlan->Features) > 0)
            {
                foreach($SubscriptionPlan->Features as $feature)
                {
                    $properties->addFeature($feature);
                }
            }

            if($SubscriptionPromotion == null)
            {
                $properties->InitialPrice = $SubscriptionPlan->InitialPrice;
                $properties->CyclePrice = $SubscriptionPlan->CyclePrice;
                $properties->PromotionID = 0;
            }
            else
            {
                $properties->InitialPrice = $SubscriptionPromotion->InitialPrice;
                $properties->CyclePrice = $SubscriptionPromotion->CyclePrice;
                $properties->PromotionID = $SubscriptionPromotion->ID;

                if(count($SubscriptionPromotion->Features) > 0)
                {
                    foreach($SubscriptionPromotion->Features as $feature)
                    {
                        $properties->addFeature($feature);
                    }
                }
            }

            $this->intellivoidAccounts->getTransactionManager()->processPayment(
                $account_id, $Application->Name . ' (' . $SubscriptionPlan->PlanName . ')',
                $properties->InitialPrice
            );

            if($SubscriptionPromotion->AffiliationAccountID !== 0)
            {
                if($SubscriptionPromotion->AffiliationInitialShare > 0)
                {

                    if($SubscriptionPromotion->AffiliationInitialShare > $properties->InitialPrice)
                    {
                        $SubscriptionPromotion->AffiliationInitialShare = $properties->InitialPrice;
                    }

                    $this->intellivoidAccounts->getTransactionManager()->addFunds(
                        $SubscriptionPromotion->AffiliationAccountID, $Application->Name . ' (' . $SubscriptionPlan->PlanName . ')',
                        $SubscriptionPromotion->AffiliationInitialShare
                    );
                }
            }

            $public_id = Hashing::SubscriptionPublicID($account_id, $SubscriptionPlan->ID);
            $public_id = $this->intellivoidAccounts->database->real_escape_string($public_id);
            $account_id = (int)$account_id;
            $subscription_plan_id = (int)$SubscriptionPlan->ID;
            $active = (int)True;
            $billing_cycle = (int)$SubscriptionPlan->BillingCycle;
            $next_billing_cycle = (int)time() + $billing_cycle;
            $properties = ZiProto::encode($properties->toArray());
            $properties = $this->intellivoidAccounts->database->real_escape_string($properties);
            $created_timestamp = (int)time();
            $flags = ZiProto::encode([]);
            $flags = $this->intellivoidAccounts->database->real_escape_string($flags);

            $Query = QueryBuilder::insert_into('subscriptions', array(
                'public_id' => $public_id,
                'account_id' => (int)$account_id,
                'subscription_plan_id' => (int)$subscription_plan_id,
                'active' => $active,
                'billing_cycle' => $billing_cycle,
                'next_billing_cycle' => $next_billing_cycle,
                'properties' => $properties,
                'created_timestamp' => $created_timestamp,
                'flags' => $flags
            ));
            $QueryResults = $this->intellivoidAccounts->database->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAccounts->database->error);
            }

            return $subscriptionManager->getSubscriptionManager()->getSubscription(SubscriptionSearchMethod::byPublicId, $public_id);
        }
    }