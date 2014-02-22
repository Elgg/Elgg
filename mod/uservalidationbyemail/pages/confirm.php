<?php

$code = sanitise_string(get_input('c', FALSE));
$user_guid = get_input('u', FALSE);

// new users are not enabled by default.
$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$user = get_entity($user_guid);

if (!$code || !$user || !uservalidationbyemail_validate_email($user_guid, $code)) {
	register_error(elgg_echo('email:confirm:fail'));
	forward();
}

elgg_push_context('uservalidationbyemail_validate_user');
system_message(elgg_echo('email:confirm:success'));
$user = get_entity($user_guid);
$user->enable();
elgg_pop_context();

try {
	login($user);
} catch(LoginException $e){
	register_error($e->getMessage());
}

access_show_hidden_entities($access_status);

forward();