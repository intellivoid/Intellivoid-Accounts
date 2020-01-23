
-- --------------------------------------------------------

--
-- Table structure for table `application_access`
--
-- Creation: Jan 23, 2020 at 07:59 PM
--

CREATE TABLE `application_access` (
  `id` int(255) NOT NULL COMMENT 'Unique Internal Database ID for this record',
  `public_id` varchar(255) DEFAULT NULL COMMENT 'Unique public ID for this Application Access Record',
  `application_id` int(255) DEFAULT NULL COMMENT 'The Application ID that''s associated associated with this account',
  `account_id` int(255) DEFAULT NULL COMMENT 'The Account ID that this record is associated with',
  `permissions` blob DEFAULT NULL COMMENT 'The permissions that this Application currently requests from the Account.',
  `status` int(255) DEFAULT NULL COMMENT 'The current status of this Application access to this Account',
  `creation_timestamp` int(255) DEFAULT NULL COMMENT 'The timestamp for when this record was created',
  `last_authenticated_timestamp` int(255) DEFAULT NULL COMMENT 'The timestamp for when this Account last authenticated to ths Application'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Records for what Applications currently has access to Accounts';

--
-- RELATIONSHIPS FOR TABLE `application_access`:
--   `application_id`
--       `applications` -> `id`
--   `account_id`
--       `users` -> `id`
--
