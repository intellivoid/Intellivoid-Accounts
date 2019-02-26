CREATE TABLE login_records
(
    id INT(255) PRIMARY KEY COMMENT 'The ID of the Login Record' AUTO_INCREMENT,
    public_Id VARCHAR(255) COMMENT 'The Public ID of the login record',
    ip_address VARCHAR(255) COMMENT 'The IP Address that was used for authentication',
    origin TEXT COMMENT 'The origin of this login',
    time INT(255) COMMENT 'The Unix Timestamp of this login',
    status INT(255) COMMENT 'The status of the login'
);
CREATE UNIQUE INDEX login_records_id_uindex ON login_records (id);
ALTER TABLE login_records COMMENT = 'Login records for accounts';