<?php
/**
 * Elgg 1.8-svn upgrade 2011031300
 * twitter_api
 *
 * Updates the database for twitterservice to twitter_api changes.
 */

// make sure we have updated plugins
elgg_generate_plugin_entities();

$db_prefix = elgg_get_config('dbprefix');

// find the old settings for twitterservice and copy them to the new one
$options = array(
	'type' => 'object',
	'subtype' => 'plugin',
	'joins' => array("JOIN {$db_prefix}objects_entity oe ON e.guid = oe.guid"),
	'wheres' => array('title = "twitterservice"')
);

$objs = elgg_get_entities($options);

if (!$objs) {
	return true;
}

$service = $objs[0];

$api = elgg_get_plugin_from_id('twitter_api');

if (!$api) {
	return true;
}

$settings = array('consumer_key', 'consumer_secret', 'sign_on', 'new_users');

foreach ($settings as $setting) {
	$api->setSetting($setting, $service->getSetting($setting));
}

// update the user settings
$q = "UPDATE {$db_prefix}private_settings
	SET name = replace('twitterservice', 'twitter_api', name)
	WHERE name like '%twitterservice%'";

update_data($q);

if ($service->isActive()) {
	$api->activate();
	$service->deactivate();
}
