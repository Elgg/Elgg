<?php
/**
 * Elgg 3.0.0 upgrade 2016110600
 * datalist_into_config
 *
 * Merges datalists table data into config
 */

$dbprefix = elgg_get_config('dbprefix');

$exists = get_data_row("SHOW TABLES LIKE '{$dbprefix}datalists'");
if (!$exists) {
	// need to return true to let upgrade includer know all is fine
	return true;
}

$duplicates = get_data("SELECT name
	FROM {$dbprefix}datalists
	WHERE name IN (SELECT name FROM {$dbprefix}config)
	AND name NOT IN ('processed_upgrades', 'version')
");

if (!empty($duplicates)) {
	$duplicates_array = [];
	foreach ($duplicates as $duplicate) {
		$duplicates_array[] = $duplicate->name;
	}
	$duplicates = implode(', ', $duplicates_array);
	throw new \DatabaseException("Found names ({$duplicates}) in datalist that also exist in config. Don't know how to merge.");
}

$rows = get_data("SELECT * FROM {$dbprefix}datalists");

foreach ($rows as $row) {
	$value = $row->value;
	
	if (in_array($row->name, ['version', 'processed_upgrades'])) {
		// do not copy name to config as during upgrades this is already taken care of by the upgrade process
		continue;
	}
	elgg_save_config($row->name, $value);
}

// all data migrated, so drop the table
delete_data("DROP TABLE {$dbprefix}datalists");
