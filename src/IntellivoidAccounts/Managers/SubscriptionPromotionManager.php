<?php


    namespace IntellivoidAccounts\Managers;


    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\SubscriptionPromotion;
    use IntellivoidAccounts\Utilities\Hashing;

    /**
     * Class SubscriptionPromotionManager
     * @package IntellivoidAccounts\Managers
     */
    class SubscriptionPromotionManager
    {
        /**
         * @var IntellivoidAccounts
         */
        private $intellivoidAccounts;

        /**
         * SubscriptionPromotionManager constructor.
         * @param IntellivoidAccounts $intellivoidAccounts
         */
        public function __construct(IntellivoidAccounts $intellivoidAccounts)
        {
            $this->intellivoidAccounts = $intellivoidAccounts;
        }

        public function createSubscriptionPromotion(int $subscription_plan_id, string $promotion_code, int $affiliation_account_id, float $affiliation_initial_share, float $affiliation_cycle_share, array $features): SubscriptionPromotion
        {
            $public_id = Hashing::SubscriptionPromotionPublicID((int)$subscription_plan_id, $promotion_code);
        }
    }