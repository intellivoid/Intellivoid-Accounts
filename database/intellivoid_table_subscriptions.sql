
-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--
-- Creation: Jan 23, 2020 at 07:59 PM
--

CREATE TABLE `subscriptions` (
  `id` int(255) NOT NULL COMMENT 'The internal unique database ID for this record',
  `public_id` varchar(255) DEFAULT NULL COMMENT 'Unique public ID for this subscription',
  `account_id` int(255) DEFAULT NULL COMMENT 'The ID of the Account that this subscription is associated with',
  `subscription_plan_id` int(255) DEFAULT NULL COMMENT 'The ID of the subscription plan that this subscription is associated with',
  `active` tinyint(1) DEFAULT NULL COMMENT 'Indicates if this subscription is currently active or not',
  `billing_cycle` int(255) DEFAULT NULL COMMENT 'The cycle for billing this subscription (Every x seconds, bill the user) x = this value',
  `next_billing_cycle` int(255) DEFAULT NULL COMMENT 'The next Unix Timestamp for when this billing cycle should be processed',
  `properties` blob DEFAULT NULL COMMENT 'ZiProto Encoded data which represents the properties for this subscription',
  `created_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of this record was created',
  `flags` blob DEFAULT NULL COMMENT 'Admin-placed flags for this subscription record (Special perms, etc)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Subscriptions associated with users and services';

--
-- RELATIONSHIPS FOR TABLE `subscriptions`:
--   `subscription_plan_id`
--       `subscription_plans` -> `id`
--   `account_id`
--       `users` -> `id`
--
