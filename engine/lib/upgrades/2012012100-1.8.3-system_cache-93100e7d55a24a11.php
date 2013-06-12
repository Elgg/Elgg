<?php
/**
 * Elgg 1.8.3 upgrade 2012012100
 * system_cache
 *
 * Convert viewpath cache to system cache
 */

$value = _elgg_datalist_get('viewpath_cache_enabled');
_elgg_datalist_set('system_cache_enabled', $value);

$query = "DELETE FROM {$CONFIG->dbprefix}datalists WHERE name='viewpath_cache_enabled'";
delete_data($query);
