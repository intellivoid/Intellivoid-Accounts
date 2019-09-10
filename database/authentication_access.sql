create table authentication_access
(
    id                  int(255) auto_increment comment 'The internal unique database ID for this authentication access',
    access_token        varchar(255) null comment 'The private access token for fetching information to the account',
    application_id      int(255)     null comment 'The ID of the application that issued this authentication access',
    account_id          int(255)     null comment 'The ID of the account that''s authenticated',
    request_id          int(255)     null comment 'The ID of the authentication request that created this access',
    status              int(255)     null comment 'The status of this current access',
    expires_timestamp   int(255)     null comment 'The Unix Timestamp of when this authentication access expires',
    last_used_timestamp int(255)     null comment 'The Unix Timestamp of when this authentication access was last used',
    created_timestamp   int(255)     null comment 'The Unix Timestamp of when this authentication access was created',
    constraint authentication_access_id_uindex unique (id)
) comment 'Table of authetication access tokens granted by the system for applications';
alter table authentication_access add primary key (id);