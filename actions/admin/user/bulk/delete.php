<?php
/**
 * Bulk delete users
 */

$user_guids = (array) get_input('user_guids');
if (empty($user_guids)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

elgg_call(ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES, function() use ($user_guids) {
	foreach ($user_guids as $user_guid) {
		$user = get_user($user_guid);
		if (empty($user)) {
			continue;
		}
		
		$name = $user->getDisplayName();
		if ($user->delete()) {
			elgg_register_success_message(elgg_echo('admin:user:delete:yes', [$name]));
		} else {
			elgg_register_error_message(elgg_echo('entity:delete:fail', [$name]));
		}
	}
});

return elgg_ok_response();
