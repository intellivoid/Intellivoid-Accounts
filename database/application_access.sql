create table application_access
(
    id                           int(255) auto_increment comment 'Unique Internal Database ID for this record',
    public_id                    varchar(255) null comment 'Unique public ID for this Application Access Record',
    application_id               int(255)     null comment 'The Application ID that''s associated associated with this account',
    account_id                   int(255)     null comment 'The Account ID that this record is associated with',
    status                       int(255)     null comment 'The current status of this Application access to this Account',
    creation_timestamp           int(255)     null comment 'The timestamp for when this record was created',
    last_authenticated_timestamp int(255)     null comment 'The timestamp for when this Account last authenticated to ths Application',
    constraint application_access_id_uindex  unique (id)
) comment 'Records for what Applications currently has access to Accounts';
alter table application_access add primary key (id);