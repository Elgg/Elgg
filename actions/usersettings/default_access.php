<?php
/**
 * Set a user's default access level
 */
if (!elgg_get_config('allow_user_default_access')) {
	return elgg_error_response(elgg_echo('user:default_access:failure'));
}
$default_access = get_input('default_access');
$user_guid = (int) get_input('guid');
if ($user_guid) {
	$user = get_user($user_guid);
} else {
	$user = elgg_get_logged_in_user_entity();
}
if (!$user) {
	return elgg_error_response(elgg_echo('user:default_access:failure'));
}
if (!$user->setPrivateSetting('elgg_default_access', $default_access)) {
	return elgg_error_response(elgg_echo('user:default_access:failure'));
}
return elgg_ok_response('', elgg_echo('user:default_access:success'));