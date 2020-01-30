
-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(255) NOT NULL COMMENT 'Internal Database ID',
  `public_app_id` varchar(255) DEFAULT NULL COMMENT 'The public unique Application ID',
  `secret_key` varchar(255) DEFAULT NULL COMMENT 'The secret key for authentication operations',
  `name` varchar(255) DEFAULT NULL COMMENT 'The name of the application',
  `name_safe` varchar(255) DEFAULT NULL COMMENT 'Safe version of the application name',
  `permissions` blob DEFAULT NULL COMMENT 'The permissions that this appication requires when requesting authentication access',
  `status` int(255) DEFAULT NULL COMMENT 'The status of this application, this determines the operation of the authentication',
  `authentication_mode` int(255) DEFAULT NULL COMMENT 'The mode of authentication that this application uses',
  `account_id` int(255) DEFAULT NULL COMMENT 'The account ID that this application is owned by',
  `flags` blob DEFAULT NULL COMMENT 'Flags associated with this Application',
  `creation_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this application was registered',
  `last_updated_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this application was last updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table for Applications that are registered in Intellivoid';
