<?php


    namespace IntellivoidAccounts\Abstracts;

    /**
     * Class SubscriptionPromotionStatus
     * @package IntellivoidAccounts\Abstracts
     */
    abstract class SubscriptionPromotionStatus
    {
        const Active = 0;

        const Disabled = 1;

        const Deleted = 2;
    }