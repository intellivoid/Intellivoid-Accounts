create table users_known_hosts
(
	id int(255) auto_increment comment 'The unique ID for this entry',
	public_id varchar(255) null comment 'The Public ID for this entry',
	ip_address varchar(255) null comment 'The IP Address associated with the account',
	blocked tinyint(1) null comment 'Indicates if this host is blocked from accessing this account',
	last_used int(255) null comment 'The Unix Timestamp of when this host was last used to login successfully',
	location_data blob null comment 'ZiProto encoded data which contains the location data for this host',
	user_agents blob null comment 'ZiProto encoded array which contains objects of user agents that were used with this host',
	created int(255) null comment 'The Unix Timestamp of when this entry was first created',
	constraint users_known_hosts_id_uindex unique (id)
) comment 'Table of known hosts associated with user accounts';
alter table users_known_hosts add primary key (id);