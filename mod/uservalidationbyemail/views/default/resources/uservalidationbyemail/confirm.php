<?php

elgg_signed_request_gatekeeper();

$user_guid = get_input('u', FALSE);

// new users are not enabled by default.
$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$user = get_entity($user_guid);

if (!$user || !elgg_set_user_validation_status($user_guid, true, 'email')) {
	register_error(elgg_echo('email:confirm:fail'));
	forward();
}

system_message(elgg_echo('email:confirm:success'));

elgg_push_context('uservalidationbyemail_validate_user');
$user->enable();
elgg_pop_context();

try {
	login($user);
} catch(LoginException $e){
	register_error($e->getMessage());
}

access_show_hidden_entities($access_status);

forward();
