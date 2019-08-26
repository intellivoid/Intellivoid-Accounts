create table users_logins
(
	id int(255) auto_increment comment 'The internal database ID for this login record',
	public_id varchar(255) null comment 'The unique public ID for this login record',
	origin varchar(255) null comment 'The origin of the login',
	host_id int(255) null comment 'The ID of the host that was used to login to this account',
	account_id int(255) null comment 'The account ID associated with this login record',
	status int(255) null comment 'The login status
	0 = Success
	1 = Incorrect Credentials
	2 = Verification Failed
	3 = Blocked due to untrusted IP
	4 = Blocked due to suspicious activties',
	timestamp int(255) null comment 'The Unix Timestamp of when this record was created',
	constraint users_logins_id_uindex unique (id)
) comment 'Login history for Intellivoid Accounts (new)';
alter table users_logins add primary key (id);

