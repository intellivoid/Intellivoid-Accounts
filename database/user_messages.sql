create table user_messages
(
	id int(255) auto_increment comment 'The internal Database ID for this Message',
	message_id varchar(255) null comment 'The Public Unique Message ID',
	from_id int(255) null comment 'The Account ID which indicates who this message is from',
	to_id int(255) null comment 'The Account ID which indicates who this message is for, 0 means for everyone (Broadcast)',
	reply_to_id varchar(255) null comment 'The Message ID that this message is replying to, 0 if none.',
	subject varchar(255) null comment 'The subject of the message',
	contents text null comment 'The contents of the message',
	verified tinyint(1) null comment 'Indicates if this message is verified',
	seen tinyint(1) null comment 'Indicates if this message has been seen, this value is ignored if it''s a broadcast message',
	allow_reply tinyint(1) null comment 'Indicates if it allows the receiver to reply to this message',
	from_deleted tinyint(1) null comment 'Indicates if the sender deleted this message from their inbox',
	to_deleted tinyint(1) null comment 'Indicates if the receiver deleted this message from their inbox',
	timestamp int(255) null comment 'The Unix Timestamp of when this message was sent',
	constraint user_messages_id_uindex unique (id)
) comment 'Messages sent by users within the system';
alter table user_messages add primary key (id);

