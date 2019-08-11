create table user_messages
(
	id INT(255) primary key auto_increment comment 'The internal Database ID for this Message',
	message_id VARCHAR(255) null comment 'The Public Unique Message ID',
	from_id INT(255) null comment 'The Account ID which indicates who this message is from',
	to_id INT(255) null comment 'The Account ID which indicates who this message is for, 0 means for everyone (Broadcast)',
	reply_to_id VARCHAR(255) null comment 'The Message ID that this message is replying to, 0 if none.',
	subject VARCHAR(255) null comment 'The subject of the message',
	contents TEXT null comment 'The contents of the message',
	verified BOOL null comment 'Indicates if this message is verified',
	seen BOOL null comment 'Indicates if this message has been seen, this value is ignored if it''s a broadcast message',
	allow_reply BOOL null comment 'Indicates if it allows the receiver to reply to this message',
	from_deleted BOOL null comment 'Indicates if the sender deleted this message from their inbox',
	to_deleted BOOL null comment 'Indicates if the receiver deleted this message from their inbox',
	timestamp INT(255) null comment 'The Unix Timestamp of when this message was sent'
) comment 'Messages sent by users within the system';
create unique index user_messages_id_uindex on user_messages (id);
alter table user_messages add constraint user_messages_pk primary key (id);