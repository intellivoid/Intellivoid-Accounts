
-- --------------------------------------------------------

--
-- Table structure for table `telegram_clients`
--
-- Creation: Jan 23, 2020 at 07:59 PM
--

CREATE TABLE `telegram_clients` (
  `id` int(255) NOT NULL COMMENT 'The unique internal database ID for this Telegram Client',
  `public_id` varchar(255) DEFAULT NULL COMMENT 'The unique Public ID for this Telegram Client',
  `available` tinyint(1) DEFAULT NULL COMMENT 'Indicates if this Telegram Client is available',
  `account_id` int(255) DEFAULT NULL COMMENT '0 If a account is not associated with this Telegram Client',
  `user` blob DEFAULT NULL COMMENT 'ZiProto encoded data for Telegram User Data',
  `chat` blob DEFAULT NULL COMMENT 'ZiProto encoded data for Telegram Chat Data',
  `session_data` blob DEFAULT NULL COMMENT 'ZiProto encoded data for Telegram Session Data',
  `chat_id` varchar(255) DEFAULT NULL COMMENT 'The chat ID associated with this Telegram Client',
  `user_id` varchar(255) DEFAULT NULL COMMENT 'The user ID associated with this Telegram Client',
  `last_activity` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this client was last active',
  `created` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this client was created and registered into the database'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table of Telegram Clients that were assocaited with a Telegram Bot';

--
-- RELATIONSHIPS FOR TABLE `telegram_clients`:
--   `account_id`
--       `users` -> `id`
--
