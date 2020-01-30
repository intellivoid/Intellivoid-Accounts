
-- --------------------------------------------------------

--
-- Table structure for table `otl_codes`
--

CREATE TABLE `otl_codes` (
  `id` int(255) NOT NULL COMMENT 'The internal unique database ID for this login code',
  `code` varchar(255) DEFAULT NULL COMMENT 'Unique authentication code used to login and authenticate',
  `vendor` varchar(255) DEFAULT NULL COMMENT 'The name of the Application/Service that authenticated the account using this code (Default: None)',
  `account_id` int(255) DEFAULT NULL COMMENT 'The account ID that generated this code',
  `status` int(255) DEFAULT NULL COMMENT 'The current status of this authentication code',
  `expires` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp for when this code expires',
  `created` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='One time login codes used for authentication via internal services';
