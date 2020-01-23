-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 23, 2020 at 09:02 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `intellivoid`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--
-- Creation: Jan 23, 2020 at 07:59 PM
--

CREATE TABLE `applications` (
  `id` int(255) NOT NULL COMMENT 'Internal Database ID',
  `public_app_id` varchar(255) DEFAULT NULL COMMENT 'The public unique Application ID',
  `secret_key` varchar(255) DEFAULT NULL COMMENT 'The secret key for authentication operations',
  `name` varchar(255) DEFAULT NULL COMMENT 'The name of the application',
  `name_safe` varchar(255) DEFAULT NULL COMMENT 'Safe version of the application name',
  `permissions` blob DEFAULT NULL COMMENT 'The permissions that this appication requires when requesting authentication access',
  `status` int(255) DEFAULT NULL COMMENT 'The status of this application, this determines the operation of the authentication',
  `authentication_mode` int(255) DEFAULT NULL COMMENT 'The mode of authentication that this application uses',
  `account_id` int(255) DEFAULT NULL COMMENT 'The account ID that this application is owned by',
  `flags` blob DEFAULT NULL COMMENT 'Flags associated with this Application',
  `creation_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this application was registered',
  `last_updated_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this application was last updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table for Applications that are registered in Intellivoid';

--
-- RELATIONSHIPS FOR TABLE `applications`:
--   `account_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `application_access`
--
-- Creation: Jan 23, 2020 at 07:59 PM
--

CREATE TABLE `application_access` (
  `id` int(255) NOT NULL COMMENT 'Unique Internal Database ID for this record',
  `public_id` varchar(255) DEFAULT NULL COMMENT 'Unique public ID for this Application Access Record',
  `application_id` int(255) DEFAULT NULL COMMENT 'The Application ID that''s associated associated with this account',
  `account_id` int(255) DEFAULT NULL COMMENT 'The Account ID that this record is associated with',
  `permissions` blob DEFAULT NULL COMMENT 'The permissions that this Application currently requests from the Account.',
  `status` int(255) DEFAULT NULL COMMENT 'The current status of this Application access to this Account',
  `creation_timestamp` int(255) DEFAULT NULL COMMENT 'The timestamp for when this record was created',
  `last_authenticated_timestamp` int(255) DEFAULT NULL COMMENT 'The timestamp for when this Account last authenticated to ths Application'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Records for what Applications currently has access to Accounts';

--
-- RELATIONSHIPS FOR TABLE `application_access`:
--   `application_id`
--       `applications` -> `id`
--   `account_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `authentication_access`
--
-- Creation: Jan 23, 2020 at 07:59 PM
--

CREATE TABLE `authentication_access` (
  `id` int(255) NOT NULL COMMENT 'The internal unique database ID for this authentication access',
  `access_token` varchar(255) DEFAULT NULL COMMENT 'The private access token for fetching information to the account',
  `application_id` int(255) DEFAULT NULL COMMENT 'The ID of the application that issued this authentication access',
  `account_id` int(255) DEFAULT NULL COMMENT 'The ID of the account that''s authenticated',
  `request_id` int(255) DEFAULT NULL COMMENT 'The ID of the authentication request that created this access',
  `permissions` blob DEFAULT NULL COMMENT 'The permission that the application has access to using this access token',
  `status` int(255) DEFAULT NULL COMMENT 'The status of this current access',
  `expires_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this authentication access expires',
  `last_used_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this authentication access was last used',
  `created_timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this authentication access was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table of authentication access tokens granted by the system for applications';

--
-- RELATIONSHIPS FOR TABLE `authentication_access`:
--   `application_id`
--       `applications` -> `id`
--   `request_id`
--       `authentication_requests` -> `id`
--   `account_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `authentication_requests`
--
-- Creation: Jan 23, 2020 at 07:59 PM
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

--
-- RELATIONSHIPS FOR TABLE `authentication_requests`:
--   `application_id`
--       `applications` -> `id`
--   `account_id`
--       `users` -> `id`
--   `host_id`
--       `users_known_hosts` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `cookies`
--
-- Creation: Jan 23, 2020 at 07:59 PM
-- Last update: Jan 23, 2020 at 07:59 PM
--

CREATE TABLE `cookies` (
  `id` int(11) NOT NULL COMMENT 'Cookie ID',
  `date_creation` int(11) DEFAULT NULL COMMENT 'The unix timestamp of when the cookie was created',
  `disposed` tinyint(1) DEFAULT NULL COMMENT 'Flag for if the cookie was disposed',
  `name` varchar(255) DEFAULT NULL COMMENT 'The name of the Cookie (Public)',
  `token` varchar(255) DEFAULT NULL COMMENT 'The public token of the cookie which uniquely identifies it',
  `expires` int(11) DEFAULT NULL COMMENT 'The Unix Timestamp of when the cookie should expire',
  `ip_tied` tinyint(1) DEFAULT NULL COMMENT 'If the cookie should be strictly tied to the client''s IP Address',
  `client_ip` varchar(255) DEFAULT NULL COMMENT 'The client''s IP Address of the cookie is tied to the IP',
  `data` blob DEFAULT NULL COMMENT 'ZiProto Encoded Data associated with the cookie'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='The main database for Secured Web Sessions library';

--
-- RELATIONSHIPS FOR TABLE `cookies`:
--

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

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--
-- Creation: Jan 23, 2020 at 07:59 PM
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

--
-- RELATIONSHIPS FOR TABLE `subscription_plans`:
--   `application_id`
--       `applications` -> `id`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--
-- Creation: Jan 23, 2020 at 07:59 PM
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

--
-- RELATIONSHIPS FOR TABLE `support_tickets`:
--

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

-- --------------------------------------------------------

--
-- Table structure for table `transaction_records`
--
-- Creation: Jan 23, 2020 at 07:59 PM
--

CREATE TABLE `transaction_records` (
  `id` int(255) NOT NULL COMMENT 'Unique internal database ID for this transaction record',
  `public_id` varchar(255) DEFAULT NULL COMMENT 'Unique public ID for this transaction record',
  `account_id` int(255) DEFAULT NULL COMMENT 'The account ID that this transaction is associated with',
  `vendor` varchar(255) DEFAULT NULL COMMENT 'The name of the vendor or account username that this transaction is to or from',
  `amount` float DEFAULT NULL COMMENT 'The amount that the associated account has withdrawn or received\r\n\r\n< 0 	= Currency taken from associated account\r\n0 	= No currency taken\r\n> 0 	= Currency added to the associated account ',
  `timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this transaction took place'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Records of Balance Transactions regarding subscription payments, payments, withdrawals, etc.';

--
-- RELATIONSHIPS FOR TABLE `transaction_records`:
--   `account_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: Jan 23, 2020 at 07:59 PM
-- Last update: Jan 23, 2020 at 08:00 PM
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL COMMENT 'The ID of the account',
  `public_id` varchar(255) DEFAULT NULL COMMENT 'The Public ID of the account',
  `username` varchar(255) DEFAULT NULL COMMENT 'The Alias/Username used for identifying this account',
  `email` varchar(255) DEFAULT NULL COMMENT 'The Email Address that''s associated with this Account',
  `password` text DEFAULT NULL COMMENT 'The password for authentication (hashed)',
  `status` int(255) DEFAULT NULL COMMENT 'The status of the account',
  `personal_information` blob DEFAULT NULL COMMENT 'The personal information associated with this account (JSON Encoded)',
  `configuration` blob DEFAULT NULL COMMENT 'The configuration associated with this account (JSON Encoded)',
  `last_login_id` int(255) DEFAULT NULL COMMENT 'The ID of the last login record',
  `creation_date` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this Account was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table of all user accounts';

--
-- RELATIONSHIPS FOR TABLE `users`:
--

-- --------------------------------------------------------

--
-- Table structure for table `users_audit`
--
-- Creation: Jan 23, 2020 at 07:59 PM
-- Last update: Jan 23, 2020 at 08:00 PM
--

CREATE TABLE `users_audit` (
  `id` int(255) NOT NULL,
  `account_id` int(255) DEFAULT NULL COMMENT 'The ID of the Account associated with this event',
  `event_type` int(255) DEFAULT NULL COMMENT 'The type of event',
  `timestamp` int(255) DEFAULT NULL COMMENT 'The timestamp of when this record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Security log for account changes';

--
-- RELATIONSHIPS FOR TABLE `users_audit`:
--   `account_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `users_known_hosts`
--
-- Creation: Jan 23, 2020 at 07:59 PM
-- Last update: Jan 23, 2020 at 07:59 PM
--

CREATE TABLE `users_known_hosts` (
  `id` int(255) NOT NULL COMMENT 'The unique ID for this entry',
  `public_id` varchar(255) DEFAULT NULL COMMENT 'The Public ID for this entry',
  `ip_address` varchar(255) DEFAULT NULL COMMENT 'The IP Address associated with the account',
  `blocked` tinyint(1) DEFAULT NULL COMMENT 'Indicates if this host is blocked from accessing this account',
  `location_data` blob DEFAULT NULL COMMENT 'ZiProto encoded data which contains the location data for this host',
  `last_used` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this host was last used to login successfully',
  `created` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this entry was first created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table of known hosts associated with user accounts';

--
-- RELATIONSHIPS FOR TABLE `users_known_hosts`:
--

-- --------------------------------------------------------

--
-- Table structure for table `users_logins`
--
-- Creation: Jan 23, 2020 at 07:59 PM
-- Last update: Jan 23, 2020 at 07:59 PM
--

CREATE TABLE `users_logins` (
  `id` int(255) NOT NULL COMMENT 'The internal database ID for this login record',
  `public_id` varchar(255) DEFAULT NULL COMMENT 'The unique public ID for this login record',
  `origin` varchar(255) DEFAULT NULL COMMENT 'The origin of the login',
  `host_id` int(255) DEFAULT NULL COMMENT 'The ID of the host that was used to login to this account',
  `user_agent` blob DEFAULT NULL COMMENT 'ZiProto encoded data for the detected user agent',
  `account_id` int(255) DEFAULT NULL COMMENT 'The account ID associated with this login record',
  `status` int(255) DEFAULT NULL COMMENT 'The login status\r\n	0 = Success\r\n	1 = Incorrect Credentials\r\n	2 = Verification Failed\r\n	3 = Blocked due to untrusted IP\r\n	4 = Blocked due to suspicious activties ',
  `timestamp` int(255) DEFAULT NULL COMMENT 'The Unix Timestamp of when this record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Login history for Intellivoid Accounts (new)';

--
-- RELATIONSHIPS FOR TABLE `users_logins`:
--   `account_id`
--       `users` -> `id`
--   `host_id`
--       `users_known_hosts` -> `id`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `applications_id_uindex` (`id`),
  ADD KEY `applications_users_id_fk` (`account_id`);

--
-- Indexes for table `application_access`
--
ALTER TABLE `application_access`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `application_access_id_uindex` (`id`),
  ADD KEY `application_access_applications_id_fk` (`application_id`),
  ADD KEY `application_access_users_id_fk` (`account_id`);

--
-- Indexes for table `authentication_access`
--
ALTER TABLE `authentication_access`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `authentication_access_id_uindex` (`id`),
  ADD KEY `authentication_access_applications_id_fk` (`application_id`),
  ADD KEY `authentication_access_authentication_requests_id_fk` (`request_id`),
  ADD KEY `authentication_access_users_id_fk` (`account_id`);

--
-- Indexes for table `authentication_requests`
--
ALTER TABLE `authentication_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `authentication_requests_id_uindex` (`id`),
  ADD KEY `authentication_requests_applications_id_fk` (`application_id`),
  ADD KEY `authentication_requests_users_id_fk` (`account_id`),
  ADD KEY `authentication_requests_users_known_hosts_id_fk` (`host_id`);

--
-- Indexes for table `cookies`
--
ALTER TABLE `cookies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sws_id_uindex` (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscriptions_id_uindex` (`id`),
  ADD KEY `subscriptions_subscription_plans_id_fk` (`subscription_plan_id`),
  ADD KEY `subscriptions_users_id_fk` (`account_id`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_plans_id_uindex` (`id`),
  ADD KEY `subscription_plans_applications_id_fk` (`application_id`);

--
-- Indexes for table `subscription_promotions`
--
ALTER TABLE `subscription_promotions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_promotions_id_uindex` (`id`),
  ADD KEY `subscription_promotions_subscription_plans_id_fk` (`subscription_plan_id`),
  ADD KEY `users` (`affiliation_account_id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `support_tickets_id_uindex` (`id`);

--
-- Indexes for table `telegram_clients`
--
ALTER TABLE `telegram_clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `telegram_clients_id_uindex` (`id`),
  ADD KEY `telegram_clients_users_id_fk` (`account_id`);

--
-- Indexes for table `telegram_verification_codes`
--
ALTER TABLE `telegram_verification_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `telegram_verification_codes_id_uindex` (`id`),
  ADD KEY `telegram_verification_codes_telegram_clients_id_fk` (`telegram_client_id`);

--
-- Indexes for table `tracking_user_agents`
--
ALTER TABLE `tracking_user_agents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracking_user_agents_id_uindex` (`id`);

--
-- Indexes for table `transaction_records`
--
ALTER TABLE `transaction_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_records_id_uindex` (`id`),
  ADD KEY `transaction_records_users_id_fk` (`account_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_id_uindex` (`id`);

--
-- Indexes for table `users_audit`
--
ALTER TABLE `users_audit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_audit_id_uindex` (`id`),
  ADD KEY `users_audit_users_id_fk` (`account_id`);

--
-- Indexes for table `users_known_hosts`
--
ALTER TABLE `users_known_hosts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_known_hosts_id_uindex` (`id`);

--
-- Indexes for table `users_logins`
--
ALTER TABLE `users_logins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_logins_id_uindex` (`id`),
  ADD KEY `users_logins_users_id_fk` (`account_id`),
  ADD KEY `users_logins_users_known_hosts_id_fk` (`host_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Internal Database ID';

--
-- AUTO_INCREMENT for table `application_access`
--
ALTER TABLE `application_access`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Unique Internal Database ID for this record';

--
-- AUTO_INCREMENT for table `authentication_access`
--
ALTER TABLE `authentication_access`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'The internal unique database ID for this authentication access';

--
-- AUTO_INCREMENT for table `authentication_requests`
--
ALTER TABLE `authentication_requests`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique database ID for this authentication request';

--
-- AUTO_INCREMENT for table `cookies`
--
ALTER TABLE `cookies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Cookie ID';

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'The internal unique database ID for this record';

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique database ID for this subscription plan';

--
-- AUTO_INCREMENT for table `subscription_promotions`
--
ALTER TABLE `subscription_promotions`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `telegram_clients`
--
ALTER TABLE `telegram_clients`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'The unique internal database ID for this Telegram Client';

--
-- AUTO_INCREMENT for table `telegram_verification_codes`
--
ALTER TABLE `telegram_verification_codes`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique database ID for this record';

--
-- AUTO_INCREMENT for table `tracking_user_agents`
--
ALTER TABLE `tracking_user_agents`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'The ID for this record';

--
-- AUTO_INCREMENT for table `transaction_records`
--
ALTER TABLE `transaction_records`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Unique internal database ID for this transaction record';

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'The ID of the account';

--
-- AUTO_INCREMENT for table `users_audit`
--
ALTER TABLE `users_audit`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_known_hosts`
--
ALTER TABLE `users_known_hosts`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'The unique ID for this entry';

--
-- AUTO_INCREMENT for table `users_logins`
--
ALTER TABLE `users_logins`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'The internal database ID for this login record';

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_users_id_fk` FOREIGN KEY (`account_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `application_access`
--
ALTER TABLE `application_access`
  ADD CONSTRAINT `application_access_applications_id_fk` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`),
  ADD CONSTRAINT `application_access_users_id_fk` FOREIGN KEY (`account_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `authentication_access`
--
ALTER TABLE `authentication_access`
  ADD CONSTRAINT `authentication_access_applications_id_fk` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`),
  ADD CONSTRAINT `authentication_access_authentication_requests_id_fk` FOREIGN KEY (`request_id`) REFERENCES `authentication_requests` (`id`),
  ADD CONSTRAINT `authentication_access_users_id_fk` FOREIGN KEY (`account_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `authentication_requests`
--
ALTER TABLE `authentication_requests`
  ADD CONSTRAINT `authentication_requests_applications_id_fk` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`),
  ADD CONSTRAINT `authentication_requests_users_id_fk` FOREIGN KEY (`account_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `authentication_requests_users_known_hosts_id_fk` FOREIGN KEY (`host_id`) REFERENCES `users_known_hosts` (`id`);

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_subscription_plans_id_fk` FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans` (`id`),
  ADD CONSTRAINT `subscriptions_users_id_fk` FOREIGN KEY (`account_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD CONSTRAINT `subscription_plans_applications_id_fk` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`);

--
-- Constraints for table `subscription_promotions`
--
ALTER TABLE `subscription_promotions`
  ADD CONSTRAINT `subscription_promotions_subscription_plans_id_fk` FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans` (`id`),
  ADD CONSTRAINT `users` FOREIGN KEY (`affiliation_account_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `telegram_clients`
--
ALTER TABLE `telegram_clients`
  ADD CONSTRAINT `telegram_clients_users_id_fk` FOREIGN KEY (`account_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `telegram_verification_codes`
--
ALTER TABLE `telegram_verification_codes`
  ADD CONSTRAINT `telegram_verification_codes_telegram_clients_id_fk` FOREIGN KEY (`telegram_client_id`) REFERENCES `telegram_clients` (`id`);

--
-- Constraints for table `transaction_records`
--
ALTER TABLE `transaction_records`
  ADD CONSTRAINT `transaction_records_users_id_fk` FOREIGN KEY (`account_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users_audit`
--
ALTER TABLE `users_audit`
  ADD CONSTRAINT `users_audit_users_id_fk` FOREIGN KEY (`account_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users_logins`
--
ALTER TABLE `users_logins`
  ADD CONSTRAINT `users_logins_users_id_fk` FOREIGN KEY (`account_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `users_logins_users_known_hosts_id_fk` FOREIGN KEY (`host_id`) REFERENCES `users_known_hosts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
