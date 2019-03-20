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
    }