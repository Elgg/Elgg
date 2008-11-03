
-- Based on slow query table feedback
ALTER TABLE `prefix_entity_relationships` DROP KEY `relationship`;
ALTER TABLE `prefix_entity_relationships` ADD KEY `relationship` (`relationship`);

ALTER TABLE `prefix_entity_relationships` DROP KEY `guid_two`;
ALTER TABLE `prefix_entity_relationships` ADD KEY `guid_two` (`guid_two`);

-- The following seemed to have been missed off upgrade
ALTER TABLE `prefix_users_entity` DROP KEY `code`;
ALTER TABLE `prefix_users_entity` ADD KEY `code` (`code`);

-- Access collections missing keys
ALTER TABLE `prefix_access_collections` DROP KEY `site_guid`;
ALTER TABLE `prefix_access_collections` ADD KEY `site_guid` (`site_guid`);
ALTER TABLE `prefix_access_collections` DROP KEY `owner_guid`;
ALTER TABLE `prefix_access_collections` ADD KEY `owner_guid` (`owner_guid`);
