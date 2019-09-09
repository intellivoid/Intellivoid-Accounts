<?php

    use acm\acm;
    use acm\Objects\Schema;

    if(class_exists('acm\acm') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'acm' . DIRECTORY_SEPARATOR . 'acm.php');
    }

    $acm = new acm(__DIR__, 'Intellivoid Accounts');

    $DatabaseSchema = new Schema();
    $DatabaseSchema->setDefinition('Host', 'localhost');
    $DatabaseSchema->setDefinition('Port', '3306');
    $DatabaseSchema->setDefinition('Username', 'root');
    $DatabaseSchema->setDefinition('Password', '');
    $DatabaseSchema->setDefinition('Name', 'intellivoid');
    $acm->defineSchema('Database', $DatabaseSchema);

    $IpStackSchema = new Schema();
    $IpStackSchema->setDefinition('AccessKey', '<API KEY>');
    $IpStackSchema->setDefinition('UseSSL', 'false');
    $IpStackSchema->setDefinition('IpStackHost', 'api.ipstack.com');
    $acm->defineSchema('IpStack', $IpStackSchema);

    $SystemSchema = new Schema();
    $SystemSchema->setDefinition('ProfilesLocation_Unix', '/etc/user_pictures');
    $SystemSchema->setDefinition('ProfilesLocation_Windows', 'C:\\user_pictures');
    $acm->defineSchema('System', $SystemSchema);

    $acm->processCommandLine();