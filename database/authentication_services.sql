create table authentication_services
(
	id INT(255) primary key auto_increment comment 'The unique Internal ID for this Service',
	available BOOL null comment 'Indicates if this service is currently availble for authentication',
	public_id VARCHAR(255) null comment 'The Unique Public ID for this service',
	service_name VARCHAR(255) null comment 'The name of the service',
	access_key VARCHAR(255) null comment 'The private access key used for requesting authentications',
	permissions BLOB null comment 'ZiProto Encoded data which contains what permissions that this service requires from the user',
	creation_timestamp INT(255) null comment 'Unix Timestamp for when this service was registered'
) comment 'Contains information for services that have access to Intellivoid Authentication';

create unique index authentication_services_id_uindex on authentication_services (id);
alter table authentication_services add constraint authentication_services_pk primary key (id);

