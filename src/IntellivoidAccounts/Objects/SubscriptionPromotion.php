<?php


    namespace IntellivoidAccounts\Objects;


    use IntellivoidAccounts\Abstracts\SubscriptionPromotionStatus;

    /**
     * Class SubscriptionPromotion
     * @package IntellivoidAccounts\Objects
     */
    class SubscriptionPromotion
    {
        /**
         * The internal unique database ID for this record
         *
         * @var int
         */
        public $ID;

        /**
         * The unique public ID for this promotion
         *
         * @var string
         */
        public $PublicID;

        /**
         * User-friendly promotion code for this promotion
         *
         * @var string
         */
        public $PromotionCode;

        /**
         * The subscription plan ID that this promotion is applicable to
         *
         * @var int
         */
        public $SubscriptionPlanID;

        /**
         * The account ID that this promotion is affiliated with
         * 0 = None
         *
         * @var int
         */
        public $AffiliationAccountID;

        /**
         * The share of initial purchase to give to the affiliated account
         *
         * @var float
         */
        public $AffiliationInitialShare;

        /**
         * The share of billing cycles to give to the affiliated account
         *
         * @var float
         */
        public $AffiliationCycleShare;

        /**
         * Array of new features or features to override
         *
         * @var array(Feature)
         */
        public $Features;

        /**
         * The status of this promotion code
         *
         * @var int|SubscriptionPromotionStatus
         */
        public $Status;

        /**
         * Flags associated with this promotion code
         *
         * @var array
         */
        public $Flags;
    }