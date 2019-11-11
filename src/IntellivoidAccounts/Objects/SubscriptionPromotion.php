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

        /**
         * Determines if the flag is already applied
         *
         * @param string $flag
         * @return bool
         */
        public function hasFlag(string $flag): bool
        {
            $flag = str_ireplace(' ', '_', strtoupper($flag));

            if(in_array($flag, $this->Flags))
            {
                return true;
            }

            return false;
        }

        /**
         * Applies a flag
         *
         * @param string $flag
         * @return bool
         */
        public function applyFlag(string $flag): bool
        {
            $flag = str_ireplace(' ', '_', strtoupper($flag));

            if($this->hasFlag($flag))
            {
                return false;
            }

            $this->Flags[] = $flag;
            return true;
        }

        /**
         * Removes an existing flag
         *
         * @param string $flag
         * @return bool
         */
        public function removeFlag(string $flag)
        {
            $flag = str_ireplace(' ', '_', strtoupper($flag));

            if($this->hasFlag($flag) == false)
            {
                return false;
            }

            $this->Flags = array_diff($this->Flags, [$flag]);
            return true;
        }

    }