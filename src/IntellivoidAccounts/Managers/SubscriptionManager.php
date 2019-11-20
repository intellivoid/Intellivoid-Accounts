<?php /** @noinspection PhpUnused */


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\AccountStatus;
    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
    use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationSearchMethod;
    use IntellivoidAccounts\Abstracts\SearchMethods\SubscriptionPlanSearchMethod;
    use IntellivoidAccounts\Abstracts\SearchMethods\SubscriptionPromotionSearchMethod;
    use IntellivoidAccounts\Exceptions\AccountLimitedException;
    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\Exceptions\InvalidSubscriptionPromotionNameException;
    use IntellivoidAccounts\Exceptions\SubscriptionPlanNotFoundException;
    use IntellivoidAccounts\Exceptions\SubscriptionPromotionNotFoundException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\Subscription;
    use IntellivoidAccounts\Objects\SubscriptionPlan;
    use IntellivoidAccounts\Utilities\Hashing;

    /**
     * Class SubscriptionManager
     * @package IntellivoidAccounts\Managers
     */
    class SubscriptionManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * SubscriptionManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        /**
         * @param int $account_id
         * @param int $application_id
         * @param string $plan_name
         * @param string $promotion_code
         * @return Subscription
         * @throws AccountLimitedException
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws InvalidSubscriptionPromotionNameException
         * @throws SubscriptionPromotionNotFoundException
         * @throws SubscriptionPlanNotFoundException
         */
        public function startSubscription(int $account_id, int $application_id, string $plan_name, string $promotion_code = "NONE"): Subscription
        {
            // Retrieve the required information
            $Application = $this->intellivoidAccounts->getApplicationManager()->getApplication(ApplicationSearchMethod::byId, $application_id);
            $Account = $this->intellivoidAccounts->getAccountManager()->getAccount(AccountSearchMethod::byId, $account_id);
            if($Account->Status == AccountStatus::Limited)
            {
                throw new AccountLimitedException();
            }

            $SubscriptionPlan = $this->intellivoidAccounts->getSubscriptionPlanManager()->getSubscriptionPlanByName(
                $application_id, $plan_name
            );

            $SubscriptionPromotion = null;
            if($promotion_code !== "NONE")
            {
                $SubscriptionPromotion = $this->intellivoidAccounts->getSubscriptionPromotionManager()->getSubscriptionPromotion(
                    SubscriptionPromotionSearchMethod::byPromotionCode, $promotion_code
                );
            }

            if($SubscriptionPromotion == null)
            {
                $this->intellivoidAccounts->getTransactionManager()->processPayment(
                    $account_id, $Application->Name . ' (' . $SubscriptionPlan->PlanName . ')',
                    (float)$SubscriptionPlan->InitialPrice
                );
            }
            else
            {
                // TODO: Need to update promotion system to have initial share
                $this->intellivoidAccounts->getTransactionManager()->processPayment(
                    $account_id, $Application->Name . ' (' . $SubscriptionPlan->PlanName . ')',
                    (float)$SubscriptionPromotion->AffiliationInitialShare
                );
            }



            $properties = new Subscription\Properties();
            $properties->InitialPrice =

            $public_id = Hashing::SubscriptionPublicID($account_id, $SubscriptionPlan->ID);
            $public_id = $this->intellivoidAccounts->database->real_connect($public_id);
            $account_id = (int)$account_id;
            $subscription_plan_id = (int)$SubscriptionPlan->ID;
            $active = (int)True;
            $billing_cycle = (int)$SubscriptionPlan->BillingCycle;
            $next_billing_cycle = (int)time() + $billing_cycle;

        }

    }