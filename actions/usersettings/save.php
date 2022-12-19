<?php
/**
 * Aggregate action for saving settings
 *
 * To see the individual action methods, enable the developers plugin, visit Admin > Inspect > Events
 * and search for "usersettings:save".
 */

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Exceptions\Http\EntityPermissionsException;

/* @var $request \Elgg\Request */

$guid = $request->getParam('guid');
if (isset($guid)) {
	$user = get_user($guid);
} else {
	$user = elgg_get_logged_in_user_entity();
}

if (!$user instanceof ElggUser) {
	throw new EntityNotFoundException();
}

if (!$user->canEdit()) {
	throw new EntityPermissionsException();
}

elgg_make_sticky_form('usersettings', ['password', 'password2']);

$event_params = [
	'user' => $user,
	'request' => $request,
];

// callbacks should return false to indicate that the sticky form should not be cleared
if (elgg_trigger_event_results('usersettings:save', 'user', $event_params, true)) {
	elgg_clear_sticky_form('usersettings');
}

foreach ($request->validation()->all() as $item) {
	if ($item->isValid()) {
		$message = $item->getMessage();
		if (!elgg_is_empty($message)) {
			elgg_register_success_message($message);
		}
	} else {
		$error = $item->getError();
		if (!elgg_is_empty($error)) {
			elgg_register_error_message($error);
		}
	}
}

return elgg_ok_response([
	'user' => $user,
	'validation' => $request->validation(),
]);
