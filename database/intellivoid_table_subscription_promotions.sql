
-- --------------------------------------------------------

--
-- Table structure for table `subscription_promotions`
--
-- Creation: Jan 23, 2020 at 07:59 PM
--

CREATE TABLE `subscription_promotions` (
  `id` int(255) NOT NULL,
  `public_id` varchar(255) DEFAULT NULL COMMENT 'Unique public ID for this subscription promotion',
  `promotion_code` varchar(255) DEFAULT NULL COMMENT 'User friendly promotion code',
  `subscription_plan_id` int(255) DEFAULT NULL COMMENT 'The subscription plan ID that this promotion is applicable to',
  `initial_price` float DEFAULT NULL COMMENT 'The initial price that this promotion is offering for the subscription plan',
  `cycle_price` float DEFAULT NULL COMMENT 'The billing cycle price that this promotion is offering to apply',
  `affiliation_account_id` int(255) DEFAULT NULL COMMENT 'THe Account ID that receives affiliations, 0 = None',
  `affiliation_initial_share` float DEFAULT NULL COMMENT 'The amount of the initial price to share with the affiliation, 0 = None',
  `affiliation_cycle_share` float DEFAULT NULL COMMENT 'The amount to share per cycle with the affiliation, 0 = None',
  `features` blob DEFAULT NULL COMMENT 'Features to add / override',
  `status` int(255) DEFAULT NULL COMMENT 'The current status of the promotion',
  `flags` blob DEFAULT NULL COMMENT 'Flags associated with this promotion code',
  `last_updated_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this record was last updated',
  `created_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Promotion codes applicable to subscriptions';

--
-- RELATIONSHIPS FOR TABLE `subscription_promotions`:
--   `subscription_plan_id`
--       `subscription_plans` -> `id`
--   `affiliation_account_id`
--       `users` -> `id`
--
