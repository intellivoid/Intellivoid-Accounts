
-- --------------------------------------------------------

--
-- Table structure for table `users_known_hosts`
--

CREATE TABLE `users_known_hosts` (
  `id` int(255) NOT NULL COMMENT 'The unique ID for this entry',
  `public_id` varchar(255) DEFAULT NULL COMMENT 'The Public ID for this entry',
  `ip_address` varchar(255) DEFAULT NULL COMMENT 'The IP Address associated with the account',
  `blocked` tinyint(1) DEFAULT NULL COMMENT 'Indicates if this host is blocked from accessing this account',
  `location_data` blob DEFAULT NULL COMMENT 'ZiProto encoded data which contains the location data for this host',
  `last_used` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this host was last used to login successfully',
  `created` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this entry was first created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table of known hosts associated with user accounts';
