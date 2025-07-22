<?php
/**
 * Helper functions
 */

/**
 * Request user validation email.
 * Send email out to the address and request a confirmation.
 *
 * @param int $user_guid The user's GUID
 * @return array|bool
 */
function uservalidationbyemail_request_validation(int $user_guid): array|bool {
	$user = get_user($user_guid);
	if (!$user instanceof \ElggUser) {
		return false;
	}
	
	$validated = elgg_get_plugin_user_setting('email_validated', $user->guid, 'uservalidationbyemail');
	if (!isset($validated) || (bool) $validated) {
		// email address already validated, or not required by this plugin
		return true;
	}
	
	// Get email to show in the next page
	elgg_get_session()->set('emailsent', $user->email);
	
	return $user->notify('uservalidationbyemail', $user);
}
