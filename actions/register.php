<?php
/**
 * Elgg registration action
 *
 * @package Elgg.Core
 * @subpackage User.Account
 */

global $CONFIG;

// Get variables
$username = get_input('username');
$password = get_input('password');
$password2 = get_input('password2');
$email = get_input('email');
$name = get_input('name');
$friend_guid = (int) get_input('friend_guid',0);
$invitecode = get_input('invitecode');

if ($CONFIG->allow_registration) {
	try {
		if (trim($password) == "" || trim($password2) == "") {
			throw new RegistrationException(elgg_echo('RegistrationException:EmptyPassword'));
		}

		if (strcmp($password, $password2) != 0) {
			throw new RegistrationException(elgg_echo('RegistrationException:PasswordMismatch'));
		}

		$guid = register_user($username, $password, $name, $email, false, $friend_guid, $invitecode);

		if ($guid) {
			$new_user = get_entity($guid);

			// allow plugins to respond to self registration
			// note: To catch all new users, even those created by an admin,
			// register for the create, user event instead.
			// only passing vars that aren't in ElggUser.
			$params = array(
				'user' => $new_user,
				'password' => $password,
				'friend_guid' => $friend_guid,
				'invitecode' => $invitecode
			);

			// if this user is admin, that means it was the first
			// registered user.  Don't trigger this hook.
			// @todo This can be removed in the new installer
			if (!$new_user->isAdmin()) {
				// @todo should registration be allowed no matter what the plugins return?
				if (!trigger_plugin_hook('register', 'user', $params, TRUE)) {
					$new_user->delete();
					// @todo this is a generic messages. We could have plugins
					// throw a RegistrationException, but that is very odd
					// for the plugin hooks system.
					throw new RegistrationException(elgg_echo('registerbad'));
				}
			}

			system_message(sprintf(elgg_echo("registerok"), $CONFIG->sitename));

			// Forward on success, assume everything else is an error...
			// If just registered admin user, login the user in and forward to the
			// plugins simple settings page.
			if (!datalist_get('first_admin_login') && $new_user->isAdmin()) {
				login($new_user);
				// remove the "you've registered!" system_message();
				$_SESSION['msg']['messages'] = array();

				// remind users to enable / disable desired tools
				elgg_add_admin_notice('first_installation_plugin_reminder', elgg_echo('firstadminlogininstructions'));

				datalist_set('first_admin_login', time());
				forward('pg/admin/plugins/simple');
			} else {
				login($new_user);
				forward();
			}
		} else {
			register_error(elgg_echo("registerbad"));
		}
	} catch (RegistrationException $r) {
		register_error($r->getMessage());
	}
} else {
	register_error(elgg_echo('registerdisabled'));
}

forward(REFERER);