create table if not exists intellivoid.application_access
(
    id                           int auto_increment comment 'Unique Internal Database ID for this record',
    public_id                    varchar(255) null comment 'Unique public ID for this Application Access Record',
    application_id               int          null comment 'The Application ID that''s associated associated with this account',
    account_id                   int          null comment 'The Account ID that this record is associated with',
    permissions                  blob         null comment 'The permissions that this Application currently requests from the Account.',
    status                       int          null comment 'The current status of this Application access to this Account',
    creation_timestamp           int          null comment 'The timestamp for when this record was created',
    last_authenticated_timestamp int          null comment 'The timestamp for when this Account last authenticated to ths Application',
    constraint application_access_application_id_account_id_uindex
        unique (application_id, account_id),
    constraint application_access_id_uindex
        unique (id),
    constraint application_access_public_id_uindex
        unique (public_id)
)
    comment 'Records for what Applications currently has access to Accounts' collate = utf8mb4_general_ci;

alter table intellivoid.application_access
    add primary key (id);
