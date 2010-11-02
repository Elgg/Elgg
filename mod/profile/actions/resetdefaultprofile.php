<?php
/**
 * Elgg profile plugin edit default profile action
 *
 * @package ElggProfile
 */

global $CONFIG;
admin_gatekeeper();

if ($fieldlist = get_plugin_setting('user_defined_fields', 'profile')) {
	$fieldlistarray = explode(',', $fieldlist);
	foreach($fieldlistarray as $listitem) {
		set_plugin_setting("admin_defined_profile_{$listitem}", '', 'profile');
		set_plugin_setting("admin_defined_profile_type_{$listitem}", '', 'profile');
	}
}

set_plugin_setting('user_defined_fields', FALSE, 'profile');

system_message(elgg_echo('profile:defaultprofile:reset'));

forward(REFERER);