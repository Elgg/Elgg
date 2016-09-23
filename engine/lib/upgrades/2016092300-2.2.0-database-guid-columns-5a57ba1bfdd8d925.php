<?php
/**
 * Elgg 2.2.0 upgrade 2016092300
 * database-guid-columns
 *
 * Align all GUID columns to be of the same type/size
 */

$ia = elgg_set_ignore_access(true);

$upgrade = new ElggUpgrade();
$path = 'admin/upgrades/database_guid_columns';
if (!$upgrade->getUpgradeFromPath($path)) {
	$upgrade->setPath($path);
	$upgrade->title = 'Align the GUID columns in the database';
	$upgrade->description = 'Not all the columns in the database that store GUIDs are of the same size.
			This can cause problems on very large databases. Before you run this upgrade make sure you have a backup of your database.';
	
	$upgrade->save();
}

elgg_set_ignore_access($ia);
