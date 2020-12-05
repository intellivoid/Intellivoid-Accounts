<?php


    namespace IntellivoidAccounts\Exceptions;


    use Exception;
    use Throwable;

    /**
     * Class ApplicationSettingsRecordNotFoundException
     * @package IntellivoidAccounts\Exceptions
     */
    class ApplicationSettingsRecordNotFoundException extends Exception
    {
        /**
         * ApplicationSettingsRecordNotFoundException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }