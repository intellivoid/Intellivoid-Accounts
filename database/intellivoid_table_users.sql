
-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: Jan 23, 2020 at 07:59 PM
-- Last update: Jan 23, 2020 at 08:00 PM
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL COMMENT 'The ID of the account',
  `public_id` varchar(255) DEFAULT NULL COMMENT 'The Public ID of the account',
  `username` varchar(255) DEFAULT NULL COMMENT 'The Alias/Username used for identifying this account',
  `email` varchar(255) DEFAULT NULL COMMENT 'The Email Address that''s associated with this Account',
  `password` text DEFAULT NULL COMMENT 'The password for authentication (hashed)',
  `status` int(255) DEFAULT NULL COMMENT 'The status of the account',
  `personal_information` blob DEFAULT NULL COMMENT 'The personal information associated with this account (JSON Encoded)',
  `configuration` blob DEFAULT NULL COMMENT 'The configuration associated with this account (JSON Encoded)',
  `last_login_id` int(255) DEFAULT NULL COMMENT 'The ID of the last login record',
  `creation_date` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this Account was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table of all user accounts';

--
-- RELATIONSHIPS FOR TABLE `users`:
--
