<?php
/**
 * Validate a user
 */

$user_guid = (int) get_input('user_guid');
if (empty($user_guid)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$hidden = access_show_hidden_entities(true);

$user = get_user($user_guid);
if (empty($user)) {
	access_show_hidden_entities($hidden);
	
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if ($user->isValidated()) {
	// already validated
	access_show_hidden_entities($hidden);
	
	return elgg_ok_response('', elgg_echo('action:user:validate:already', [$user->getDisplayName()]));
}

$user->setValidationStatus(true, 'manual');

if (!$user->isValidated()) {
	access_show_hidden_entities($hidden);
	
	return elgg_error_response(elgg_echo('action:user:validate:error', [$user->getDisplayName()]));
}

access_show_hidden_entities($hidden);

return elgg_ok_response('', elgg_echo('action:user:validate:success', [$user->getDisplayName()]));
