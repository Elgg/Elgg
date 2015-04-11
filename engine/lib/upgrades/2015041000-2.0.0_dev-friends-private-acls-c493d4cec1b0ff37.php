<?php
/**
 * Elgg 2.0.0-dev upgrade 2015041000
 * friends-private-acls
 *
 * TODO Description
 */

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);
$ia = elgg_set_ignore_access(true);

if ($upgrade->isRequired()) {
	$path = "admin/upgrades/friendsprivateacls";
	$upgrade = new \ElggUpgrade();

	// Create the upgrade if one with the same URL doesn't already exist
	if (!$upgrade->getUpgradeFromPath($path)) {
		$upgrade->setPath($path);
		$upgrade->title = 'Access Upgrade';
		$upgrade->description = 'The access system needs to be upgraded';
		$upgrade->save();
	}
}

elgg_set_ignore_access($ia);
access_show_hidden_entities($access_status);
