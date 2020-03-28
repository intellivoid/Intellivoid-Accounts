create table if not exists intellivoid.authentication_access
(
    id                  int auto_increment comment 'The internal unique database ID for this authentication access',
    access_token        varchar(255) null comment 'The private access token for fetching information to the account',
    application_id      int          null comment 'The ID of the application that issued this authentication access',
    account_id          int          null comment 'The ID of the account that''s authenticated',
    request_id          int          null comment 'The ID of the authentication request that created this access',
    permissions         blob         null comment 'The permission that the application has access to using this access token',
    status              int          null comment 'The status of this current access',
    expires_timestamp   int          null comment 'The Unix Timestamp of when this authentication access expires',
    last_used_timestamp int          null comment 'The Unix Timestamp of when this authentication access was last used',
    created_timestamp   int          null comment 'The Unix Timestamp of when this authentication access was created',
    constraint authentication_access_access_token_uindex
        unique (access_token),
    constraint authentication_access_id_uindex
        unique (id),
    constraint authentication_access_request_id_uindex
        unique (request_id)
)
    comment 'Table of authentication access tokens granted by the system for applications' collate = utf8mb4_general_ci;

alter table intellivoid.authentication_access
    add primary key (id);

