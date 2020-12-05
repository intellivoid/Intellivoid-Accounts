<?php


    namespace IntellivoidAccounts\Exceptions;


    use Exception;
    use Throwable;

    /**
     * Class VariableNotFoundException
     * @package IntellivoidAccounts\Exceptions
     */
    class VariableNotFoundException extends Exception
    {
        /**
         * VariableNotFoundException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }