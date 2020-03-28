create table if not exists intellivoid.telegram_verification_codes
(
    id                 int auto_increment comment 'Internal unique database ID for this record',
    verification_code  varchar(255) null comment 'The generated verification code unique to this request',
    telegram_client_id int          null comment 'The ID of the Telegram Client that generated this code',
    status             int          null comment 'The current status of this verification code',
    expires            int          null comment 'The Unix Timestamp of when this code expires',
    created            int          null comment 'The Unix Timestamp for when this code was created',
    constraint telegram_verification_codes_id_uindex
        unique (id),
    constraint telegram_verification_codes_verification_code_uindex
        unique (verification_code)
)
    comment 'Verification codes used to verify Telegram Accounts' collate = utf8mb4_general_ci;

alter table intellivoid.telegram_verification_codes
    add primary key (id);

