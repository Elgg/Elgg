<?php
/**
 * Elgg user settings functions.
 * Functions for adding and manipulating options on the user settings panel.
 *
 * @package Elgg.Core
 * @subpackage Settings.User
 */

/**
 * Set a user's password
 * 
 * @return bool
 * @since 1.8.0
 * @access private
 */
function _elgg_set_user_password() {
	$current_password = get_input('current_password', null, false);
	$password = get_input('password', null, false);
	$password2 = get_input('password2', null, false);
	$user_guid = get_input('guid');

	if ($user_guid) {
		$user = get_user($user_guid);
	} else {
		$user = elgg_get_logged_in_user_entity();
	}

	if ($user && $password) {
		// let admin user change anyone's password without knowing it except his own.
		if (!elgg_is_admin_logged_in() || elgg_is_admin_logged_in() && $user->guid == elgg_get_logged_in_user_guid()) {
			$credentials = array(
				'username' => $user->username,
				'password' => $current_password
			);

			try {
				pam_auth_userpass($credentials);
			} catch (LoginException $e) {
				register_error(elgg_echo('LoginException:ChangePasswordFailure'));
				return false;
			}
		}

		try {
			$result = validate_password($password);
		} catch (RegistrationException $e) {
			register_error($e->getMessage());
			return false;
		}

		if ($result) {
			if ($password == $password2) {
				$user->setPassword($password);
				_elgg_services()->persistentLogin->handlePasswordChange($user, elgg_get_logged_in_user_entity());

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
 * @access private
 */
function _elgg_set_user_name() {
	$name = strip_tags(get_input('name'));
	$user_guid = get_input('guid');

	if ($user_guid) {
		$user = get_user($user_guid);
	} else {
		$user = elgg_get_logged_in_user_entity();
	}

	if (elgg_strlen($name) > 50) {
		register_error(elgg_echo('user:name:fail'));
		return false;
	}

	if ($user && $user->canEdit() && $name) {
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
 * @access private
 */
function _elgg_set_user_language() {
	$language = get_input('language');
	$user_guid = get_input('guid');

	if ($user_guid) {
		$user = get_user($user_guid);
	} else {
		$user = elgg_get_logged_in_user_entity();
	}

	if ($user && $language) {
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
 * @access private
 */
function _elgg_set_user_email() {
	$email = get_input('email');
	$user_guid = get_input('guid');

	if ($user_guid) {
		$user = get_user($user_guid);
	} else {
		$user = elgg_get_logged_in_user_entity();
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
 * @access private
 */
function _elgg_set_user_default_access() {

	if (!elgg_get_config('allow_user_default_access')) {
		return false;
	}

	$default_access = get_input('default_access');
	$user_guid = get_input('guid');

	if ($user_guid) {
		$user = get_user($user_guid);
	} else {
		$user = elgg_get_logged_in_user_entity();
	}

	if ($user) {
		$current_default_access = $user->getPrivateSetting('elgg_default_access');
		if ($default_access !== $current_default_access) {
			if ($user->setPrivateSetting('elgg_default_access', $default_access)) {
				system_message(elgg_echo('user:default_access:success'));
				return true;
			} else {
				register_error(elgg_echo('user:default_access:failure'));
			}
		} else {
			// no change
			return null;
		}
	} else {
		register_error(elgg_echo('user:default_access:failure'));
	}

	return false;
}

/**
 * Set up the menu for user settings
 *
 * @return void
 * @access private
 */
function _elgg_user_settings_menu_setup() {
	$user = elgg_get_page_owner_entity();

	if (!$user) {
		return;
	}

	if (!elgg_in_context("settings")) {
		return;
	}
	
	$params = array(
		'name' => '1_account',
		'text' => elgg_echo('usersettings:user:opt:linktext'),
		'href' => "settings/user/{$user->username}",
		'section' => 'configure',
	);
	elgg_register_menu_item('page', $params);
	$params = array(
		'name' => '1_plugins',
		'text' => elgg_echo('usersettings:plugins:opt:linktext'),
		'href' => '#',
		'section' => 'configure',
	);
	elgg_register_menu_item('page', $params);
	$params = array(
		'name' => '1_statistics',
		'text' => elgg_echo('usersettings:statistics:opt:linktext'),
		'href' => "settings/statistics/{$user->username}",
		'section' => 'configure',
	);
	elgg_register_menu_item('page', $params);
	
	// register plugin user settings menu items
	$active_plugins = elgg_get_plugins();
	
	foreach ($active_plugins as $plugin) {
		$plugin_id = $plugin->getID();
		if (elgg_view_exists("usersettings/$plugin_id/edit") || elgg_view_exists("plugins/$plugin_id/usersettings")) {
			$params = array(
				'name' => $plugin_id,
				'text' => $plugin->getFriendlyName(),
				'href' => "settings/plugins/{$user->username}/$plugin_id",
				'parent_name' => '1_plugins',
				'section' => 'configure',
			);
			elgg_register_menu_item('page', $params);
		}
	}
	
	elgg_register_plugin_hook_handler("prepare", "menu:page", "_elgg_user_settings_menu_prepare");
}

/**
 * Prepares the page menu to strip out empty plugins menu item for user settings
 *
 * @param string $hook   prepare
 * @param string $type   menu:page
 * @param array  $value  array of menu items
 * @param array  $params menu related parameters
 *
 * @return array
 * @access private
 */
function _elgg_user_settings_menu_prepare($hook, $type, $value, $params) {
	if (empty($value)) {
		return $value;
	}
	
	if (!elgg_in_context("settings")) {
		return $value;
	}
	
	$configure = elgg_extract("configure", $value);
	if (empty($configure)) {
		return $value;
	}	
	
	foreach ($configure as $index => $menu_item) {
		if (!($menu_item instanceof ElggMenuItem)) {
			continue;	
		}
		
		if ($menu_item->getName() == "1_plugins") {
			if (!$menu_item->getChildren()) {
				// no need for this menu item if it has no children
				unset($value["configure"][$index]);	
			}
		}
	}
	
	return $value;
}

/**
 * Page handler for user settings
 *
 * @param array $page Pages array
 *
 * @return bool
 * @access private
 */
function _elgg_user_settings_page_handler($page) {
	global $CONFIG;

	if (!isset($page[0])) {
		$page[0] = 'user';
	}

	if (isset($page[1])) {
		$user = get_user_by_username($page[1]);
		elgg_set_page_owner_guid($user->guid);
	} else {
		$user = elgg_get_logged_in_user_entity();
		elgg_set_page_owner_guid($user->guid);
	}

	elgg_push_breadcrumb(elgg_echo('settings'), "settings/user/$user->username");

	switch ($page[0]) {
		case 'statistics':
			elgg_push_breadcrumb(elgg_echo('usersettings:statistics:opt:linktext'));
			$path = $CONFIG->path . "pages/settings/statistics.php";
			break;
		case 'plugins':
			if (isset($page[2])) {
				set_input("plugin_id", $page[2]);
				elgg_push_breadcrumb(elgg_echo('usersettings:plugins:opt:linktext'));
				$path = $CONFIG->path . "pages/settings/tools.php";
			}
			break;
		case 'user':
			$path = $CONFIG->path . "pages/settings/account.php";
			break;
	}

	if (isset($path)) {
		require $path;
		return true;
	}
	return false;
}

/**
 * Initialize the user settings library
 *
 * @return void
 * @access private
 */
function _elgg_user_settings_init() {
	elgg_register_page_handler('settings', '_elgg_user_settings_page_handler');

	elgg_register_event_handler('pagesetup', 'system', '_elgg_user_settings_menu_setup');

	elgg_register_plugin_hook_handler('usersettings:save', 'user', '_elgg_set_user_language');
	elgg_register_plugin_hook_handler('usersettings:save', 'user', '_elgg_set_user_password');
	elgg_register_plugin_hook_handler('usersettings:save', 'user', '_elgg_set_user_default_access');
	elgg_register_plugin_hook_handler('usersettings:save', 'user', '_elgg_set_user_name');
	elgg_register_plugin_hook_handler('usersettings:save', 'user', '_elgg_set_user_email');
	
	elgg_register_action("usersettings/save");

	// extend the account settings form
	elgg_extend_view('forms/account/settings', 'core/settings/account/name', 100);
	elgg_extend_view('forms/account/settings', 'core/settings/account/password', 100);
	elgg_extend_view('forms/account/settings', 'core/settings/account/email', 100);
	elgg_extend_view('forms/account/settings', 'core/settings/account/language', 100);
	elgg_extend_view('forms/account/settings', 'core/settings/account/default_access', 100);
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_user_settings_init');
};
