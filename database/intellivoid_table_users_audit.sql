
-- --------------------------------------------------------

--
-- Table structure for table `users_audit`
--

CREATE TABLE `users_audit` (
  `id` int(255) NOT NULL,
  `account_id` int(255) DEFAULT NULL COMMENT 'The ID of the Account associated with this event',
  `event_type` int(255) DEFAULT NULL COMMENT 'The type of event',
  `timestamp` int(255) DEFAULT NULL COMMENT 'The timestamp of when this record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Security log for account changes';
