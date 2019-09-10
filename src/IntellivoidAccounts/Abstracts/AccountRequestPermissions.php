<?php


    namespace IntellivoidAccounts\Abstracts;

    /**
     * Class AccountRequestPermissions
     * @package IntellivoidAccounts\Abstracts
     */
    abstract class AccountRequestPermissions
    {
        /**
         * Access to personal information such as First Name, Last name, birthday, email if available
         */
        const PersonalInformation = "READ_PERSONAL_INFORMATION";

        /**
         * Access to Account Balance for creating transactions for payments
         */
        const AccountBalance = "INVOKE_ACCOUNT_BALANCE";

        /**
         * Access to send you notifications via Telegram
         */
        const TelegramAccount = "INVOKE_TELEGRAM_ACCOUNT";

        /**
         * Permission to change change personal information associated with your account
         */
        const ChangeAccountSettings = "WRITE_PERSONAL_INFORMATION";
    }