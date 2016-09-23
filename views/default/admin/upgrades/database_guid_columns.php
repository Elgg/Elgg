<?php
/**
 * Align all GUID columns to be of the same type/size
 */

$factory = new ElggUpgrade();
$upgrade = $factory->getUpgradeFromPath('admin/upgrades/database_guid_columns');

if ($upgrade->isCompleted()) {
	$count = 0;
} else {
	$count = 8;
}

echo elgg_view('admin/upgrades/view', [
	'count' => $count,
	'action' => 'action/admin/upgrades/upgrade_database_guid_columns',
]);
