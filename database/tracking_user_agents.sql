create table tracking_user_agents
(
    id                int(255) auto_increment comment 'The ID for this record',
    tracking_id       varchar(255) null comment 'The unique tracking ID calculated for this useragent tracking record',
    user_agent_string varchar(255) null comment 'The full string of the user agent',
    platform          varchar(255) null comment 'The platform that was detected from this useragent',
    browser           varchar(255) null comment 'The browser detected from this user agent',
    version           varchar(255) null comment 'The version detected from this user agent',
    host_id           int(255)     null comment 'The ID of the host that this record is associated with',
    created           int(255)     null comment 'The Unix Timestamp of when this record was created',
    last_seen         int(255)     null comment 'The Unix Timestamp of when this record was last seen',
    constraint tracking_user_agents_id_uindex unique (id)
) comment 'Table for tracking User Agents';
alter table tracking_user_agents add primary key (id);