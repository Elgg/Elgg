<?php
/**
 * Helper functions
 */

/**
 * Request user validation email.
 * Send email out to the address and request a confirmation.
 *
 * @param int $user_guid The user's GUID
 * @return mixed
 */
function uservalidationbyemail_request_validation($user_guid) {

	$user_guid = (int) $user_guid;
	$user = get_user($user_guid);
	if (!$user instanceof ElggUser) {
		return false;
	}
	
	$validated = elgg_get_plugin_user_setting('email_validated', $user->guid, 'uservalidationbyemail');
	if (!isset($validated) || (bool) $validated) {
		// email address already validated, or not required by this plugin
		return true;
	}
	
	$site = elgg_get_site_entity();
	
	// Work out validate link
	$link = elgg_generate_url('account:validation:email:confirm', [
		'u' => $user->guid,
	]);
	$link = elgg_http_get_signed_url($link);
	
	// Get email to show in the next page
	elgg_get_session()->set('emailsent', $user->email);

	$subject = elgg_echo('email:validate:subject', [
			$user->getDisplayName(),
			$site->getDisplayName()
		], $user->language
	);

	$body = elgg_echo('email:validate:body', [
			$site->getDisplayName(),
			$link,
		], $user->language
	);

	$params = [
		'action' => 'uservalidationbyemail',
		'object' => $user,
		'link' => $link,
	];
	
	// Send validation email
	return notify_user($user->guid, $site->guid, $subject, $body, $params, 'email');
}
