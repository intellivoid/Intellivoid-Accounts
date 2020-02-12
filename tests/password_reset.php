<?php

    use IntellivoidAccounts\IntellivoidAccounts;

    $SourceFolder = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    include_once($SourceFolder . 'IntellivoidAccounts' . DIRECTORY_SEPARATOR . 'IntellivoidAccounts.php');

    $IntellivoidAccounts = new IntellivoidAccounts();

    $Account = $IntellivoidAccounts->getAccountManager()->getAccount(
        \IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod::byUsername, 'Admin'
    );

    print($IntellivoidAccounts->getAccountManager()->enterPasswordRecoveryMode($Account));