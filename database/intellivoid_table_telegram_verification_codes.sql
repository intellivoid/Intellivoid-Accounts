
-- --------------------------------------------------------

--
-- Table structure for table `telegram_verification_codes`
--
-- Creation: Jan 23, 2020 at 07:59 PM
--

CREATE TABLE `telegram_verification_codes` (
  `id` int(255) NOT NULL COMMENT 'Internal unique database ID for this record',
  `verification_code` varchar(255) DEFAULT NULL COMMENT 'The generated verification code unique to this request',
  `telegram_client_id` int(255) DEFAULT NULL COMMENT 'The ID of the Telegram Client that generated this code',
  `status` int(255) DEFAULT NULL COMMENT 'The current status of this verification code',
  `expires` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this code expires',
  `created` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp for when this code was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Verification codes used to verify Telegram Accounts';

--
-- RELATIONSHIPS FOR TABLE `telegram_verification_codes`:
--   `telegram_client_id`
--       `telegram_clients` -> `id`
--
