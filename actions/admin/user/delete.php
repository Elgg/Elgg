<?php
/**
 * Delete a user.
 *
 * The user will be deleted recursively, meaning all entities
 * owned or contained by the user will also be removed.
 */

// Get the user
$guid = (int) get_input('guid');
if ($guid === elgg_get_logged_in_user_guid()) {
	return elgg_error_response(elgg_echo('admin:user:self:delete:no'));
}

$user = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($guid) {
	return get_user($guid);
});
if (!$user || !$user->canDelete()) {
	return elgg_error_response(elgg_echo('admin:user:delete:no'));
}

$name = $user->getDisplayName();
$username = $user->username;

$deleted = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($user) {
	return $user->delete();
});
if (!$deleted) {
	return elgg_error_response(elgg_echo('admin:user:delete:no'));
}

// forward to user administration if on a user's page as it no longer exists
$forward = REFERER;
if (elgg_strpos($_SERVER['HTTP_REFERER'], $username) !== false) {
	$forward = 'admin/users/newest';
}

return elgg_ok_response('', elgg_echo('admin:user:delete:yes', [$name]), $forward);
