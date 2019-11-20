<?php /** @noinspection PhpUnused */


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\Abstracts\AccountStatus;
    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
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
         * @param SubscriptionPlan $subscriptionPlan
         * @param string $promotion_code
         * @return Subscription
         * @throws AccountLimitedException
         * @throws AccountNotFoundException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws InvalidSubscriptionPromotionNameException
         * @throws SubscriptionPromotionNotFoundException
         */
        public function startSubscription(int $account_id, SubscriptionPlan $subscriptionPlan, string $promotion_code = "NONE"): Subscription
        {
            // Retrieve the required information
            $Account = $this->intellivoidAccounts->getAccountManager()->getAccount(AccountSearchMethod::byId, $account_id);

            if($Account->Status == AccountStatus::Limited)
            {
                throw new AccountLimitedException();
            }
            $SubscriptionPromotion = null;
            if($promotion_code !== "NONE")
            {
                $SubscriptionPromotion = $this->intellivoidAccounts->getSubscriptionPromotionManager()->getSubscriptionPromotion(
                    SubscriptionPromotionSearchMethod::byPromotionCode, $promotion_code
                );
            }

        }

    }