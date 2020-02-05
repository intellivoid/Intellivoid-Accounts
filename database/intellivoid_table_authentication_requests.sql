
-- --------------------------------------------------------

--
-- Table structure for table `authentication_requests`
--

CREATE TABLE `authentication_requests` (
  `id` int(255) NOT NULL COMMENT 'Internal unique database ID for this authentication request',
  `request_token` varchar(255) DEFAULT NULL COMMENT 'The public request authentication token, which is later used to request an authentication token when authenticated',
  `application_id` int(255) DEFAULT NULL COMMENT 'The Application ID that issued this request',
  `status` int(255) DEFAULT NULL COMMENT 'The current status of the authentication request',
  `account_id` int(255) DEFAULT NULL COMMENT 'The account ID associated with this request once authenticatied, 0 means not authenticated',
  `host_id` int(255) DEFAULT NULL COMMENT 'The ID of the host that issued this request',
  `requested_permissions` blob DEFAULT NULL COMMENT 'The permissions that the Application requestes from the user',
  `created_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this requested was issued',
  `expires_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this request expires, once expired it won''t be used anymore.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Temporary authentication requests issued by applications';