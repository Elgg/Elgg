ALTER TABLE `prefix_system_log` ADD COLUMN `object_type` varchar(50) NOT NULL AFTER `object_class`;
ALTER TABLE `prefix_system_log` ADD COLUMN `object_subtype` varchar(50) NOT NULL AFTER `object_type`;
ALTER TABLE `prefix_system_log` MODIFY  `object_event` varchar(50) NOT NULL;
ALTER TABLE `prefix_system_log` MODIFY  `object_class` varchar(50) NOT NULL;


ALTER TABLE `prefix_system_log` DROP KEY `object_type`;
ALTER TABLE `prefix_system_log` DROP KEY `object_subtype`;
ALTER TABLE `prefix_system_log` DROP KEY `river_key`;

ALTER TABLE `prefix_system_log` ADD KEY `object_type` (`object_type`);
ALTER TABLE `prefix_system_log` ADD KEY `object_subtype` (`object_subtype`);

ALTER TABLE `prefix_system_log` ADD KEY `river_key` (`object_type`, `object_subtype`, `event`);