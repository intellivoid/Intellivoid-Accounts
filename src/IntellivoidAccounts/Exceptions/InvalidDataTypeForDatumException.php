<?php


    namespace IntellivoidAccounts\Exceptions;


    use Exception;
    use Throwable;

    /**
     * Class InvalidDataTypeForDatumException
     * @package IntellivoidAccounts\Objects\ApplicationSettings
     */
    class InvalidDataTypeForDatumException extends Exception
    {
        /**
         * InvalidDataTypeForDatumException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }