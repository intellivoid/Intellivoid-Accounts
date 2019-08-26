create table authentication_services
(
	id int(255) auto_increment comment 'The unique Internal ID for this Service',
	available tinyint(1) null comment 'Indicates if this service is currently availble for authentication',
	public_id varchar(255) null comment 'The Unique Public ID for this service',
	service_name varchar(255) null comment 'The name of the service',
	access_key varchar(255) null comment 'The private access key used for requesting authentications',
	permissions blob null comment 'ZiProto Encoded data which contains what permissions that this service requires from the user',
	creation_timestamp int(255) null comment 'Unix Timestamp for when this service was registered',
	constraint authentication_services_id_uindex unique (id)
) comment 'Contains information for services that have access to Intellivoid Authentication';
alter table authentication_services add primary key (id);