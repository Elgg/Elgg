<?php

/**
 * Clears old simplecache variables out of database
 */

$query = "DELETE FROM {$CONFIG->dbprefix}datalists WHERE name LIKE 'simplecache%'";

delete_data($query);

if ($CONFIG->simplecache_enabled) {
	datalist_set('simplecache_enabled', 1);
	elgg_regenerate_simplecache();
} else {
	datalist_set('simplecache_enabled', 0);
}
