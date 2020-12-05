<?php


    namespace IntellivoidAccounts\Exceptions;


    use Exception;
    use Throwable;

    /**
     * Class ApplicationSettingsRecordAlreadyExistsException
     * @package IntellivoidAccounts\Exceptions
     */
    class ApplicationSettingsRecordAlreadyExistsException extends Exception
    {
        /**
         * ApplicationSettingsRecordAlreadyExistsException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }