<?php
/**
 * Elgg registration action
 */

use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\Exceptions\Http\LoginException;

/* @var $request \Elgg\Request */

// Get variables
$username = (string) $request->getParam('username');
$password = (string) $request->getParam('password', null, false);
$password2 = (string) $request->getParam('password2', null, false);
$email = (string) $request->getParam('email');
$name = (string) $request->getParam('name');

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

	$new_user = elgg_register_user([
		'username' => $username,
		'password' => $password,
		'name' => $name,
		'email' => $email,
		'validated' => false,
	]);

	$fail = function () use ($new_user) {
		elgg_call(ELGG_IGNORE_ACCESS, function () use ($new_user) {
			$new_user->delete();
		});
	};

	try {
		// allow plugins to respond to self registration
		// note: To catch all new users, even those created by an admin,
		// register for the create:after, user event instead.
		// only passing vars that aren't in ElggUser.
		$params = $request->getParams();
		$params['user'] = $new_user;

		if (!elgg_trigger_event_results('register', 'user', $params, true)) {
			throw new RegistrationException(elgg_echo('registerbad'));
		}
		
		if ($new_user->isValidated() === null) {
			// no event decided to set validation status, so it will become validated
			$new_user->setValidationStatus(true, 'register_action');
		}
	} catch (\Exception $e) {
		// Catch all exception to make sure there are no incomplete user entities left behind
		$fail();
		throw $e;
	}

	$response_data = [
		'user' => $new_user,
	];

	if (!$new_user->isEnabled()) {
		// Plugins can alter forwarding URL by registering for 'response', 'action:register' event
		return elgg_ok_response($response_data);
	}

	try {
		elgg_login($new_user);
		// set forward url
		$forward_url = elgg_get_login_forward_url($new_user);
		$response_message = elgg_echo('registerok', [elgg_get_site_entity()->getDisplayName()]);

		return elgg_ok_response($response_data, $response_message, $forward_url);
	} catch (LoginException $e) {
		// if exception thrown, this probably means there is a validation
		// plugin that has disabled the user
		return elgg_error_response($e->getMessage(), REFERRER, $e->getCode() ?: ELGG_HTTP_UNAUTHORIZED);
	}
} catch (RegistrationException $r) {
	return elgg_error_response($r->getMessage(), REFERRER, $r->getCode() ?: ELGG_HTTP_BAD_REQUEST);
}
