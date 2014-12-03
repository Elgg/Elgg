<?php
/**
 * Elgg 1.9.0 upgrade 2013022000
 * datadir_dates_to_guids
 *
 * Registers an upgrade object that will be used to rewrite the structure of
 * the Elgg data directory. The new structure will have the directories named
 * by GUIDs instead of creation dates.
 *
 * See file actions/admin/upgrades/upgrade_comments.php for details.
 */

$upgrade = new \ElggUpgrade();
$path = "admin/upgrades/datadirs";

// Create the upgrade if one with the same URL doesn't already exist
if (!$upgrade->getUpgradeFromPath($path)) {
	$upgrade->setPath($path);
	$upgrade->title = 'Data directory upgrade';
	$upgrade->description = 'Data directory structure has been improved in Elgg 1.9 and it requires a migration. Run this upgrade to complete the migration.';
	$upgrade->save();
}
