<?php


    namespace IntellivoidAccounts\Abstracts;

    /**
     * Class AuthenticationMode
     * @package IntellivoidAccounts\Abstracts
     */
    abstract class AuthenticationMode
    {
        const Available = 0;

        const Unavailable = 1;

        const Suspended = 3;
    }