<?php
/**
 * Reset profile fields action
 *
 */

$fieldlist = get_plugin_setting('user_defined_fields', 'profile');
if ($fieldlist) {
	$fieldlistarray = explode(',', $fieldlist);
	foreach ($fieldlistarray as $listitem) {
		clear_plugin_setting("admin_defined_profile_{$listitem}", 'profile');
		clear_plugin_setting("admin_defined_profile_type_{$listitem}", 'profile');
	}
}

set_plugin_setting('user_defined_fields', FALSE, 'profile');

system_message(elgg_echo('profile:defaultprofile:reset'));

forward(REFERER);