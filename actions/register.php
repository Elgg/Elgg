<?php
/**
 * Elgg registration action
 */

use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\Exceptions\LoginException;

/* @var $request \Elgg\Request */

elgg_make_sticky_form('register');

if (!elgg_get_config('allow_registration')) {
	return elgg_error_response(elgg_echo('registerdisabled'));
}

// Get variables
$username = $request->getParam('username');
$password = $request->getParam('password', null, false);
$password2 = $request->getParam('password2', null, false);
$email = $request->getParam('email');
$name = $request->getParam('name');

$username = trim($username);
$name = trim(strip_tags($name));
$email = trim($email);

try {
	$validation = elgg_validate_registration_data($username, [$password, $password2], $name, $email);
	$failures = $validation->getFailures();
	if ($failures) {
		$messages = array_map(function (\Elgg\Validation\ValidationResult $e) {
			return $e->getError();
		}, $failures);

		throw new RegistrationException(implode(PHP_EOL, $messages));
	}

	$guid = register_user($username, $password, $name, $email, false, null, ['validated' => false]);
	if ($guid === false) {
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
		$params = $request->getParams();
		$params['user'] = $new_user;

		if (!elgg_trigger_plugin_hook('register', 'user', $params, true)) {
			throw new RegistrationException(elgg_echo('registerbad'));
		}
		
		if ($new_user->isValidated() === null) {
			// no hook decided to set validation status, so it will become validated
			$new_user->setValidationStatus(true, 'register_action');
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

	try {
		login($new_user);
		// set forward url
		$forward_url = _elgg_get_login_forward_url($request, $new_user);
		$response_message = elgg_echo('registerok', [elgg_get_site_entity()->getDisplayName()]);

		return elgg_ok_response($response_data, $response_message, $forward_url);
	} catch (LoginException $e) {
		// if exception thrown, this probably means there is a validation
		// plugin that has disabled the user
		return elgg_error_response($e->getMessage(), REFERRER, $e->getCode() ? : ELGG_HTTP_UNAUTHORIZED);
	}
} catch (RegistrationException $r) {
	return elgg_error_response($r->getMessage(), REFERRER, $r->getCode() ? : ELGG_HTTP_BAD_REQUEST);
}
