<?php
/**
 * Bulk delete users
 */

$user_guids = (array) get_input('user_guids');
if (empty($user_guids)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$hidden = access_show_hidden_entities(true);

foreach ($user_guids as $user_guid) {
	$user = get_user($user_guid);
	if (empty($user)) {
		continue;
	}
	
	$name = $user->getDisplayName();
	if ($user->delete()) {
		system_message(elgg_echo('admin:user:delete:yes', [$name]));
	} else {
		register_error(elgg_echo('entity:delete:fail', [$name]));
	}
}

access_show_hidden_entities($hidden);

return elgg_ok_response();
