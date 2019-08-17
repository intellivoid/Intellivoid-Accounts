create table users_logins
(
	id INT(255) auto_increment primary key comment 'The internal database ID for this login record',
	public_id VARCHAR(255) null comment 'The unique public ID for this login record',
	origin VARCHAR(255) null comment 'The origin of the login',
	host_id INT(255) null comment 'The ID of the host that was used to login to this account',
	account_id INT(255) null comment 'The account ID associated with this login record',
	status INT(255) null comment 'The login status
	0 = Success
	1 = Incorrect Credentials
	2 = Verification Failed
	3 = Blocked due to untrusted IP
	4 = Blocked due to suspicious activties ',
	timestamp INT(255) null comment 'The Unix Timestamp of when this record was created'
)
comment 'Login history for Intellivoid Accounts (new)';

create unique index users_logins_id_uindex
	on users_logins (id);

alter table users_logins
	add constraint users_logins_pk
		primary key (id);

