<?php
/**
 * Email user validation plugin.
 * Non-admin accounts are invalid until their email address is confirmed.
 *
 * @package Elgg.Core.Plugin
 * @subpackage UserValidationByEmail
 */

function uservalidationbyemail_init() {
	global $CONFIG;

	require_once dirname(__FILE__) . '/lib/functions.php';

	// Register page handler to validate users
	// This doesn't need to be an action because security is handled by the validation codes.
	register_page_handler('uservalidationbyemail', 'uservalidationbyemail_page_handler');

	// mark users as unvalidated when they register
	register_plugin_hook('register', 'user', 'uservalidationbyemail_disable_new_user');

	// prevent users from logging in if they aren't validated
	register_plugin_hook('action', 'login', 'uservalidationbyemail_check_login_attempt');

	// when requesting a new password
	register_plugin_hook('action', 'user/requestnewpassword', 'uservalidationbyemail_check_request_password');

	// prevent the engine from logging in users via login()
	register_elgg_event_handler('login', 'user', 'uservalidationbyemail_check_manual_login');

	// make admin users always validated
	register_elgg_event_handler('make_admin', 'user', 'uservalidationbyemail_validate_new_admin_user');

	// register Walled Garden public pages
	register_plugin_hook('public_pages', 'walled_garden', 'uservalidationbyemail_public_pages');

	// admin interface to manually validate users
	elgg_add_admin_submenu_item('unvalidated', elgg_echo('uservalidationbyemail:admin:unvalidated'), 'users');

	$action_path = dirname(__FILE__) . '/actions';

	register_action('uservalidationbyemail/validate', FALSE, "$action_path/validate.php", TRUE);
	register_action('uservalidationbyemail/resend_validation', FALSE, "$action_path/resend_validation.php", TRUE);
	register_action('uservalidationbyemail/delete', FALSE, "$action_path/delete.php", TRUE);
	register_action('uservalidationbyemail/bulk_action', FALSE, "$action_path/bulk_action.php", TRUE);
}

/**
 * Disables a user upon registration.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 */
function uservalidationbyemail_disable_new_user($hook, $type, $value, $params) {
	$user = elgg_get_array_value('user', $params);

	// no clue what's going on, so don't react.
	if (!$user instanceof ElggUser) {
		return NULL;
	}

	// disable user to prevent showing up on the site
	// Don't do a recursive disable.  Any entities owned by the user at this point
	// are products of plugins that hook into create user and might need
	// access to the entities.
	// @todo That ^ sounds like a specific case...would be nice to track it down...
	$user->disable('uservalidationbyemail_new_user', FALSE);

	// set user as unvalidated and send out validation email
	uservalidationbyemail_set_user_validation_status($user->guid, FALSE);
	uservalidationbyemail_request_validation($user->guid);

	return TRUE;
}

/**
 * Checks if a login failed because the user hasn't validated his account.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 */
function uservalidationbyemail_check_login_attempt($hook, $type, $value, $params) {
	// everything is only stored in the input at this point
	$username = get_input('username');
	$password = get_input("password");

	if (empty($username) || empty($password)) {
		// return true to let the original login action deal with it.
		return TRUE;
	}

	// see if we need to resolve an email address to a username
	if (strpos($username, '@') !== FALSE && ($users = get_user_by_email($username))) {
		$username = $users[0]->username;
	}

	// See the users exists and isn't validated
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(TRUE);

	$user = get_user_by_username($username);

	// only resend validation if the password is correct
	if ($user && authenticate($username, $password) && !$user->validated) {
		// show an error and resend validation email
		uservalidationbyemail_request_validation($user->guid);
		// halt action
		$value = FALSE;
	}

	access_show_hidden_entities($access_status);

	return $value;
}

/**
 * Checks sent passed validation code and user guids and validates the user.
 *
 * @param array $page
 */
function uservalidationbyemail_page_handler($page) {
	global $CONFIG;

	if (isset($page[0]) && $page[0] == 'confirm') {
		$code = sanitise_string(get_input('c', FALSE));
		$user_guid = get_input('u', FALSE);

		// new users are not enabled by default.
		$access_status = access_get_show_hidden_status();
		access_show_hidden_entities(true);

		$user = get_entity($user_guid);

		if (($code) && ($user)) {
			if (uservalidationbyemail_validate_email($user_guid, $code)) {
				system_message(elgg_echo('email:confirm:success'));

				$user = get_entity($user_guid);
				$user->enable();
				login($user);
			} else {
				register_error(elgg_echo('email:confirm:fail'));
			}
		} else {
			register_error(elgg_echo('email:confirm:fail'));
		}

		access_show_hidden_entities($access_status);
	} else {
		register_error(elgg_echo('email:confirm:fail'));
	}

	forward();
}

/**
 * Make sure any admin users are automatically validated
 *
 * @param unknown_type $event
 * @param unknown_type $type
 * @param unknown_type $object
 */
function uservalidationbyemail_validate_new_admin_user($event, $type, $user) {
	if ($user instanceof ElggUser && !$user->validated) {
		uservalidationbyemail_set_user_validation_status($user->guid, TRUE, 'admin_user');
	}

	return TRUE;
}

/**
 * Registers public pages to allow in the case Private Network has been enabled.
 */
function uservalidationbyemail_public_pages($hook, $type, $return_value, $params) {
	$return_value[] = 'pg/uservalidationbyemail/confirm';
	return $return_value;
}

/**
 * Prevent a manual code login with login().
 *
 * @param unknown_type $event
 * @param unknown_type $type
 * @param unknown_type $user
 */
function uservalidationbyemail_check_manual_login($event, $type, $user) {
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(TRUE);

	// @todo register_error()?
	$return = ($user instanceof ElggUser && !$user->validated) ? FALSE : NULL;

	access_show_hidden_entities($access_status);

	return $return;
}

/**
 * Deny requests to change password if the account isn't validated.
 *
 * @todo This is needed because changing the password requires the entity to be enabled.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 */
function uservalidationbyemail_check_request_password($hook, $type, $value, $params) {
	$username = get_input('username');

	// see if we need to resolve an email address to a username
	if (strpos($username, '@') !== FALSE && ($users = get_user_by_email($username))) {
		$username = $users[0]->username;
	}

	// See the users exists and isn't validated
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(TRUE);

	$user = get_user_by_username($username);

	// resend validation instead of resetting password
	if ($user && !$user->validated) {
		uservalidationbyemail_request_validation($user->guid);
		$value = FALSE;
	}

	access_show_hidden_entities($access_status);

	return $value;
}

register_elgg_event_handler('init', 'system', 'uservalidationbyemail_init');