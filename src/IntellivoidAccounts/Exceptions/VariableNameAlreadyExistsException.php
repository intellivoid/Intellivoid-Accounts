<?php


    namespace IntellivoidAccounts\Exceptions;


    use Exception;
    use Throwable;

    /**
     * Class VariableNameAlreadyExistsException
     * @package IntellivoidAccounts\Exceptions
     */
    class VariableNameAlreadyExistsException extends Exception
    {
        /**
         * VariableNameAlreadyExistsException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }