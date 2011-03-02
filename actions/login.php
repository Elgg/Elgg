<?php
/**
 * Elgg login action
 *
 * @package Elgg.Core
 * @subpackage User.Authentication
 */

// set forward url
if (isset($_SESSION['last_forward_from']) && $_SESSION['last_forward_from']) {
	$forward_url = $_SESSION['last_forward_from'];
	unset($_SESSION['last_forward_from']);
} elseif (get_input('returntoreferer')) {
	$forward_url = REFERER;
} else {
	// forward to main index page
	$forward_url = '';
}

$username = get_input('username');
$password = get_input("password");
$persistent = get_input("persistent", FALSE);
$result = FALSE;

if (empty($username) || empty($password)) {
	register_error(elgg_echo('login:empty'));
	forward();
}

// check if logging in with email address
// @todo Are usernames with @ not allowed?
if (strpos($username, '@') !== FALSE && ($users = get_user_by_email($username))) {
	$username = $users[0]->username;
}

$result = elgg_authenticate($username, $password);
if ($result !== true) {
	register_error($result);
	forward(REFERER);
}

$user = get_user_by_username($username);
if (!$user) {
	register_error(elgg_echo('login:baduser'));
	forward(REFERER);
}

try {
	login($user, $persistent);
} catch (LoginException $e) {
	register_error($e->getMessage());
	forward(REFERER);
}

system_message(elgg_echo('loginok'));
forward($forward_url);
