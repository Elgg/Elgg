<?php
/**
 * Elgg 1.10.0 upgrade 2014111800
 * better_hashes
 *
 * Upgrades user entity schema to support new password hashes
 */

$dbprefix = _elgg_services()->config->get('dbprefix');

_elgg_services()->db->updateData("
	ALTER TABLE {$dbprefix}users_entity
	ADD `password_hash` varchar(255) NOT NULL DEFAULT ''
	AFTER `salt`
");
