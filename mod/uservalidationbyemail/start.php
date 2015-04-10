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

	// forward to uservalidationbyemail/emailsent page after register
	elgg_register_plugin_hook_handler('forward', 'system', 'uservalidationbyemail_after_registration_url');

	// canEdit override to allow not logged in code to disable a user
	elgg_register_plugin_hook_handler('permissions_check', 'user', 'uservalidationbyemail_allow_new_user_can_edit');

	// prevent users from logging in if they aren't validated
	register_pam_handler('uservalidationbyemail_check_auth_attempt', "required");

	// when requesting a new password
	elgg_register_plugin_hook_handler('action', 'user/requestnewpassword', 'uservalidationbyemail_check_request_password');

	// prevent the engine from logging in users via login()
	elgg_register_event_handler('login:before', 'user', 'uservalidationbyemail_check_manual_login');

	// make admin users always validated
	elgg_register_event_handler('make_admin', 'user', 'uservalidationbyemail_validate_new_admin_user');

	// register Walled Garden public pages
	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'uservalidationbyemail_public_pages');

	// admin interface to manually validate users
	elgg_register_admin_menu_item('administer', 'unvalidated', 'users');

	elgg_extend_view('css/admin', 'uservalidationbyemail/css');
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

	// another plugin is requesting that registration be terminated
	// no need for uservalidationbyemail
	if (!$value) {
		return $value;
	}

	// has the user already been validated?
	if (elgg_get_user_validation_status($user->guid) == true) {
		return $value;
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
 * Override the URL to be forwarded after registration
 *
 * @param string $hook
 * @param string $type
 * @param bool   $value
 * @param array  $params
 * @return string
 */
function uservalidationbyemail_after_registration_url($hook, $type, $value, $params) {
	$url = elgg_extract('current_url', $params);
	if ($url == elgg_get_site_url() . 'action/register') {
		$session = elgg_get_session();
		$email = $session->get('emailsent', '');
		if ($email) {
			return elgg_get_site_url() . 'uservalidationbyemail/emailsent';
		}
	}
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

	// check if logging in with email address
	if (strpos($username, '@') !== false) {
		$users = get_user_by_email($username);
		if ($users) {
			$username = $users[0]->username;
		}
	}

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
 * @return bool
 */
function uservalidationbyemail_page_handler($page) {
	$valid_pages = array('emailsent', 'confirm');

	if (empty($page[0]) || !in_array($page[0], $valid_pages)) {
		forward('', '404');
	}

	// note, safe to include based on input because we validated above.
	require dirname(__FILE__) . "/pages/{$page[0]}.php";
	return true;
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
	$return_value[] = 'uservalidationbyemail/emailsent';
	return $return_value;
}

/**
 * Prevent a manual code login with login().
 *
 * @param string   $event
 * @param string   $type
 * @param ElggUser $user
 * @return bool
 *
 * @throws LoginException
 */
function uservalidationbyemail_check_manual_login($event, $type, $user) {
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(TRUE);

	if (($user instanceof ElggUser) && !$user->isEnabled() && !$user->validated) {
		// send new validation email
		uservalidationbyemail_request_validation($user->getGUID());
		
		// restore hidden entities settings
		access_show_hidden_entities($access_status);
		
		// throw error so we get a nice error message
		throw new LoginException(elgg_echo('uservalidationbyemail:login:fail'));
	}

	access_show_hidden_entities($access_status);
}
