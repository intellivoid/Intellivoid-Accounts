
-- --------------------------------------------------------

--
-- Table structure for table `authentication_access`
--
-- Creation: Jan 23, 2020 at 07:59 PM
--

CREATE TABLE `authentication_access` (
  `id` int(255) NOT NULL COMMENT 'The internal unique database ID for this authentication access',
  `access_token` varchar(255) DEFAULT NULL COMMENT 'The private access token for fetching information to the account',
  `application_id` int(255) DEFAULT NULL COMMENT 'The ID of the application that issued this authentication access',
  `account_id` int(255) DEFAULT NULL COMMENT 'The ID of the account that''s authenticated',
  `request_id` int(255) DEFAULT NULL COMMENT 'The ID of the authentication request that created this access',
  `permissions` blob DEFAULT NULL COMMENT 'The permission that the application has access to using this access token',
  `status` int(255) DEFAULT NULL COMMENT 'The status of this current access',
  `expires_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this authentication access expires',
  `last_used_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this authentication access was last used',
  `created_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this authentication access was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table of authentication access tokens granted by the system for applications';

--
-- RELATIONSHIPS FOR TABLE `authentication_access`:
--   `application_id`
--       `applications` -> `id`
--   `request_id`
--       `authentication_requests` -> `id`
--   `account_id`
--       `users` -> `id`
--
