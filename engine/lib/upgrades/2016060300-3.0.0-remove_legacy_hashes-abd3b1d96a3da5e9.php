<?php
/**
 * Elgg 3.0.0 upgrade 2016060300
 * remove_legacy_hashes
 */

$dbprefix = elgg_get_config('dbprefix');

update_data("
	ALTER TABLE {$dbprefix}users_entity
	DROP KEY `password`,
	DROP COLUMN `password`,
	DROP COLUMN `salt`
");
