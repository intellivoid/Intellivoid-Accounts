
-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` int(255) NOT NULL COMMENT 'Internal unique database ID for this subscription plan',
  `public_id` varchar(255) DEFAULT NULL COMMENT 'Public unique ID for this subscription plan',
  `application_id` int(255) DEFAULT NULL COMMENT 'The Application ID that this subscription plan is associated with',
  `plan_name` varchar(255) DEFAULT NULL COMMENT 'The name of the plan',
  `features` blob DEFAULT NULL COMMENT 'ZiProto encoded data of a array of feature objects',
  `initial_price` float DEFAULT NULL COMMENT 'The initial price for starting the subscription',
  `cycle_price` float DEFAULT NULL COMMENT 'The price to charge the user per billing cycle',
  `billing_cycle` int(255) DEFAULT NULL COMMENT 'The amount of seconds required for each billing cycle',
  `status` int(255) DEFAULT NULL COMMENT 'The status of the subscription plan',
  `flags` blob DEFAULT NULL COMMENT 'ZiProto encoded data of the flags associated with this subscription plan',
  `last_updated` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this subscription plan was last updated',
  `created_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Applicable Subscription Plans for starting a new subscription';
