<?php
/**
 * Elgg 1.10.0 upgrade 2014111800
 * add_new_hash_column
 *
 * Upgrades user entity schema to support new password hashes
 */

$db = _elgg_services()->db;
$prefix = $db->getTablePrefix();

$db->updateData("
	ALTER TABLE {$prefix}users_entity
	ADD `password_hash` varchar(255) NOT NULL DEFAULT ''
	AFTER `salt`
");
