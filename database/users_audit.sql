create table users_audit
(
	id INT(255) primary key auto_increment,
	account_id INT(255) null comment 'The ID of the Account associated with this event',
	event_type INT(255) null comment 'The type of event',
	timestamp INT(255) null comment 'The timestamp of when this record was created'
)
comment 'Security log for account changes';

create unique index users_audit_log_id_uindex
	on users_audit_log (id);

alter table users_audit_log
	add constraint users_audit_log_pk
		primary key (id);

