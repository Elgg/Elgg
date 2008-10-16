ALTER TABLE `prefix_system_log` ADD COLUMN `access_id` int(11) NOT NULL AFTER `performed_by_guid`;
ALTER TABLE `prefix_system_log` ADD COLUMN `enabled` enum ('yes', 'no') NOT NULL default 'yes' AFTER `access_id`;

ALTER TABLE `prefix_system_log` DROP KEY `access_id`;
ALTER TABLE `prefix_system_log` ADD KEY `access_id` (`access_id`);

ALTER TABLE `prefix_system_log` DROP KEY `enabled`;
ALTER TABLE `prefix_system_log` ADD KEY `enabled` (`enabled`);

ALTER TABLE `prefix_system_log` DROP KEY `river_key`;
ALTER TABLE `prefix_system_log` ADD KEY `river_key` (`object_type`, `object_subtype`, `event`, `access_id`, `enabled`);