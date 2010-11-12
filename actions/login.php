<?php
/**
 * Elgg login action
 *
 * @package Elgg.Core
 * @subpackage User.Authentication
 */

$username = get_input('username');
$password = get_input("password");
$persistent = get_input("persistent", FALSE);
$result = FALSE;

if (empty($username) || empty($password)) {
	register_error(elgg_echo('loginerror'));
	forward();
}

// check first if logging in with email address
if (strpos($username, '@') !== FALSE && ($users = get_user_by_email($username))) {
	$username = $users[0]->username;
}

if ($user = authenticate($username, $password)) {
	$result = login($user, $persistent);
}

// forward to correct page
if ($result) {
	system_message(elgg_echo('loginok'));

	if (isset($_SESSION['last_forward_from']) && $_SESSION['last_forward_from']) {
		$forward_url = $_SESSION['last_forward_from'];
		unset($_SESSION['last_forward_from']);

		forward($forward_url);
	} else {
		if (get_input('returntoreferer')) {
			forward(REFERER);
		} else {
			// forward to index for front page overrides.
			// index will forward to dashboard if appropriate.
			forward('index.php');
		}
	}
} else {
	register_error(elgg_echo('loginerror'));
	//	// let a plugin hook say why login failed or react to it.
	//	$params = array(
	//		'username' => $username,
	//		'password' => $password,
	//		'persistent' => $persistent,
	//		'user' => $user
	//	);
	//
	//	// Returning FALSE to this function will generate a standard
	//	// "Could not log you in" message.
	//	// Plugins should use this hook to provide details, and then return TRUE.
	//	if (!elgg_trigger_plugin_hook('failed_login', 'user', $params, FALSE)) {
	//		register_error(elgg_echo('loginerror'));
	//	}
}

forward(REFERRER);
