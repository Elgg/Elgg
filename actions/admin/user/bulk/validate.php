<?php
/**
 * Bulk validate users
 */

$user_guids = (array) get_input('user_guids');
if (empty($user_guids)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$hidden = access_show_hidden_entities(true);

foreach ($user_guids as $user_guid) {
	$user = get_user($user_guid);
	if (empty($user)) {
		continue;
	}
	
	if ($user->isValidated()) {
		continue;
	}
	
	$user->setValidationStatus(true, 'manual');
	
	if (!$user->isValidated()) {
		register_error(elgg_echo('action:user:validate:error', [$user->getDisplayName()]));
		continue;
	}
	
	system_message(elgg_echo('action:user:validate:success', [$user->getDisplayName()]));
}

access_show_hidden_entities($hidden);

return elgg_ok_response();
