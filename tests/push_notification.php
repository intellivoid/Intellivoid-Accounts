<?php


    use IntellivoidAccounts\Abstracts\SearchMethods\TelegramClientSearchMethod;
    use IntellivoidAccounts\Exceptions\AuthNotPromptedException;
use IntellivoidAccounts\Exceptions\AuthPromptDeniedException;
use IntellivoidAccounts\Exceptions\AuthPromptExpiredException;
    use IntellivoidAccounts\Exceptions\TelegramServicesNotAvailableException;
    use IntellivoidAccounts\IntellivoidAccounts;

    $SourceFolder = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    include_once($SourceFolder . 'IntellivoidAccounts' . DIRECTORY_SEPARATOR . 'IntellivoidAccounts.php');

    $IntellivoidAccounts = new IntellivoidAccounts();

    $TelegramClient = $IntellivoidAccounts->getTelegramClientManager()->getClient(
        TelegramClientSearchMethod::byId, '1'
    );

    //$IntellivoidAccounts->getTelegramService()->sendNotification($TelegramClient, "CoffeeHouse", "Test Notification!");
    $IntellivoidAccounts->getTelegramService()->promptAuth($TelegramClient, "Netkas", "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.70 Safari/537.36", 3);


    print("Waiting for authentication\n");

    while(true)
    {
        try
        {
            if($IntellivoidAccounts->getTelegramService()->pollAuthPrompt($TelegramClient) == true)
            {
                print("Authenticated!\n");
                exit();
            }
        }
        catch(AuthPromptDeniedException $authPromptDeniedException)
        {
            print("Denied\n");
            exit();
        }
        catch(Exception $exception)
        {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw $exception;
        }

        sleep(5);
    }