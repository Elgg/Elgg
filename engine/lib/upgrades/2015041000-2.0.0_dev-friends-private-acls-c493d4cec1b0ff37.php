<?php
/**
 * Elgg 2.0.0-dev upgrade 2015041000
 * friends-private-acls
 *
 * Description
 */

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);
$ia = elgg_set_ignore_access(true);

// we need this upgrade if there are existing users on the system without friends acls
$dbprefix = elgg_get_config('dbprefix');
$friends_acl_id = elgg_get_metastring_id('friends_acl');
$friends_acl = elgg_get_entities(array(
	'type' => 'user',
	'wheres' => array(
		"NOT EXISTS (
    SELECT 1 FROM {$dbprefix}metadata md
    WHERE md.entity_guid = e.guid
        AND md.name_id = $friends_acl_id"
	),
	'count' => true
		));

$private_acl_id = elgg_get_metastring_id('private_acl');
$private_acl = elgg_get_entities(array(
	'type' => 'user',
	'wheres' => array(
		"NOT EXISTS (
    SELECT 1 FROM {$dbprefix}metadata md
    WHERE md.entity_guid = e.guid
        AND md.name_id = $private_acl_id"
	),
	'count' => true
		));

if ($friends_acl || $private_acl) {
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

