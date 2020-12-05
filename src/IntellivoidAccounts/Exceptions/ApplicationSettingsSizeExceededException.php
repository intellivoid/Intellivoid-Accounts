<?php


    namespace IntellivoidAccounts\Exceptions;

    use Exception;
    use Throwable;

    /**
     * Class ApplicationSettingsSizeExceededException
     * @package IntellivoidAccounts\Exceptions
     */
    class ApplicationSettingsSizeExceededException extends Exception
    {
        /**
         * ApplicationSettingsSizeExceededException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }