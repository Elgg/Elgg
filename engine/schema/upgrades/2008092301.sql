--- Ensure default character set is UTF8

ALTER TABLE `prefix_config` DEFAULT CHARACTER SET utf8;
ALTER TABLE `prefix_entities` DEFAULT CHARACTER SET utf8;
ALTER TABLE `prefix_entity_subtypes` DEFAULT CHARACTER SET utf8;
ALTER TABLE `prefix_entity_relationships` DEFAULT CHARACTER SET utf8;
ALTER TABLE `prefix_access_collections` DEFAULT CHARACTER SET utf8;
ALTER TABLE `prefix_access_collection_membership` DEFAULT CHARACTER SET utf8;
ALTER TABLE `prefix_objects_entity` DEFAULT CHARACTER SET utf8;
ALTER TABLE `prefix_sites_entity` DEFAULT CHARACTER SET utf8;
ALTER TABLE `prefix_users_entity` DEFAULT CHARACTER SET utf8;
ALTER TABLE `prefix_groups_entity` DEFAULT CHARACTER SET utf8;
ALTER TABLE `prefix_annotations` DEFAULT CHARACTER SET utf8;
ALTER TABLE `prefix_metadata` DEFAULT CHARACTER SET utf8;
ALTER TABLE `prefix_metastrings` DEFAULT CHARACTER SET utf8;
ALTER TABLE `prefix_api_users` DEFAULT CHARACTER SET utf8;
ALTER TABLE `prefix_users_apisessions` DEFAULT CHARACTER SET utf8;
ALTER TABLE `prefix_datalists` DEFAULT CHARACTER SET utf8;
ALTER TABLE `prefix_system_log` DEFAULT CHARACTER SET utf8;

-- New keys

ALTER TABLE `prefix_entities` ADD KEY `type` (`type`);
ALTER TABLE `prefix_entities` ADD KEY `subtype` (`subtype`);
ALTER TABLE `prefix_entities` ADD KEY `owner_guid` (`owner_guid`);
ALTER TABLE `prefix_entities` ADD KEY `container_guid` (`container_guid`);
ALTER TABLE `prefix_entities` ADD KEY `access_id` (`access_id`);
ALTER TABLE `prefix_entities` ADD KEY `time_created` (`time_created`);
ALTER TABLE `prefix_entities` ADD KEY `time_updated` (`time_updated`);

ALTER TABLE `prefix_users_entity` ADD  KEY `email` (`email`(50));
ALTER TABLE `prefix_users_entity` ADD  KEY `code` (`code`(50));

ALTER TABLE `prefix_annotations` ADD KEY `entity_guid` (`entity_guid`);
ALTER TABLE `prefix_annotations` ADD KEY `name_id` (`name_id`);
ALTER TABLE `prefix_annotations` ADD KEY `value_id` (`value_id`);
ALTER TABLE `prefix_annotations` ADD KEY `owner_guid` (`owner_guid`);
ALTER TABLE `prefix_annotations` ADD KEY `access_id` (`access_id`);

ALTER TABLE `prefix_metadata` ADD KEY `entity_guid` (`entity_guid`);
ALTER TABLE `prefix_metadata` ADD KEY `name_id` (`name_id`);
ALTER TABLE `prefix_metadata` ADD KEY `value_id` (`value_id`);
ALTER TABLE `prefix_metadata` ADD KEY `owner_guid` (`owner_guid`);
ALTER TABLE `prefix_metadata` ADD KEY `access_id` (`access_id`);

ALTER TABLE `prefix_metastrings` DROP KEY `string`;
ALTER TABLE `prefix_metastrings` ADD KEY `string` (`string`(50));

ALTER TABLE `prefix_users_apisessions` ADD KEY `token` (`token`);

ALTER TABLE `prefix_system_log` ADD KEY `object_id` (`object_id`);
ALTER TABLE `prefix_system_log` ADD KEY `object_class` (`object_class`);
ALTER TABLE `prefix_system_log` ADD KEY `event` (`event`);
ALTER TABLE `prefix_system_log` ADD KEY `performed_by_guid` (`performed_by_guid`);
ALTER TABLE `prefix_system_log` ADD KEY `time_created` (`time_created`);

DROP TABLE `prefix_privileged_paths`;

-- HMAC Cache protecting against Replay attacks

CREATE TABLE IF NOT EXISTS `prefix_hmac_cache` (
	`hmac` varchar(255) NOT NULL,
	`ts` int(11) NOT NULL,

	PRIMARY KEY  (`hmac`),
	KEY `ts` (`ts`)
) ENGINE=MEMORY;