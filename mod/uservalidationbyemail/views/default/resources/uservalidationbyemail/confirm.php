<?php

elgg_signed_request_gatekeeper();

$user_guid = get_input('u', false);

// new users are not enabled by default.
$access_status = access_show_hidden_entities(true);

$user = get_user($user_guid);
if (!$user) {
	return elgg_error_response(elgg_echo('email:confirm:fail'));
}

$user->setValidationStatus(true, 'email');

elgg_push_context('uservalidationbyemail_validate_user');
$user->enable();
elgg_pop_context();

try {
	login($user);
} catch (LoginException $e) {
	return elgg_error_response($e->getMessage());
}

access_show_hidden_entities($access_status);

return elgg_ok_response('', elgg_echo('email:confirm:success'));
