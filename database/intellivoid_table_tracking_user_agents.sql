
-- --------------------------------------------------------

--
-- Table structure for table `tracking_user_agents`
--
-- Creation: Jan 23, 2020 at 07:59 PM
-- Last update: Jan 23, 2020 at 07:59 PM
--

CREATE TABLE `tracking_user_agents` (
  `id` int(255) NOT NULL COMMENT 'The ID for this record',
  `tracking_id` varchar(255) DEFAULT NULL COMMENT 'The unique tracking ID calculated for this useragent tracking record',
  `user_agent_string` varchar(255) DEFAULT NULL COMMENT 'The full string of the user agent',
  `platform` varchar(255) DEFAULT NULL COMMENT 'The platform that was detected from this useragent',
  `browser` varchar(255) DEFAULT NULL COMMENT 'The browser detected from this user agent',
  `version` varchar(255) DEFAULT NULL COMMENT 'The version detected from this user agent',
  `host_id` int(255) DEFAULT NULL COMMENT 'The ID of the host that this record is associated with',
  `created` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this record was created',
  `last_seen` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this record was last seen'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table for tracking User Agents';

--
-- RELATIONSHIPS FOR TABLE `tracking_user_agents`:
--
