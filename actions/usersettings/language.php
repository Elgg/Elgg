<?php
/**
 * Set a user's language
 */

$language = get_input('language');
$user_guid = (int) get_input('guid');

if ($user_guid) {
	$user = get_user($user_guid);
} else {
	$user = elgg_get_logged_in_user_entity();
}

if (!$user || empty($language)) {
	return elgg_error_response(elgg_echo('user:language:fail'));
}

$user->language = $language;
if (!$user->save()) {
	return elgg_error_response(elgg_echo('user:language:fail'));
}

return elgg_ok_response('', elgg_echo('user:language:success'));
	