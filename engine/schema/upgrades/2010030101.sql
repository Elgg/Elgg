-- $db->alterTable('entities')->change('last_action', Column::INT(11)->notNull()->default('0'))
ALTER TABLE `prefix_entities` CHANGE `last_action` `last_action` INT( 11 ) NOT NULL DEFAULT '0'
