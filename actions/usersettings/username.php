<?php
/**
 * Set a user's username
 */
$username = get_input('username');
$user_guid = (int) get_input('guid');
$user = get_user($user_guid);
if (!isset($username) || !$user) {
	return elgg_error_response(elgg_echo('user:username:fail'));
}
if ($user->username === $username) {
	return elgg_ok_response('', elgg_echo('user:username:success'));
}
// check if username is valid and does not exist
try {
	if (validate_username($username)) {
		$found = false;
		// make sure we can check every user (even unvalidated)
		$hidden = access_show_hidden_entities(true);
		if (get_user_by_username($username)) {
			$found = true;
		}
		// restore access settings
		access_show_hidden_entities($hidden);
		
		if ($found) {
			return elgg_error_response(elgg_echo('registration:userexists'));
		}
	}
} catch (Exception $e) {
	return elgg_error_response($e->getMessage());
}
$user->username = $username;
if (!$user->save()) {
	return elgg_error_response(elgg_echo('user:username:fail'));
}
// correctly forward after after a username change
elgg_register_plugin_hook_handler('forward', 'all', function() use ($username) {
	return "settings/user/$username";
});
return elgg_ok_response('', elgg_echo('user:username:success'));