<?php /** @noinspection PhpUnused */


    namespace IntellivoidAccounts\Abstracts;

    /**
     * Class KnownHostViolationStatus
     * @package IntellivoidAccounts\Abstracts
     */
    abstract class KnownHostViolationStatus
    {
        /**
         * Returns if the known host isn't violating any rules
         */
        const NoViolation = 0;

        /**
         * Returns if the known host was manually blocked by an administrator
         */
        const HostBlockedByAdministrator = 1;

        /**
         * Returns if the known host was blocked due to creating multiple accounts
         */
        const HostBlockedAccountCreationLimit = 2;
    }