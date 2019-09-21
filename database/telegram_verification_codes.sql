create table telegram_verification_codes
(
    id                 int(255) auto_increment comment 'Internal unique database ID for this record',
    verification_code  varchar(255) null comment 'The generated verification code unique to this request',
    telegram_client_id int(255)     null comment 'The ID of the Telegram Client that generated this code',
    status             int(255)     null comment 'The current status of this verification code',
    expires            int(255)     null comment 'The Unix Timestamp of when this code expires',
    created            int(255)     null comment 'The Unix Timestamp for when this code was created',
    constraint telegram_verification_codes_id_uindex unique (id)
) comment 'Verification codes used to verify Telegram Accounts';

alter table telegram_verification_codes add primary key (id);