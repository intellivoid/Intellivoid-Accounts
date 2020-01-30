
--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `applications_id_uindex` (`id`);

--
-- Indexes for table `application_access`
--
ALTER TABLE `application_access`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `application_access_id_uindex` (`id`);

--
-- Indexes for table `authentication_access`
--
ALTER TABLE `authentication_access`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `authentication_access_id_uindex` (`id`);

--
-- Indexes for table `authentication_requests`
--
ALTER TABLE `authentication_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `authentication_requests_id_uindex` (`id`);

--
-- Indexes for table `cookies`
--
ALTER TABLE `cookies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sws_id_uindex` (`id`);

--
-- Indexes for table `otl_codes`
--
ALTER TABLE `otl_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `otl_codes_id_uindex` (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscriptions_id_uindex` (`id`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_plans_id_uindex` (`id`);

--
-- Indexes for table `subscription_promotions`
--
ALTER TABLE `subscription_promotions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_promotions_id_uindex` (`id`);

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
  ADD UNIQUE KEY `telegram_clients_id_uindex` (`id`);

--
-- Indexes for table `telegram_verification_codes`
--
ALTER TABLE `telegram_verification_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `telegram_verification_codes_id_uindex` (`id`);

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
  ADD UNIQUE KEY `transaction_records_id_uindex` (`id`);

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
  ADD UNIQUE KEY `users_audit_id_uindex` (`id`);

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
  ADD UNIQUE KEY `users_logins_id_uindex` (`id`);

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
-- AUTO_INCREMENT for table `otl_codes`
--
ALTER TABLE `otl_codes`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'The internal unique database ID for this login code';

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
