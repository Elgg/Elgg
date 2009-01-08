ALTER TABLE `prefix_users_entity` DROP KEY `last_action`;
ALTER TABLE `prefix_users_entity` ADD KEY `last_action` (`last_action`);

ALTER TABLE `prefix_users_entity` DROP KEY `last_login`;
ALTER TABLE `prefix_users_entity` ADD KEY `last_login` (`last_login`);