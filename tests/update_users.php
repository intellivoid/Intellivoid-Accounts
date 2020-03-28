<?php

    // This script is to update all existing users in the database and generate avatars for each one
    // IVA1.0 -> IVA2.0
    //
    // !!BACKUP BEFORE RUNNING!!
    use IntellivoidAccounts\IntellivoidAccounts;

    $SourceFolder = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    include_once($SourceFolder . 'IntellivoidAccounts' . DIRECTORY_SEPARATOR . 'IntellivoidAccounts.php');

    $IntellivoidAccounts = new IntellivoidAccounts();

    // Fetch users
    print("Fetching all users ..." . PHP_EOL);
    $Query = "SELECT id FROM `users`;";
    $QueryResults = $IntellivoidAccounts->database->query($Query);
    $FetchedAccounts = array();
    if($QueryResults == false)
    {
        print("Database Error" . PHP_EOL);
        print("     ERROR: " . $IntellivoidAccounts->database->error . PHP_EOL);
        exit(255);
    }
    else
    {
        $FetchedAccounts = [];

        while($Row = $QueryResults->fetch_assoc())
        {
            $FetchedAccounts[] = $Row;
        }
    }
    print("Fetched " . count($FetchedAccounts) . " user(s)" . PHP_EOL);

    $CachedPublicIds = [];
    $CurrentCount = 1;
    foreach($FetchedAccounts as $account)
    {
        print("Processing ID " . $account['id'] . PHP_EOL);

        try
        {
            $Account = $IntellivoidAccounts->getAccountManager()->getAccount(
                \IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod::byId, $account['id']
            );
        }
        catch(Exception $e)
        {
            var_dump($e);
            exit(255);
        }

        // Generate a new Public ID
        print("Old PUB_ID: " . $Account->PublicID . PHP_EOL);
        $Account->PublicID = \IntellivoidAccounts\Utilities\Hashing::publicID(
            $Account->Username, $Account->Password, $Account->Email
        );

        if(in_array($Account->PublicID, $CachedPublicIds))
        {
            print("ERROR: Conflicting PUB_ID" . PHP_EOL);
            exit(255);
        }

        $CachedPublicIds[] = $Account->PublicID;
        print("New PUB_ID: " . $Account->PublicID . PHP_EOL);

        print("Generating Avatar" . PHP_EOL);
        if($IntellivoidAccounts->getUdp()->getProfilePictureManager()->avatar_exists($Account->PublicID) == false)
        {
            $IntellivoidAccounts->getUdp()->getProfilePictureManager()->generate_avatar($Account->PublicID);
        }

        $Avatar = $IntellivoidAccounts->getUdp()->getProfilePictureManager()->get_avatar($Account->PublicID);

        print("Updating account (@" . $Account->Username . ")" . PHP_EOL);
        $IntellivoidAccounts->getAccountManager()->updateAccount($Account);
        print("Done (" . $CurrentCount . "/" . count($FetchedAccounts) . ")" . PHP_EOL);
        $CurrentCount += 1;
    }