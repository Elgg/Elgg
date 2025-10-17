<?php
/**
 * Logout as the current user, back to the original user.
 */

use Elgg\Exceptions\Http\LoginException;

$session = elgg_get_session();

$user_guid = (int) $session->get('login_as_original_user_guid');

$user = get_user($user_guid);
if (!$user instanceof \ElggUser || !$user->isAdmin()) {
	return elgg_error_response(elgg_echo('action:user:login_as:unknown'));
}

$persistent = (bool) $session->get('login_as_original_persistent');

try {
	elgg_login($user, $persistent);
	
	$session->remove('login_as_original_user_guid');
	$session->remove('login_as_original_persistent');
	
	return elgg_ok_response('', elgg_echo('action:user:login_as:success', [$user->getDisplayName()]));
} catch (LoginException $e) {
	return elgg_error_response(elgg_echo('action:user:login_as:error', [$user->getDisplayName()]));
}
