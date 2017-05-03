<?php
/**
 * Set a user's display name
 */
$name = get_input('name');
$user_guid = (int) get_input('guid');
$name = strip_tags($name);
if ($user_guid) {
	$user = get_user($user_guid);
} else {
	$user = elgg_get_logged_in_user_entity();
}
if (elgg_strlen($name) > 50) {
	return elgg_error_response(elgg_echo('user:name:fail'));
}
if (!$user || !$user->canEdit() || !$name) {
	return elgg_error_response(elgg_echo('user:name:fail'));
}
$user->name = $name;
if (!$user->save()) {
	return elgg_error_response(elgg_echo('user:name:fail'));
}
return elgg_ok_response('', elgg_echo('user:name:success'));