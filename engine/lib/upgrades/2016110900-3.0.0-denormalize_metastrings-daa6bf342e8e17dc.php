<?php
/**
 * Elgg 3.0.0 upgrade 2016110900
 * denormalize_metastrings
 *
 * Denormalizes metastrings into metadata and annotations
 */

$db = _elgg_services()->db;

$tables = [
	'metadata',
	'annotations',
];
foreach ($tables as $table) {
	// create columns
	$db->updateData("
		ALTER TABLE {$db->prefix}{$table}
		ADD COLUMN `value` text NOT NULL AFTER `entity_guid`,
		ADD COLUMN `name` text NOT NULL AFTER `entity_guid`
	");
	
	// move in all metastrings
	$db->insertData("
		UPDATE {$db->prefix}{$table} n_table
		INNER JOIN {$db->prefix}metastrings msn ON n_table.name_id = msn.id
		INNER JOIN {$db->prefix}metastrings msv ON n_table.value_id = msv.id
		SET n_table.name = msn.string,
		    n_table.value = msv.string
	");
	
	// remove columns and create indexes
	$db->updateData("
		ALTER TABLE {$db->prefix}{$table}
		DROP KEY name_id,
		DROP KEY value_id,
		DROP COLUMN name_id,
		DROP COLUMN value_id,
		ADD KEY(name(50)),
		ADD KEY(value(50))
	");
}

// drop table metastrings
$db->deleteData("
	DROP TABLE {$db->prefix}metastrings
");