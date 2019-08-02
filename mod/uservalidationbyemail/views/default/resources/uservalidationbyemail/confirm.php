<?php

// new users are not enabled by default.
return elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() {
	$user_guid = get_input('u', false);
	
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
	
	return elgg_ok_response('', elgg_echo('email:confirm:success'));
});
