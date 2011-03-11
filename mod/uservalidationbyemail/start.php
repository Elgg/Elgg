<?php
/**
 * Email user validation plugin.
 * Non-admin accounts are invalid until their email address is confirmed.
 *
 * @package Elgg.Core.Plugin
 * @subpackage UserValidationByEmail
 */

elgg_register_event_handler('init', 'system', 'uservalidationbyemail_init');

function uservalidationbyemail_init() {

	require_once dirname(__FILE__) . '/lib/functions.php';

	// Register page handler to validate users
	// This doesn't need to be an action because security is handled by the validation codes.
	elgg_register_page_handler('uservalidationbyemail', 'uservalidationbyemail_page_handler');

	// mark users as unvalidated and disable when they register
	elgg_register_plugin_hook_handler('register', 'user', 'uservalidationbyemail_disable_new_user');

	// canEdit override to allow not logged in code to disable a user
	elgg_register_plugin_hook_handler('permissions_check', 'user', 'uservalidationbyemail_allow_new_user_can_edit');

	// prevent users from logging in if they aren't validated
	register_pam_handler('uservalidationbyemail_check_auth_attempt', "required");

	// when requesting a new password
	elgg_register_plugin_hook_handler('action', 'user/requestnewpassword', 'uservalidationbyemail_check_request_password');

	// prevent the engine from logging in users via login()
	elgg_register_event_handler('login', 'user', 'uservalidationbyemail_check_manual_login');

	// make admin users always validated
	elgg_register_event_handler('make_admin', 'user', 'uservalidationbyemail_validate_new_admin_user');

	// register Walled Garden public pages
	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'uservalidationbyemail_public_pages');

	// admin interface to manually validate users
	elgg_register_admin_menu_item('administer', 'unvalidated', 'users');

	elgg_extend_view('js/elgg', 'uservalidationbyemail/js');

	$action_path = dirname(__FILE__) . '/actions';

	elgg_register_action('uservalidationbyemail/validate', "$action_path/validate.php", 'admin');
	elgg_register_action('uservalidationbyemail/resend_validation', "$action_path/resend_validation.php", 'admin');
	elgg_register_action('uservalidationbyemail/delete', "$action_path/delete.php", 'admin');
	elgg_register_action('uservalidationbyemail/bulk_action', "$action_path/bulk_action.php", 'admin');
}

/**
 * Disables a user upon registration.
 *
 * @param string $hook
 * @param string $type
 * @param bool   $value
 * @param array  $params
 * @return bool
 */
function uservalidationbyemail_disable_new_user($hook, $type, $value, $params) {
	$user = elgg_extract('user', $params);

	// no clue what's going on, so don't react.
	if (!$user instanceof ElggUser) {
		return;
	}

	// disable user to prevent showing up on the site
	// set context so our canEdit() override works
	elgg_push_context('uservalidationbyemail_new_user');
	$hidden_entities = access_get_show_hidden_status();
	access_show_hidden_entities(TRUE);

	// Don't do a recursive disable.  Any entities owned by the user at this point
	// are products of plugins that hook into create user and might need
	// access to the entities.
	// @todo That ^ sounds like a specific case...would be nice to track it down...
	$user->disable('uservalidationbyemail_new_user', FALSE);

	// set user as unvalidated and send out validation email
	elgg_set_user_validation_status($user->guid, FALSE);
	uservalidationbyemail_request_validation($user->guid);

	elgg_pop_context();
	access_show_hidden_entities($hidden_entities);

	return $value;
}

/**
 * Override the canEdit() call for if we're in the context of registering a new user.
 *
 * @param string $hook
 * @param string $type
 * @param bool   $value
 * @param array  $params
 * @return bool|null
 */
function uservalidationbyemail_allow_new_user_can_edit($hook, $type, $value, $params) {
	// $params['user'] is the user to check permissions for.
	// we want the entity to check, which is a user.
	$user = elgg_extract('entity', $params);

	if (!($user instanceof ElggUser)) {
		return;
	}

	$context = elgg_get_context();
	if ($context == 'uservalidationbyemail_new_user' || $context == 'uservalidationbyemail_validate_user') {
		return TRUE;
	}

	return;
}

/**
 * Checks if an account is validated
 *
 * @params array $credentials The username and password
 * @return bool
 */
function uservalidationbyemail_check_auth_attempt($credentials) {

	if (!isset($credentials['username'])) {
		return;
	}

	$username = $credentials['username'];

	// See if the user exists and isn't validated
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(TRUE);

	$user = get_user_by_username($username);
	if ($user && isset($user->validated) && !$user->validated) {
		// show an error and resend validation email
		uservalidationbyemail_request_validation($user->guid);
		access_show_hidden_entities($access_status);
		throw new LoginException(elgg_echo('uservalidationbyemail:login:fail'));
	}

	access_show_hidden_entities($access_status);
}

/**
 * Checks sent passed validation code and user guids and validates the user.
 *
 * @param array $page
 */
function uservalidationbyemail_page_handler($page) {

	if (isset($page[0]) && $page[0] == 'confirm') {
		$code = sanitise_string(get_input('c', FALSE));
		$user_guid = get_input('u', FALSE);

		// new users are not enabled by default.
		$access_status = access_get_show_hidden_status();
		access_show_hidden_entities(true);

		$user = get_entity($user_guid);

		if (($code) && ($user)) {
			if (uservalidationbyemail_validate_email($user_guid, $code)) {

				elgg_push_context('uservalidationbyemail_validate_user');
				system_message(elgg_echo('email:confirm:success'));
				$user = get_entity($user_guid);
				$user->enable();
				elgg_pop_context();

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
 * @param string   $event
 * @param string   $type
 * @param ElggUser $user
 */
function uservalidationbyemail_validate_new_admin_user($event, $type, $user) {
	if ($user instanceof ElggUser && !$user->validated) {
		elgg_set_user_validation_status($user->guid, TRUE, 'admin_user');
	}
}

/**
 * Registers public pages to allow in the case walled garden has been enabled.
 */
function uservalidationbyemail_public_pages($hook, $type, $return_value, $params) {
	$return_value[] = 'uservalidationbyemail/confirm';
	return $return_value;
}

/**
 * Prevent a manual code login with login().
 *
 * @param string   $event
 * @param string   $type
 * @param ElggUser $user
 * @return bool
 */
function uservalidationbyemail_check_manual_login($event, $type, $user) {
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(TRUE);

	// @todo register_error()?
	$return = ($user instanceof ElggUser && !$user->isEnabled() && !$user->validated) ? FALSE : NULL;

	access_show_hidden_entities($access_status);

	return $return;
}
