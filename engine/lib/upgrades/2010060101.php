<?php

/**
 * Clears old simplecache variables out of database
 */

$query = "DELETE FROM {$CONFIG->dbprefix}datalists WHERE name LIKE 'simplecache%'";

delete_data($query);

if ($CONFIG->simplecache_enabled) {
	_elgg_datalist_set('simplecache_enabled', 1);
	elgg_invalidate_simplecache();
} else {
	_elgg_datalist_set('simplecache_enabled', 0);
}
