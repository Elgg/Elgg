-- Modify field length in private settings (for people who upgraded while #611 present)
ALTER TABLE `prefix_private_settings` MODIFY  `name` varchar(128) NOT NULL;

-- While we're at it, add some more keys
ALTER TABLE `prefix_private_settings` DROP KEY `name`;
ALTER TABLE `prefix_private_settings` ADD KEY `name` (`name`);

ALTER TABLE `prefix_private_settings` DROP KEY `value`;
ALTER TABLE `prefix_private_settings` ADD KEY `value` (`value` (50));