<?php
/**
 * Action to request a new password.
 */

$username = get_input('username');
if (empty($username)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

// also allow by email addresses
$user = elgg_get_user_by_username($username, true);
if ($user instanceof \ElggUser) {
	// generate code
	$user->passwd_conf_code = elgg_generate_password();
	$user->passwd_conf_time = time();

	$user->notify('requestnewpassword', $user, [
		'ip_address' => _elgg_services()->request->getClientIp(),
	]);
}

// always report success, do not want to inform requester about account existence
return elgg_ok_response('', elgg_echo('user:password:changereq:success'), '');
