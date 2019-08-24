<?php

    $SourceFolder = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    include_once($SourceFolder . 'IntellivoidAccounts' . DIRECTORY_SEPARATOR . 'IntellivoidAccounts.php');

    $IntellivoidAccounts = new \IntellivoidAccounts\IntellivoidAccounts();

    $ChatObject = new \IntellivoidAccounts\Objects\TelegramClient\Chat();
    $ChatObject->ID = -1251812;
    $ChatObject->Username = 'Test';
    $ChatObject->FirstName = 'Zi';
    $ChatObject->LastName = 'Xing';
    $ChatObject->Type = \IntellivoidAccounts\Abstracts\TelegramChatType::Private;
    $ChatObject->Title = null;

    $UserObject = new \IntellivoidAccounts\Objects\TelegramClient\User();
    $UserObject->ID = 12412418;
    $UserObject->FirstName = "Zi";
    $UserObject->LastName = "Xing";
    $UserObject->Username = "Netkas";
    $UserObject->IsBot = false;
    $UserObject->LanguageCode = "EN";

    $IntellivoidAccounts->getTelegramClientManager()->registerClient(
        $ChatObject, $UserObject
    );