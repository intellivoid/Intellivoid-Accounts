<?php


    namespace IntellivoidAccounts\Exceptions;


    use Exception;
    use Throwable;

    /**
     * Class InvalidDatumTypeException
     * @package IntellivoidAccounts\Exceptions
     */
    class InvalidDatumTypeException extends Exception
    {
        /**
         * InvalidDatumTypeException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }