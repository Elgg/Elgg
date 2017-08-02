<?php
/**
 * Reset profile fields action
 */

$fieldlist = elgg_get_config('profile_custom_fields');
if ($fieldlist) {
	$fieldlistarray = explode(',', $fieldlist);
	foreach ($fieldlistarray as $listitem) {
		elgg_remove_config("admin_defined_profile_{$listitem}");
		elgg_remove_config("admin_defined_profile_type_{$listitem}");
	}
}

elgg_remove_config('profile_custom_fields');

return elgg_ok_response('', elgg_echo('profile:defaultprofile:reset'));
