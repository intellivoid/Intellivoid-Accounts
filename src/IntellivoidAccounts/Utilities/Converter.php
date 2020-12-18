<?php


    namespace IntellivoidAccounts\Utilities;


    use IntellivoidAccounts\Abstracts\ApplicationSettingsDatumType;

    /**
     * Class Converter
     * @package IntellivoidAccounts\Utilities
     */
    class Converter
    {
        /**
         * Converts promotion code to standard promotion
         *
         * @param string $input
         * @return string
         */
        public static function subscriptionPromotionCode(string $input): string
        {
            $input = strtoupper($input);
            $input = str_ireplace(' ', '_', $input);
            return $input;
        }

        /**
         * Converts the datum integer type to a string
         *
         * @param int $type
         * @return string
         */
        public static function applicationDatumTypeToString(int $type): string
        {
            switch($type)
            {
                case ApplicationSettingsDatumType::string: return "string";
                case ApplicationSettingsDatumType::boolean: return "boolean";
                case ApplicationSettingsDatumType::integer: return "integer";
                case ApplicationSettingsDatumType::list: return "list";
                case ApplicationSettingsDatumType::array: return "array";
                default: return "unknown";
            }
        }
    }