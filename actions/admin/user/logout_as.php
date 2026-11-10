<?php
/**
 * Logout as the current user, back to the original user.
 */

use Elgg\Exceptions\Http\LoginException;

$session = elgg_get_session();
$current_user = elgg_get_logged_in_user_entity();

$user_guid = (int) $session->get('login_as_original_user_guid');
$hash = (string) $session->get('login_as_hash');

$new_hash = elgg_build_hmac([
	$user_guid,
	$current_user->guid,
]);
if (!$new_hash->matchesToken($hash)) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

$user = get_user($user_guid);
if (!$user instanceof \ElggUser || !$user->isAdmin()) {
	return elgg_error_response(elgg_echo('action:user:login_as:unknown'));
}

$persistent = (bool) $session->get('login_as_original_persistent');

try {
	elgg_login($user, $persistent);
	
	$session->remove('login_as_original_user_guid');
	$session->remove('login_as_original_persistent');
	$session->remove('login_as_hash');
	
	return elgg_ok_response('', elgg_echo('action:user:login_as:success', [$user->getDisplayName()]));
} catch (LoginException $e) {
	return elgg_error_response(elgg_echo('action:user:login_as:error', [$user->getDisplayName()]));
}
