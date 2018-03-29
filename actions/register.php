<?php
/**
 * Elgg registration action
 */

elgg_make_sticky_form('register');

if (!elgg_get_config('allow_registration')) {
	return elgg_error_response(elgg_echo('registerdisabled'));
}

// Get variables
$username = get_input('username');
$password = get_input('password', null, false);
$password2 = get_input('password2', null, false);
$email = get_input('email');
$name = get_input('name');
$friend_guid = (int) get_input('friend_guid', 0);
$invitecode = get_input('invitecode');

try {
	if (trim($password) == "" || trim($password2) == "") {
		throw new RegistrationException(elgg_echo('RegistrationException:EmptyPassword'));
	}

	if (strcmp($password, $password2) != 0) {
		throw new RegistrationException(elgg_echo('RegistrationException:PasswordMismatch'));
	}

	$guid = register_user($username, $password, $name, $email);
	if (!$guid) {
		throw new RegistrationException(elgg_echo('registerbad'));
	}

	$new_user = get_user($guid);

	$fail = function () use ($new_user) {
		elgg_call(ELGG_IGNORE_ACCESS, function () use ($new_user) {
			$new_user->delete();
		});
	};

	try {
		// allow plugins to respond to self registration
		// note: To catch all new users, even those created by an admin,
		// register for the create, user event instead.
		// only passing vars that aren't in ElggUser.
		$params = [
			'user' => $new_user,
			'password' => $password,
			'friend_guid' => $friend_guid,
			'invitecode' => $invitecode
		];

		if (!elgg_trigger_plugin_hook('register', 'user', $params, true)) {
			throw new RegistrationException(elgg_echo('registerbad'));
		}
	} catch (\Exception $e) {
		// Catch all exception to make sure there are no incomplete user entities left behind
		$fail();
		throw $e;
	}

	elgg_clear_sticky_form('register');

	$response_data = [
		'user' => $new_user,
	];

	if (!$new_user->isEnabled()) {
		// Plugins can alter forwarding URL by registering for 'response', 'action:register' hook
		return elgg_ok_response($response_data);
	}

	// if exception thrown, this probably means there is a validation
	// plugin that has disabled the user
	try {
		login($new_user);

		// set forward url
		$session = elgg_get_session();
		if ($session->has('last_forward_from')) {
			$forward_url = $session->get('last_forward_from');
			$forward_source = 'last_forward_from';
		} else {
			// forward to main index page
			$forward_url = '';
			$forward_source = null;
		}
		$params = [
			'user' => $new_user,
			'source' => $forward_source,
		];

		$forward_url = elgg_trigger_plugin_hook('login:forward', 'user', $params, $forward_url);
		$response_message = elgg_echo('registerok', [elgg_get_site_entity()->getDisplayName()]);

		return elgg_ok_response($response_data, $response_message, $forward_url);
	} catch (LoginException $e) {
		return elgg_error_response($e->getMessage());
	}
} catch (RegistrationException $r) {
	return elgg_error_response($r->getMessage());
}
