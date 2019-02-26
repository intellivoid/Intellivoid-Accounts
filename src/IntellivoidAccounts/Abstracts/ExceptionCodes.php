<?php

    namespace IntellivoidAccounts\Abstracts;

    /**
     * Class ExceptionCodes
     * @package IntellivoidAccounts\Abstracts
     */
    abstract class ExceptionCodes
    {
        const InvalidUsernameException = 100;
        const InvalidEmailException = 101;
        const InvalidPasswordException = 102;
        const ConfigurationNotFoundException = 103;
    }