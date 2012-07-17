<?php
/**
 * Unlocks the upgrade script 
 */

// @todo Move this in ElggUpgradeManager::isLocked() when #4682 fixed
global $CONFIG, $DB_QUERY_CACHE;

$is_locked = count(get_data("show tables like '{$CONFIG->dbprefix}locked'"));

// Invalidate query cache
if ($DB_QUERY_CACHE) {
	$DB_QUERY_CACHE->clear();
	elgg_log("Query cache invalidated", 'NOTICE');
}

if ($is_locked) {
	// @todo Move to ElggUpgradeManager::unlock() when #4682 fixed.
	delete_data("drop table {$CONFIG->dbprefix}locked");
	error_log('Upgrade unlocks itself');
}
system_message(elgg_echo('upgrade:unlock:success'));
forward(REFERER);