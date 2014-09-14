<?php
/**
 * Data dirs upgrade page
 */

// Upgrade also possible hidden users. This feature get run
// by an administrator so there's no need to ignore access.
$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$factory = new ElggUpgrade();
$upgrade = $factory->getUpgradeFromPath('admin/upgrades/datadirs');

if ($upgrade->isCompleted()) {
	$count = 0;
} else {
	$helper = new Elgg_Upgrades_Helper2013022000(
		elgg_get_site_entity()->guid,
		elgg_get_config('dbprefix')
	);

	$helper->forgetFailures();
	$count = $helper->countUnmigratedUsers();
}

echo elgg_view('admin/upgrades/view', array(
	'count' => $count,
	'action' => 'action/admin/upgrades/upgrade_datadirs',
));

access_show_hidden_entities($access_status);
