<?php


    namespace IntellivoidAccounts\Abstracts;

    /**
     * Class AccountRequestPermissions
     * @package IntellivoidAccounts\Abstracts
     */
    abstract class AccountRequestPermissions
    {
        /**
         * Can view the user's username
         *
         * (This permission is always granted)
         */
        const ViewUsername = "READ_USERNAME";

        /**
         * Can request the user's display image
         *
         * (This permission is always granted)
         */
        const GetUserDisplay = "GET_USER_DISPLAY";

        /**
         * Access to personal information such as First Name, Last name, birthday, email if available
         */
        const ReadPersonalInformation = "READ_PERSONAL_INFORMATION";

        /**
         * Edits personal information
         */
        const EditPersonalInformation = "EDIT_PERSONAL_INFORMATION";

        /**
         * Views your Email Address
         */
        const ViewEmailAddress = "READ_EMAIL_ADDRESS";

        /**
         * Makes purchases or activate a paid subscription on the users behalf
         */
        const MakePurchases = "INVOKE_PURCHASES";

        /**
         * Send notifications to Telegram if available
         */
        const TelegramNotifications = "INVOKE_TELEGRAM_NOTIFICATIONS";

        /**
         * Can request basic information about the user's linked Telegram Client such as
         * the user id, username if any, first name if any, last name if any.
         */
        const GetTelegramClient = "READ_TELEGRAM_CLIENT";

        /**
         * Access todo Tasks and groups but cannot change anything
         */
        const AccessTodo = "READ_TODO";

        /**
         * Access and manage todo tasks and groups
         */
        const ManageTodo = "MANAGE_TODO";

        /**
         * Can sync application settings to the user account so that an application
         * can securely save settings associated with the application, for instance;
         * the application could have a setting to enable dark mode, this application
         * can save this variable to the user's account so that it can retrieve the
         * settings the next time the user logins again.
         */
        const SyncApplicationSettings = "SYNC_APPLICATION_SETTINGS";

    }