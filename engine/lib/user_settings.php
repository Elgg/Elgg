<?php
/**
 * Elgg user settings functions.
 * Functions for adding and manipulating options on the user settings panel.
 *
 * @package Elgg.Core
 * @subpackage Settings.User
 */

/**
 * Saves user settings.
 *
 * @todo this assumes settings are coming in on a GET/POST request
 *
 * @note This is a handler for the 'usersettings:save', 'user' plugin hook
 *
 * @return void
 */
function users_settings_save() {
	elgg_set_user_language();
	elgg_set_user_password();
	elgg_set_user_default_access();
	elgg_set_user_name();
	elgg_set_user_email();
}

/**
 * Set a user's password
 * 
 * @return bool
 * @since 1.8.0
 */
function elgg_set_user_password() {
	$current_password = get_input('current_password');
	$password = get_input('password');
	$password2 = get_input('password2');
	$user_id = get_input('guid');

	if (!$user_id) {
		$user = elgg_get_logged_in_user_entity();
	} else {
		$user = get_entity($user_id);
	}

	if (($user) && ($password != "")) {
		// let admin user change anyone's password without knowing it except his own.
		if (!elgg_is_admin_logged_in() || elgg_is_admin_logged_in() && $user->guid == elgg_get_logged_in_user_guid()) {
			$credentials = array(
				'username' => $user->username,
				'password' => $current_password
			);

			if (!pam_auth_userpass($credentials)) {
				register_error(elgg_echo('user:password:fail:incorrect_current_password'));
				return false;
			}
		}

		if (strlen($password) >= 4) {
			if ($password == $password2) {
				$user->salt = generate_random_cleartext_password(); // Reset the salt
				$user->password = generate_user_password($user, $password);
				if ($user->save()) {
					system_message(elgg_echo('user:password:success'));
					return true;
				} else {
					register_error(elgg_echo('user:password:fail'));
				}
			} else {
				register_error(elgg_echo('user:password:fail:notsame'));
			}
		} else {
			register_error(elgg_echo('user:password:fail:tooshort'));
		}
	} else {
		// no change
		return null;
	}
	return false;
}

/**
 * Set a user's display name
 * 
 * @return bool
 * @since 1.8.0
 */
function elgg_set_user_name() {
	$name = strip_tags(get_input('name'));
	$user_id = get_input('guid');

	if (!$user_id) {
		$user = elgg_get_logged_in_user_entity();
	} else {
		$user = get_entity($user_id);
	}

	if (elgg_strlen($name) > 50) {
		register_error(elgg_echo('user:name:fail'));
		return false;
	}

	if (($user) && ($user->canEdit()) && ($name)) {
		if ($name != $user->name) {
			$user->name = $name;
			if ($user->save()) {
				system_message(elgg_echo('user:name:success'));
				return true;
			} else {
				register_error(elgg_echo('user:name:fail'));
			}
		} else {
			// no change
			return null;
		}
	} else {
		register_error(elgg_echo('user:name:fail'));
	}
	return false;
}

/**
 * Set a user's language
 * 
 * @return bool
 * @since 1.8.0
 */
function elgg_set_user_language() {
	$language = get_input('language');
	$user_id = get_input('guid');

	if (!$user_id) {
		$user = elgg_get_logged_in_user_entity();
	} else {
		$user = get_entity($user_id);
	}

	if (($user) && ($language)) {
		if (strcmp($language, $user->language) != 0) {
			$user->language = $language;
			if ($user->save()) {
				system_message(elgg_echo('user:language:success'));
				return true;
			} else {
				register_error(elgg_echo('user:language:fail'));
			}
		} else {
			// no change
			return null;
		}
	} else {
		register_error(elgg_echo('user:language:fail'));
	}
	return false;
}

