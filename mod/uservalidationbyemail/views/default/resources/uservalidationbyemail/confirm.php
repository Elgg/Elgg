<?php

// new users are not enabled by default.
return elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() {
	$user_guid = get_input('u', false);
	
	$user = get_user($user_guid);
	if (!$user) {
		return elgg_error_response(elgg_echo('email:confirm:fail'));
	}
	
	elgg_call(ELGG_IGNORE_ACCESS, function() use (&$user) {
		// set validation flag
		elgg_set_plugin_user_setting('email_validated', true, $user->guid, 'uservalidationbyemail');
		
		if (!(bool) elgg_get_config('require_admin_validation')) {
			// user doesn't have to be validated by admin after this
			$user->setValidationStatus(true, 'email');
		}
	});
	
	try {
		login($user);
	} catch (LoginException $e) {
		return elgg_error_response($e->getMessage());
	}
	
	return elgg_ok_response('', elgg_echo('email:confirm:success'));
});
