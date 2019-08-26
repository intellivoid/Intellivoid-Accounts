create table telegram_clients
(
	id int(255) auto_increment comment 'The unique internal database ID for this Telegram Client',
	public_id varchar(255) null comment 'The unique Public ID for this Telegram Client',
	available tinyint(1) null comment 'Indicates if this Telegram Client is available',
	account_id int(255) null comment '0 If a account is not associated with this Telegram Client',
	user blob null comment 'ZiProto encoded data for Telegram User Data',
	chat blob null comment 'ZiProto encoded data for Telegram Chat Data',
	session_data blob null comment 'ZiProto encoded data for Telegram Session Data',
	chat_id varchar(255) null comment 'The chat ID associated with this Telegram Client',
	user_id varchar(255) null comment 'The user ID associated with this Telegram Client',
	last_activity int(255) null comment 'The Unix Timestamp of when this client was last active',
	created int(255) null comment 'The Unix Timestamp of when this client was created and registered into the database',
	constraint telegram_clients_id_uindex unique (id)
) comment 'Table of Telegram Clients that were assocaited with a Telegram Bot';
alter table telegram_clients add primary key (id);

