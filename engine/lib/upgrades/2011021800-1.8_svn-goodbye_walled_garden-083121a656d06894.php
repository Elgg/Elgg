<?php
/**
 * Elgg 1.8-svn upgrade 2011021800
 * goodbye_walled_garden
 *
 * Removes the Walled Garden plugin in favor of new system settings
 */

global $CONFIG;

$access = elgg_set_ignore_access(TRUE);

if (elgg_is_active_plugin('walledgarden')) {
	disable_plugin('walledgarden');
	set_config('allow_registration', FALSE);
	set_config('walled_garden', TRUE);
} else {
	set_config('allow_registration', TRUE);
	set_config('walled_garden', FALSE);
}

// this was for people who manually set the config option
$disable_registration = elgg_get_config('disable_registration');
if ($disable_registration !== null) {
	$allow_registration = !$disable_registration;
	elgg_save_config('allow_registration', $allow_registration);

	$site = elgg_get_site_entity();
	$query = "DELETE FROM {$CONFIG->dbprefix}config
				WHERE name = 'disable_registration' AND site_guid = $site->guid";
	delete_data($query);
}

elgg_set_ignore_access($access);
