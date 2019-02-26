CREATE TABLE users
(
    id INT(255) PRIMARY KEY COMMENT 'The ID of the account' AUTO_INCREMENT,
    public_id VARCHAR(255) COMMENT 'The Public ID of the account',
    username VARCHAR(255) COMMENT 'The Alias/Username used for identifying this account',
    email VARCHAR(255) COMMENT 'The Email Address that''s associated with this Account',
    password TEXT COMMENT 'The password for authentication (hashed)',
    status INT(255) COMMENT 'The status of the account',
    personal_information MEDIUMTEXT COMMENT 'The personal information associated with this account (JSON Encoded)',
    configuration MEDIUMTEXT COMMENT 'The configuration associated with this account (JSON Encoded)',
    last_login_id INT(255) COMMENT 'The ID of the last login record',
    creation_date INT(255) COMMENT 'The Unix Timestamp of when this Account was created'
);
CREATE UNIQUE INDEX users_id_uindex ON users (id);
ALTER TABLE users COMMENT = 'Table of all user accounts';