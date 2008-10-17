ALTER TABLE `prefix_system_log` ADD COLUMN `owner_guid` int(11) NOT NULL AFTER `performed_by_guid`;

ALTER TABLE `prefix_system_log` DROP KEY `owner_guid`;
ALTER TABLE `prefix_system_log` ADD KEY `owner_guid` (`owner_guid`);

ALTER TABLE `prefix_system_log` DROP KEY `river_key`;
ALTER TABLE `prefix_system_log` ADD KEY `river_key` (`object_type`, `object_subtype`, `event`, `access_id`);
