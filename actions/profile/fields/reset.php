<?php
/**
 * Reset profile fields action
 *
 */

$fieldlist = elgg_get_config('profile_custom_fields');
if ($fieldlist) {
	$fieldlistarray = explode(',', $fieldlist);
	foreach ($fieldlistarray as $listitem) {
		unset_config("admin_defined_profile_{$listitem}");
		unset_config("admin_defined_profile_type_{$listitem}");
	}
}

unset_config('profile_custom_fields');

system_message(elgg_echo('profile:defaultprofile:reset'));

forward(REFERER);