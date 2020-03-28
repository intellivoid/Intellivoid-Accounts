create table if not exists intellivoid.users_logins
(
    id         int auto_increment comment 'The internal database ID for this login record',
    public_id  varchar(255) null comment 'The unique public ID for this login record',
    origin     varchar(255) null comment 'The origin of the login',
    host_id    int          null comment 'The ID of the host that was used to login to this account',
    user_agent blob         null comment 'ZiProto encoded data for the detected user agent',
    account_id int          null comment 'The account ID associated with this login record',
    status     int          null comment 'The login status
	0 = Success
	1 = Incorrect Credentials
	2 = Verification Failed
	3 = Blocked due to untrusted IP
	4 = Blocked due to suspicious activties ',
    timestamp  int          null comment 'The Unix Timestamp of when this record was created',
    constraint users_logins_id_uindex
        unique (id),
    constraint users_logins_public_id_uindex
        unique (public_id)
)
    comment 'Login history for Intellivoid Accounts (new)' collate = utf8mb4_general_ci;

alter table intellivoid.users_logins
    add primary key (id);

