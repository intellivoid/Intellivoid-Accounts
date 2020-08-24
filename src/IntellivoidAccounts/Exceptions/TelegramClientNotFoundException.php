<?php


    namespace IntellivoidAccounts\Exceptions;


    use Throwable;

    /**
     * Class TelegramClientNotFoundException
     * @package IntellivoidAccounts\Exceptions
     * @deprecated
     */
    class TelegramClientNotFoundException extends \Exception
    {
        /**
         * TelegramClientNotFoundException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }