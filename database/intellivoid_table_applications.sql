create table if not exists intellivoid.applications
(
    id                     int auto_increment comment 'Internal Database ID',
    public_app_id          varchar(255) null comment 'The public unique Application ID',
    secret_key             varchar(255) null comment 'The secret key for authentication operations',
    name                   varchar(255) null comment 'The name of the application',
    name_safe              varchar(255) null comment 'Safe version of the application name',
    permissions            blob         null comment 'The permissions that this appication requires when requesting authentication access',
    status                 int          null comment 'The status of this application, this determines the operation of the authentication',
    authentication_mode    int          null comment 'The mode of authentication that this application uses',
    account_id             int          null comment 'The account ID that this application is owned by',
    flags                  blob         null comment 'Flags associated with this Application',
    creation_timestamp     int          null comment 'The Unix Timestamp of when this application was registered',
    last_updated_timestamp int          null comment 'The Unix Timestamp of when this application was last updated',
    constraint applications_id_uindex
        unique (id),
    constraint applications_name_name_safe_uindex
        unique (name, name_safe),
    constraint applications_name_safe_uindex
        unique (name_safe),
    constraint applications_name_uindex
        unique (name),
    constraint applications_public_app_id_uindex
        unique (public_app_id)
)
    comment 'Table for Applications that are registered in Intellivoid' collate = utf8mb4_general_ci;

create index applications_public_app_id_secret_key_index
    on intellivoid.applications (public_app_id, secret_key);

alter table intellivoid.applications
    add primary key (id);

