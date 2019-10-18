create table applications
(
    id                     int(255) auto_increment comment 'Internal Database ID',
    public_app_id          varchar(255) null comment 'The public unique Application ID',
    secret_key             varchar(255) null comment 'The secret key for authentication operations',
    name                   varchar(255) null comment 'The name of the application',
    name_safe              varchar(255) null comment 'Safe version of the application name',
    permissions            blob         null comment 'The permissions that this appication requires when requesting authentication access',
    status                 int(255)     null comment 'The status of this application, this determines the operation of the authentication',
    authentication_mode    int(255)     null comment 'The mode of authentication that this application uses',
    account_id             int(255)     null comment 'The account ID that this application is owned by',
    flags                  blob         null comment 'Flags associated with this Application',
    creation_timestamp     int(255)     null comment 'The Unix Timestamp of when this application was registered',
    last_updated_timestamp int(255)     null comment 'The Unix Timestamp of when this application was last updated',
    constraint applications_id_uindex unique (id)
) comment 'Table for Applications that are registered in Intellivoid';
alter table applications add primary key (id);