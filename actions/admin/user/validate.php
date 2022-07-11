<?php
/**
 * Validate a user
 */

$user_guid = (int) get_input('user_guid');
if (empty($user_guid)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

return elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($user_guid) {
	$user = get_user($user_guid);
	if (empty($user)) {
		return elgg_error_response(elgg_echo('error:missing_data'));
	}
	
	if ($user->isValidated()) {
		// already validated
		return elgg_ok_response('', elgg_echo('action:user:validate:already', [$user->getDisplayName()]));
	}
	
	$user->setValidationStatus(true, 'manual');
	
	if ($user->isValidated() !== true) {
		return elgg_error_response(elgg_echo('action:user:validate:error', [$user->getDisplayName()]));
	}
	
	// inform user about validation
	$site = elgg_get_site_entity();
	$subject = elgg_echo('user:notification:validate:subject', [$site->getDisplayName()], $user->getLanguage());
	$body = elgg_echo('user:notification:validate:body', [
		$site->getDisplayName(),
		$site->getURL(),
	], $user->getLanguage());
	
	$params = [
		'action' => 'validate',
		'object' => $user,
		'apply_muting' => false,
	];
	
	notify_user($user->guid, $site->guid, $subject, $body, $params, 'email');
	
	return elgg_ok_response('', elgg_echo('action:user:validate:success', [$user->getDisplayName()]));
});
