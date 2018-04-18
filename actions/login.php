<?php
/**
 * Elgg login action
 */

/* @var $request \Elgg\Request */

$username = get_input('username');
$password = get_input('password', null, false);
$persistent = (bool) get_input("persistent");
$result = false;

if (empty($username) || empty($password)) {
	return elgg_error_response(elgg_echo('login:empty'), REFERRER, ELGG_HTTP_BAD_REQUEST);
}

// check if logging in with email address
if (strpos($username, '@') !== false && ($users = get_user_by_email($username))) {
	$username = $users[0]->username;
}

$user = get_user_by_username($username);

try {
	$result = elgg_authenticate($username, $password);
	if ($result !== true) {
		// was due to missing hash?
		if ($user && !$user->password_hash) {
			// if we did this in pam_auth_userpass(), visitors could sniff account usernames from
			// email addresses. Instead, this lets us give the visitor only the information
			// they provided.
			elgg_get_session()->set('forgotpassword:hash_missing', get_input('username'));
			$output = [
				'forward' => elgg_generate_url('account:password:reset'),
			];
			return elgg_ok_response($output, '', elgg_generate_url('account:password:reset'));
		}

		throw new LoginException($result);
	}

	if (!$user) {
		throw new LoginException(elgg_echo('login:baduser'));
	}

	login($user, $persistent);
} catch (LoginException $e) {
	return elgg_error_response($e->getMessage(), REFERRER, ELGG_HTTP_UNAUTHORIZED);
}

if ($request->isXhr()) {
	// Hold the system messages until the client refreshes the page.
	$request->setParam('elgg_fetch_messages', 0);
}

$output = [
	'user' => $user,
];
$message = elgg_echo('loginok', [], $user->getLanguage(get_current_language()));
$forward_url = _elgg_get_login_forward_url($request, $user);

return elgg_ok_response($output, $message, $forward_url);
