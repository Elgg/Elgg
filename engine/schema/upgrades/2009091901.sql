-- add an additional column to the river table
-- $db->alterTable('river')->addColumn('annotation_id', Column::INT(11)->notNull()->after('object_guid'));
ALTER TABLE `prefix_river` ADD COLUMN `annotation_id` int(11) NOT NULL AFTER `object_guid`;
-- $db->alterTable('river')->addKey('annotation_id', ['annotation_id'));
ALTER TABLE `prefix_river` ADD KEY `annotation_id` (`annotation_id`);