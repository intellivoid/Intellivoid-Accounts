
-- --------------------------------------------------------

--
-- Table structure for table `transaction_records`
--

CREATE TABLE `transaction_records` (
  `id` int(255) NOT NULL COMMENT 'Unique internal database ID for this transaction record',
  `public_id` varchar(255) DEFAULT NULL COMMENT 'Unique public ID for this transaction record',
  `account_id` int(255) DEFAULT NULL COMMENT 'The account ID that this transaction is associated with',
  `vendor` varchar(255) DEFAULT NULL COMMENT 'The name of the vendor or account username that this transaction is to or from',
  `amount` float DEFAULT NULL COMMENT 'The amount that the associated account has withdrawn or received\r\n\r\n< 0 	= Currency taken from associated account\r\n0 	= No currency taken\r\n> 0 	= Currency added to the associated account ',
  `timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this transaction took place'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Records of Balance Transactions regarding subscription payments, payments, withdrawals, etc.';
