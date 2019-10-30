<?php


    $SourceFolder = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    include_once($SourceFolder . 'IntellivoidAccounts' . DIRECTORY_SEPARATOR . 'IntellivoidAccounts.php');

    $IntellivoidAccounts = new \IntellivoidAccounts\IntellivoidAccounts();

    $TelegramClient = $IntellivoidAccounts->getTelegramClientManager()->getClient(
        \IntellivoidAccounts\Abstracts\SearchMethods\TelegramClientSearchMethod::byId, '1'
    );

    $IntellivoidAccounts->getTelegramService()->sendNotification($TelegramClient, "CoffeeHouse", "Test Notification!");