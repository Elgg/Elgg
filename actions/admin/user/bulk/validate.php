<?php
/**
 * Bulk validate users
 */

$user_guids = (array) get_input('user_guids');
if (empty($user_guids)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($user_guids) {
	$users = elgg_get_entities([
		'type' => 'user',
		'guids' => $user_guids,
		'limit' => false,
	]);
	/* @var $user \ElggUser */
	foreach ($users as $user) {
		if ($user->isValidated()) {
			continue;
		}
		
		$user->setValidationStatus(true, 'manual');
		
		if ($user->isValidated() !== true) {
			register_error(elgg_echo('action:user:validate:error', [$user->getDisplayName()]));
			continue;
		}
		
		system_message(elgg_echo('action:user:validate:success', [$user->getDisplayName()]));
	}
});

return elgg_ok_response();
