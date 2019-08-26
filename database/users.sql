create table users
(
	id int(255) auto_increment comment 'The ID of the account',
	public_id varchar(255) null comment 'The Public ID of the account',
	username varchar(255) null comment 'The Alias/Username used for identifying this account',
	email varchar(255) null comment 'The Email Address that''s associated with this Account',
	password text null comment 'The password for authentication (hashed)',
	status int(255) null comment 'The status of the account',
	personal_information blob null comment 'The personal information associated with this account (JSON Encoded)',
	configuration blob null comment 'The configuration associated with this account (JSON Encoded)',
	last_login_id int(255) null comment 'The ID of the last login record',
	creation_date int(255) null comment 'The Unix Timestamp of when this Account was created',
	constraint users_id_uindex unique (id)
) comment 'Table of all user accounts';
alter table users add primary key (id);