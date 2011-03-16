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
$service = elgg_get_plugin_from_id('twitterservice');
$api = elgg_get_plugin_from_id('twitter_api');

if (!$api || !$service) {
	return true;
}

$settings = array('consumer_key', 'consumer_secret', 'sign_on', 'new_users');

foreach ($settings as $setting) {
	$api->setSetting($setting, $service->getSetting($setting));
}

// update the user settings
$q = "UPDATE {$db_prefix}private_settings
	SET name = replace(name, 'twitterservice', 'twitter_api')
	WHERE name like '%twitterservice%'";

update_data($q);

if ($service->isActive()) {
	$api->activate();
	$service->deactivate();
}

$service->delete();