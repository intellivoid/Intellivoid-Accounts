
-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int(255) NOT NULL,
  `ticket_number` varchar(255) DEFAULT NULL COMMENT 'A Unique Ticket Number used for a reference',
  `source` varchar(255) DEFAULT NULL COMMENT 'The source of the support ticket',
  `subject` varchar(255) DEFAULT NULL COMMENT 'The subject of the ticket',
  `message` text DEFAULT NULL COMMENT 'The message regarding the ticket',
  `response_email` varchar(255) DEFAULT NULL COMMENT 'The response email for the sender of this ticket',
  `ticket_status` int(255) DEFAULT NULL COMMENT 'The status of the support ticket',
  `ticket_notes` text DEFAULT NULL COMMENT 'Optional Administrator Notes that are attached to this ticket',
  `submission_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this support ticket was submitted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table of support tickets that can be reported from various sources';
