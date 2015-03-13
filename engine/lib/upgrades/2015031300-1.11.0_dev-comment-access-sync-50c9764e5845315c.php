<?php
/**
 * Elgg 1.11.0-dev upgrade 2015031300
 * comment-access-sync
 *
 * Synchronize comment access_id with the container access_id
 */

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);
$ia = elgg_set_ignore_access(true);

// there may be many instances in large databases
// add \ElggUpgrade object if need to update comments
$dbprefix = elgg_get_config('dbprefix');
$options = array(
	'type' => 'object',
	'subtype' => 'comment',
	'joins' => array(
		"JOIN {$dbprefix}entities e2 ON e.container_guid = e2.guid"
	),
	'wheres' => array(
		"e.access_id != e2.access_id"
	),
	'count' => true
);

if (elgg_get_entities($options)) {
	$path = "admin/upgrades/commentaccess";
	$upgrade = new \ElggUpgrade();

	// Create the upgrade if one with the same URL doesn't already exist
	if (!$upgrade->getUpgradeFromPath($path)) {
		$upgrade->setPath($path);
		$upgrade->title = 'Comments Access Upgrade';
		$upgrade->description = 'Some comments on this system have different access settings than their containers. Run this upgrade to synchronize comment access.';
		$upgrade->save();
	}
}

elgg_set_ignore_access($ia);
access_show_hidden_entities($access_status);