/**
 * Set a user's email address
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_set_user_email() {
	$email = get_input('email');
	$user_id = get_input('guid');

	if (!$user_id) {
		$user = elgg_get_logged_in_user_entity();
	} else {
		$user = get_entity($user_id);
	}

	if (!is_email_address($email)) {
		register_error(elgg_echo('email:save:fail'));
		return false;
	}

	if ($user) {
		if (strcmp($email, $user->email) != 0) {
			if (!get_user_by_email($email)) {
				if ($user->email != $email) {

					$user->email = $email;
					if ($user->save()) {
						system_message(elgg_echo('email:save:success'));
						return true;
					} else {
						register_error(elgg_echo('email:save:fail'));
					}
				}
			} else {
				register_error(elgg_echo('registration:dupeemail'));
			}
		} else {
			// no change
			return null;
		}
	} else {
		register_error(elgg_echo('email:save:fail'));
	}
	return false;
}

/**
 * Set a user's default access level
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_set_user_default_access() {

	if (!elgg_get_config('allow_user_default_access')) {
		return false;
	}

	$default_access = get_input('default_access');
	$user_id = get_input('guid');

	if (!$user_id) {
		$user = elgg_get_logged_in_user_entity();
	} else {
		$user = get_entity($user_id);
	}

	if ($user) {
		$current_default_access = $user->getPrivateSetting('elgg_default_access');
		if ($default_access !== $current_default_access) {
			if ($user->setPrivateSetting('elgg_default_access', $default_access)) {
				system_message(elgg_echo('user:default_access:success'));
				return true;
			} else {
				register_error(elgg_echo('user:default_access:fail'));
			}
		} else {
			// no change
			return null;
		}
	} else {
		register_error(elgg_echo('user:default_access:fail'));
	}

	return false;
}

/**
 * Set up the menu for user settings
 *
 * @return void
 */
function usersettings_pagesetup() {
	if (elgg_get_context() == "settings" && elgg_get_logged_in_user_guid()) {
		$user = elgg_get_logged_in_user_entity();

		$params = array(
			'name' => '1_account',
			'text' => elgg_echo('usersettings:user:opt:linktext'),
			'href' => "settings/user/{$user->username}",
		);
		elgg_register_menu_item('page', $params);
		$params = array(
			'name' => '1_plugins',
			'text' => elgg_echo('usersettings:plugins:opt:linktext'),
			'href' => "settings/plugins/{$user->username}",
		);
		elgg_register_menu_item('page', $params);
		$params = array(
			'name' => '1_statistics',
			'text' => elgg_echo('usersettings:statistics:opt:linktext'),
			'href' => "settings/statistics/{$user->username}",
		);
		elgg_register_menu_item('page', $params);
	}
}

/**
 * Page handler for user settings
 *
 * @param array $page Pages array
 *
 * @return void
 */
function usersettings_page_handler($page) {
	global $CONFIG;

	if (!isset($page[0])) {
		$page[0] = 'user';
	}

	if ($page[1]) {
		$user = get_user_by_username($page[1]);
		elgg_set_page_owner_guid($user->guid);
	} else {
		$user = elgg_get_logged_in_user_guid();
		elgg_set_page_owner_guid($user->guid);
	}

	elgg_push_breadcrumb(elgg_echo('settings'), "settings/user/$user->username");

	switch ($page[0]) {
		case 'statistics':
			elgg_push_breadcrumb(elgg_echo('usersettings:statistics:opt:linktext'));
			$path = $CONFIG->path . "pages/settings/statistics.php";
			break;
		case 'plugins':
			elgg_push_breadcrumb(elgg_echo('usersettings:plugins:opt:linktext'));
			$path = $CONFIG->path . "pages/settings/tools.php";
			break;
		case 'user':
		default:
			$path = $CONFIG->path . "pages/settings/account.php";
			break;
	}

	require($path);
}

/**
 * Initialize the user settings library
 *
 * @return void
 */
function usersettings_init() {
	elgg_register_page_handler('settings', 'usersettings_page_handler');

	elgg_register_plugin_hook_handler('usersettings:save', 'user', 'users_settings_save');

	elgg_register_action("usersettings/save");
}

/// Register init function
elgg_register_event_handler('init', 'system', 'usersettings_init');
elgg_register_event_handler('pagesetup', 'system', 'usersettings_pagesetup');
