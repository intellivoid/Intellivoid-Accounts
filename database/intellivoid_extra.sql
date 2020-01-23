
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
