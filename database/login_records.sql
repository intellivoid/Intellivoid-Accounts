create table login_records
(
	id int(255) auto_increment comment 'The ID of the Login Record',
	public_id varchar(255) null comment 'The Public ID of the login record',
	account_id int(255) null comment 'The Account ID of the login',
	ip_address varchar(255) null comment 'The IP Address that was used for authentication',
	origin varchar(255) null comment 'The origin of this login',
	time int(255) null comment 'The Unix Timestamp of this login',
	status int(255) null comment 'The status of the login',
	constraint login_records_id_uindex unique (id)
) comment 'Login records for accounts';
alter table login_records add primary key (id);