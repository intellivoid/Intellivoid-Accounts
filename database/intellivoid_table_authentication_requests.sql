create table if not exists intellivoid.authentication_requests
(
    id                    int auto_increment comment 'Internal unique database ID for this authentication request',
    request_token         varchar(255) null comment 'The public request authentication token, which is later used to request an authentication token when authenticated',
    application_id        int          null comment 'The Application ID that issued this request',
    status                int          null comment 'The current status of the authentication request',
    account_id            int          null comment 'The account ID associated with this request once authenticatied, 0 means not authenticated',
    host_id               int          null comment 'The ID of the host that issued this request',
    requested_permissions blob         null comment 'The permissions that the Application requestes from the user',
    created_timestamp     int          null comment 'The Unix Timestamp of when this requested was issued',
    expires_timestamp     int          null comment 'The Unix Timestamp of when this request expires, once expired it won''t be used anymore.',
    constraint authentication_requests_id_uindex
        unique (id),
    constraint authentication_requests_request_token_uindex
        unique (request_token)
)
    comment 'Temporary authentication requests issued by applications' collate = utf8mb4_general_ci;

create index authentication_requests_application_id_account_id_index
    on intellivoid.authentication_requests (application_id, account_id);

alter table intellivoid.authentication_requests
    add primary key (id);

