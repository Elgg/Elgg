-- $db->alterTable('groups_entity')->dropKey('name')
ALTER TABLE `prefix_groups_entity` DROP KEY `name`;
--                                 ->addKey('name', ['name' => 50]);
ALTER TABLE `prefix_groups_entity` ADD KEY `name` (`name`(50));

--                                 ->dropKey('description')
ALTER TABLE `prefix_groups_entity` DROP KEY `description`;
--                                 ->addKey('description', ['description' => 50]);
ALTER TABLE `prefix_groups_entity` ADD KEY `description` (`description`(50));
