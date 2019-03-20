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
        const DatabaseException = 104;
        const InvalidSearchMethodException = 105;
        const AccountNotFoundException = 106;
        const UsernameAlreadyExistsException = 107;
        const EmailAlreadyExistsException = 108;
        const IncorrectLoginDetailsException = 109;
        const AccountSuspendedException = 110;
    }