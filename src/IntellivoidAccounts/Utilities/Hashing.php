<?php

    namespace IntellivoidAccounts\Utilities;

    /**
     * Class Hashing
     * @package IntellivoidAccounts\Utilities
     */
    class Hashing
    {
        /**
         * Calculates the Public ID of the Account
         *
         * @param string $username
         * @param string $password
         * @param string $email
         * @return string
         */
        public static function publicID(string $username, string $password, string $email): string
        {
            $username = hash('haval256,3', $username);
            $password = hash('haval192,4', $password);
            $email = hash('haval256,5', $email);

            $crc_2 = hash('haval160,3', $username . $email);
            $crc_3 = hash('haval128,3', $username . $password);

            return hash('ripemd320', $crc_2 . $crc_3);
        }

        /**
         * Hashes the password
         *
         * @param string $password
         * @return string
         */
        public static function password(string $password)
        {
            return hash('sha512', $password) .  hash('haval256,5', $password);
        }

        /**
         * Creates a public ID for a login record
         *
         * @param int $account_id
         * @param int $unix_timestamp
         * @param int $status
         * @param string $origin
         * @param string $ip_address
         * @return string
         */
        public static function loginPublicID(int $account_id, int $unix_timestamp, int $status, string $origin, string $ip_address)
        {
            $account_id = hash('haval256,5', $account_id);
            $unix_timestamp = hash('haval256,5', $unix_timestamp);
            $status = hash('haval256,5', $status);
            $origin = hash('haval256,5', $origin);
            $ip_address = hash('haval256,5', $ip_address);

            $crc1 = hash('sha256', $account_id . $unix_timestamp . $status);
            $crc2 = hash('sha256', $origin, $ip_address);

            return $crc1 . $crc2;
        }
    }