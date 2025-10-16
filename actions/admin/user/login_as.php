<?php
/**
 * Login as the specified user
 *
 * Sets a flag in the session to let us know who the originally logged in user is.
 */

use Elgg\Exceptions\Http\LoginException;

$user_guid = (int) get_input('user_guid');

$user = get_user($user_guid);
if (!$user instanceof \ElggUser) {
	return elgg_error_response(elgg_echo('action:user:login_as:unknown'));
}

$original_user = elgg_get_logged_in_user_entity();
$original_user_guid = $original_user->guid;

// store the original persistent login state to restore on logout_as.
$persistent = false;
if (isset($_COOKIE['elggperm'])) {
	$original_perm_user = elgg_get_user_by_persistent_token($_COOKIE['elggperm']);
	if ($original_perm_user instanceof \ElggUser && $original_user->guid === $original_perm_user->guid) {
		$persistent = true;
	}
}

$session = elgg_get_session();
$session->set('login_as_original_user_guid', $original_user_guid);
$session->set('login_as_original_persistent', $persistent);

try {
	elgg_login($user);
	
	return elgg_ok_response('', elgg_echo('action:user:login_as:success', [$user->getDisplayName()]));
} catch (LoginException $exc) {
	$session->remove('login_as_original_user_guid');
	$session->remove('login_as_original_persistent');
	
	try {
		elgg_login($original_user);
	} catch (LoginException $ex) {
		// we can't log back in as ourselves? just leave us logged out then...
	}
	
	return elgg_error_response(elgg_echo('action:user:login_as:error', [$user->getDisplayName()]));
}
