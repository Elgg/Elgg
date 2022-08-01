<?php
/**
 * Elgg login action
 */

use Elgg\Exceptions\AuthenticationException;
use Elgg\Exceptions\LoginException;

$username = get_input('username');
$password = get_input('password', null, false);
$persistent = (bool) get_input("persistent");
$result = false;

if (empty($username) || empty($password)) {
	return elgg_error_response(elgg_echo('login:empty'), REFERRER, ELGG_HTTP_BAD_REQUEST);
}

// check if logging in with email address
if (elgg_strpos($username, '@') !== false) {
	$users = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($username) {
		return get_user_by_email($username);
	});
	
	if (!empty($users)) {
		$username = $users[0]->username;
	}
}

// fetch the user (even disabled)
$user = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function () use ($username) {
	return get_user_by_username($username);
});

try {
	// try to authenticate
	$result = elgg_pam_authenticate('user', [
		'username' => $username,
		'password' => $password,
	]);
	if ($result !== true) {
		// was due to missing hash?
		if ($user && !$user->password_hash) {
			// if we did this in user password PAM handler, visitors could sniff account usernames from
			// email addresses. Instead, this lets us give the visitor only the information
			// they provided.
			elgg_get_session()->set('forgotpassword:hash_missing', get_input('username'));
			$output = [
				'forward' => elgg_generate_url('account:password:reset'),
			];
			return elgg_ok_response($output, '', elgg_generate_url('account:password:reset'));
		}

		throw new LoginException(elgg_echo('LoginException:Unknown'));
	}

	if (!$user) {
		throw new LoginException(elgg_echo('login:baduser'));
	}

	elgg_login($user, $persistent);
} catch (AuthenticationException | LoginException $e) {
	$prev = $e->getPrevious();
	
	$forward = null;
	if ($prev instanceof LoginException) {
		$forward = $prev->getRedirectUrl();
	} elseif ($e instanceof LoginException) {
		$forward = $e->getRedirectUrl();
	}
	
	// if a forward url is set we need to use a ok response.
	// The login action is mostly used as an AJAX action and AJAX actions do not support redirects.
	if (!empty($forward)) {
		// Registering an error as we use an OK response
		// It makes no sense for AJAX actions as a OK response with a forward will instantly redirect without time to read the message
		$error = $e->getMessage();
		if (!empty($error) && !elgg_is_xhr()) {
			elgg_register_error_message($error);
		}
		
		return elgg_ok_response('', '', $forward);
	}
	
	return elgg_error_response($e->getMessage(), REFERRER, ELGG_HTTP_UNAUTHORIZED);
}

if (elgg_is_xhr()) {
	// Hold the system messages until the client refreshes the page.
	set_input('elgg_fetch_messages', 0);
}

$output = [
	'user' => $user,
];
$message = elgg_echo('loginok', [], $user->getLanguage(elgg_get_current_language()));

return elgg_ok_response($output, $message, elgg_get_login_forward_url($user));
