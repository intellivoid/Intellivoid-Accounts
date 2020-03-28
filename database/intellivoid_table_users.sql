create table if not exists intellivoid.users
(
    id                   int auto_increment comment 'The ID of the account',
    public_id            varchar(255) null comment 'The Public ID of the account',
    username             varchar(255) null comment 'The Alias/Username used for identifying this account',
    email                varchar(255) null comment 'The Email Address that''s associated with this Account',
    password             text         null comment 'The password for authentication (hashed)',
    status               int          null comment 'The status of the account',
    personal_information blob         null comment 'The personal information associated with this account (JSON Encoded)',
    configuration        blob         null comment 'The configuration associated with this account (JSON Encoded)',
    last_login_id        int          null comment 'The ID of the last login record',
    creation_date        int          null comment 'The Unix Timestamp of when this Account was created',
    constraint users_email_uindex
        unique (email),
    constraint users_id_uindex
        unique (id),
    constraint users_public_id_uindex
        unique (public_id),
    constraint users_username_uindex
        unique (username)
)
    comment 'Table of all user accounts' charset = latin1;

alter table intellivoid.users
    add primary key (id);

