<?php
/**
 * Elgg 1.8-svn upgrade 2011031300
 * twitter_api
 *
 * Updates the database for twitterservice to twitter_api changes.
 */


$ia = elgg_set_ignore_access(true);

// make sure we have updated plugins
elgg_generate_plugin_entities();

$show_hidden = access_get_show_hidden_status();
access_show_hidden_entities(true);

$db_prefix = elgg_get_config('dbprefix');
$site_guid = elgg_get_site_entity()->getGUID();
$old = elgg_get_plugin_from_id('twitterservice');
$new = elgg_get_plugin_from_id('twitter_api');
$has_settings = false;

// if not loaded, don't bother.
if (!$old || !$new) {
	return true;
}

$settings = array('consumer_key', 'consumer_secret', 'sign_on', 'new_users');

foreach ($settings as $setting) {
	$value = $old->getSetting($setting);
	if ($value) {
		$has_settings = true;
		$new->setSetting($setting, $value);
	}
}

// update the user settings
$q = "UPDATE {$db_prefix}private_settings
	SET name = replace(name, 'twitterservice', 'twitter_api')
	WHERE name like '%twitterservice%'";

update_data($q);

// if there were settings, emit a notice to re-enable twitter_api
if ($has_settings) {
	elgg_add_admin_notice('twitter_api:disabled', elgg_echo('update:twitter_api:deactivated'));
}

$old->delete();

access_show_hidden_entities($show_hidden);
elgg_set_ignore_access($ia);