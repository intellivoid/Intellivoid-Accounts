create table telegram_clients
(
	id INT(255) auto_increment primary key comment 'The unique internal database ID for this Telegram Client',
	public_id VARCHAR(255) null comment 'The unique Public ID for this Telegram Client',
	available BOOL null comment 'Indicates if this Telegram Client is available',
	account_id INT(255) null comment '0 If a account is not associated with this Telegram Client',
	user BLOB null comment 'ZiProto encoded data for Telegram User Data',
	chat BLOB null comment 'ZiProto encoded data for Telegram Chat Data',
	session_data BLOB null comment 'ZiProto encoded data for Telegram Session Data',
	chat_id VARCHAR(255) null comment 'The chat ID associated with this Telegram Client',
	user_id VARCHAR(255) null comment 'The user ID associated with this Telegram Client',
	last_activity INT(255) null comment 'The Unix Timestamp of when this client was last active',
	created INT(255) null comment 'The Unix Timestamp of when this client was created and registered into the database'
) comment 'Table of Telegram Clients that were associated with a Telegram Bot';
create unique index telegram_clients_id_uindex on telegram_clients (id);
alter table telegram_clients add constraint telegram_clients_pk primary key (id);