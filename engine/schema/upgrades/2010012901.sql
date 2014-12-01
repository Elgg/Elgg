-- $db->alterTable('entities')->addColumn('last_action', Column::INT(11)->notNull()->after('time_updated'));
ALTER TABLE `prefix_entities` ADD `last_action` INT( 11 ) NOT NULL AFTER `time_updated` 
