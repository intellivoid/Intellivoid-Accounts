
-- --------------------------------------------------------

--
-- Table structure for table `users_logins`
--
-- Creation: Jan 23, 2020 at 07:59 PM
-- Last update: Jan 23, 2020 at 07:59 PM
--

CREATE TABLE `users_logins` (
  `id` int(255) NOT NULL COMMENT 'The internal database ID for this login record',
  `public_id` varchar(255) DEFAULT NULL COMMENT 'The unique public ID for this login record',
  `origin` varchar(255) DEFAULT NULL COMMENT 'The origin of the login',
  `host_id` int(255) DEFAULT NULL COMMENT 'The ID of the host that was used to login to this account',
  `user_agent` blob DEFAULT NULL COMMENT 'ZiProto encoded data for the detected user agent',
  `account_id` int(255) DEFAULT NULL COMMENT 'The account ID associated with this login record',
  `status` int(255) DEFAULT NULL COMMENT 'The login status\r\n	0 = Success\r\n	1 = Incorrect Credentials\r\n	2 = Verification Failed\r\n	3 = Blocked due to untrusted IP\r\n	4 = Blocked due to suspicious activties ',
  `timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Login history for Intellivoid Accounts (new)';

--
-- RELATIONSHIPS FOR TABLE `users_logins`:
--   `account_id`
--       `users` -> `id`
--   `host_id`
--       `users_known_hosts` -> `id`
--
