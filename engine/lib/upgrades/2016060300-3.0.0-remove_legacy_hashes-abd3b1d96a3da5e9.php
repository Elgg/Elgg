<?php
/**
 * Elgg 3.0.0 upgrade 2016060300
 * remove_legacy_hashes
 */

$db = _elgg_services()->db;

$db->updateData("
	UPDATE {$db->prefix}users_entity
	SET `password` = '',
	    `salt` = ''
");
