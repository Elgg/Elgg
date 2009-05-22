ALTER TABLE `prefix_groups_entity` DROP KEY `name`;
ALTER TABLE `prefix_groups_entity` ADD KEY `name` (`name`(50));

ALTER TABLE `prefix_groups_entity` DROP KEY `description`;
ALTER TABLE `prefix_groups_entity` ADD KEY `description` (`description`(50));
