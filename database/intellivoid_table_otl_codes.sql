create table if not exists intellivoid.otl_codes
(
    id         int auto_increment comment 'The internal unique database ID for this login code',
    code       varchar(255) null comment 'Unique authentication code used to login and authenticate',
    vendor     varchar(255) null comment 'The name of the Application/Service that authenticated the account using this code (Default: None)',
    account_id int          null comment 'The account ID that generated this code',
    status     int          null comment 'The current status of this authentication code',
    expires    int          null comment 'The Unix Timestamp for when this code expires',
    created    int          null comment 'The Unix Timestamp of when this record was created',
    constraint otl_codes_code_account_id_uindex
        unique (code, account_id),
    constraint otl_codes_code_uindex
        unique (code),
    constraint otl_codes_id_uindex
        unique (id)
)
    comment 'One time login codes used for authentication via internal services' collate = utf8mb4_general_ci;

alter table intellivoid.otl_codes
    add primary key (id);

