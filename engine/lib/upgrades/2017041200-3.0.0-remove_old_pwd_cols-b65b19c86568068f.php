<?php
/**
 * Elgg 3.0.0 upgrade 2017041200
 *
 * Removes old password/hash columns
 */

$db = elgg()->getDb();

$db->updateData("
	ALTER TABLE {$db->prefix}users_entity
	DROP KEY `password`,
	DROP COLUMN `password`,
	DROP COLUMN `salt`
");
