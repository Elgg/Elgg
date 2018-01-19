<?php
/**
 * Helper functions
 *
 * @package Elgg.Core.Plugin
 * @subpackage UserValidationByEmail
 */

/**
 * Request user validation email.
 * Send email out to the address and request a confirmation.
 *
 * @param int $user_guid The user's GUID
 * @return mixed
 */
function uservalidationbyemail_request_validation($user_guid) {

	$site = elgg_get_site_entity();

	$user_guid = (int) $user_guid;
	$user = get_user($user_guid);

	if ($user) {
		// Work out validate link
		$link = "{$site->url}uservalidationbyemail/confirm?u=$user_guid";
		$link = elgg_http_get_signed_url($link);
		
		// Get email to show in the next page
		elgg_get_session()->set('emailsent', $user->email);

		$subject = elgg_echo('email:validate:subject', [
				$user->getDisplayName(),
				$site->getDisplayName()
			], $user->language
		);

		$body = elgg_echo('email:validate:body', [
				$user->getDisplayName(),
				$site->getDisplayName(),
				$link,
				$site->getDisplayName(),
				$site->url
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

	return false;
}
