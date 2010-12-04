<?php
/**
 * Elgg add action
 *
 * @package Elgg
 * @subpackage Core
 */

// Get variables
global $CONFIG;
$username = get_input('username');
$password = get_input('password');
$password2 = get_input('password2');
$email = get_input('email');
$name = get_input('name');

$admin = get_input('admin');
if (is_array($admin)) {
	$admin = $admin[0];
}

// For now, just try and register the user
try {
	$guid = register_user($username, $password, $name, $email, TRUE);

	if (((trim($password) != "") && (strcmp($password, $password2) == 0)) && ($guid)) {
		$new_user = get_entity($guid);
		if (($guid) && ($admin)) {
			$new_user->makeAdmin();
		}

		$new_user->admin_created = TRUE;
		$new_user->created_by_guid = get_loggedin_userid();

		$subject = elgg_echo('useradd:subject');
		$body = elgg_echo('useradd:body', array($name,
			$CONFIG->site->name, $CONFIG->site->url, $username, $password));

		notify_user($new_user->guid, $CONFIG->site->guid, $subject, $body);

		system_message(elgg_echo("adduser:ok", array($CONFIG->sitename)));
	} else {
		register_error(elgg_echo("adduser:bad"));
	}
} catch (RegistrationException $r) {
	register_error($r->getMessage());
}

forward(REFERER);
