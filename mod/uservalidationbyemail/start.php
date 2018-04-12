<?php
/**
 * Email user validation plugin.
 * Non-admin accounts are invalid until their email address is confirmed.
 *
 * @package Elgg.Core.Plugin
 * @subpackage UserValidationByEmail
 */

/**
 * User validation by email init
 *
 * @return void
 */
function uservalidationbyemail_init() {

	require_once dirname(__FILE__) . '/lib/functions.php';

	// mark users as unvalidated and disable when they register
	elgg_register_plugin_hook_handler('register', 'user', 'uservalidationbyemail_disable_new_user');

	// forward to uservalidationbyemail/emailsent page after register
	elgg_register_plugin_hook_handler('response', 'action:register', 'uservalidationbyemail_after_registration_url');

	// canEdit override to allow not logged in code to disable a user
	elgg_register_plugin_hook_handler('permissions_check', 'user', 'uservalidationbyemail_allow_new_user_can_edit');
	
	// admin user validation page
	elgg_register_plugin_hook_handler('register', 'menu:user:unvalidated', '_uservalidationbyemail_user_unvalidated_menu');
	elgg_register_plugin_hook_handler('register', 'menu:user:unvalidated:bulk', '_uservalidationbyemail_user_unvalidated_bulk_menu');

	// prevent users from logging in if they aren't validated
	register_pam_handler('uservalidationbyemail_check_auth_attempt', "required");

	// prevent the engine from logging in users via login()
	elgg_register_event_handler('login:before', 'user', 'uservalidationbyemail_check_manual_login');

	// make admin users always validated
	elgg_register_event_handler('make_admin', 'user', 'uservalidationbyemail_validate_new_admin_user');
}

/**
 * Disables a user upon registration
 *
 * @param string $hook   'register'
 * @param string $type   'user'
 * @param bool   $value  current return value
 * @param array  $params supplied params
 *
 * @return void
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
		return;
	}

	// has the user already been validated?
	if ($user->isValidated()) {
		return;
	}

	// disable user to prevent showing up on the site
	// set context so our canEdit() override works
	elgg_push_context('uservalidationbyemail_new_user');
	$hidden_entities = access_show_hidden_entities(true);

	// Don't do a recursive disable.  Any entities owned by the user at this point
	// are products of plugins that hook into create user and might need
	// access to the entities.
	// @todo That ^ sounds like a specific case...would be nice to track it down...
	$user->disable('uservalidationbyemail_new_user', false);

	// set user as unvalidated and send out validation email
	$user->setValidationStatus(false);
	uservalidationbyemail_request_validation($user->guid);

	elgg_pop_context();
	access_show_hidden_entities($hidden_entities);
}

/**
 * Override the URL to be forwarded after registration
 *
 * @param string                     $hook   'response'
 * @param string                     $type   'action:register'
 * @param \Elgg\Http\ResponseBuilder $value  Current response
 * @param array                      $params Additional params
 *
 * @return void|\Elgg\Http\ResponseBuilder
 */
function uservalidationbyemail_after_registration_url($hook, $type, $value, $params) {
	$session = elgg_get_session();
	$email = $session->get('emailsent', '');
	if ($email) {
		$value->setForwardURL(elgg_normalize_url('uservalidationbyemail/emailsent'));
		return $value;
	}
}

/**
 * Override the canEdit() call for if we're in the context of registering a new user.
 *
 * @param string $hook   'permissions_check'
 * @param string $type   'user'
 * @param bool   $value  current return value
 * @param array  $params supplied params
 *
 * @return void|true
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
		return true;
	}
}

/**
 * Checks if an account is validated
 *
 * @param array $credentials The username and password
 *
 * @return void
 */
function uservalidationbyemail_check_auth_attempt($credentials) {

	if (!isset($credentials['username'])) {
		return;
	}

	$username = $credentials['username'];

	// See if the user exists and isn't validated
	$access_status = access_show_hidden_entities(true);
	
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
 * Make sure any admin users are automatically validated
 *
 * @param string   $event 'make_admin'
 * @param string   $type  'user'
 * @param ElggUser $user  the user
 *
 * @return void
 */
function uservalidationbyemail_validate_new_admin_user($event, $type, $user) {
	if ($user instanceof ElggUser && !$user->isValidated()) {
		$user->setValidationStatus(true, 'admin_user');
	}
}

/**
 * Prevent a manual code login with login()
 *
 * @param string   $event 'login:before'
 * @param string   $type  'user'
 * @param ElggUser $user  the user
 *
 * @return void
 *
 * @throws LoginException
 */
function uservalidationbyemail_check_manual_login($event, $type, $user) {
	
	$access_status = access_show_hidden_entities(true);
	
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

/**
 * Add a menu item to an unvalidated user
 *
 * @elgg_plugin_hook register menu:user:unvalidated
 *
 * @param \Elgg\Hook $hook the plugin hook 'register' 'menu:user:unvalidated'
 *
 * @return void|ElggMenuItem[]
 *
 * @since 3.0
 * @internal
 */
function _uservalidationbyemail_user_unvalidated_menu(\Elgg\Hook $hook) {
	
	if (!elgg_is_admin_logged_in()) {
		return;
	}
	
	$entity = $hook->getEntityParam();
	if (!$entity instanceof ElggUser) {
		return;
	}
	
	$return = $hook->getValue();
	
	$return[] = ElggMenuItem::factory([
		'name' => 'uservalidationbyemail:resend',
		'text' => elgg_echo('uservalidationbyemail:admin:resend_validation'),
		'href' => elgg_http_add_url_query_elements('action/uservalidationbyemail/resend_validation', [
			'user_guids[]' => $entity->guid,
		]),
		'confirm' => elgg_echo('uservalidationbyemail:confirm_resend_validation', [$entity->getDisplayName()]),
		'priority' => 100,
	]);
	
	return $return;
}

/**
 * Add a menu item to the buld actions for unvalidated users
 *
 * @elgg_plugin_hook register menu:user:unvalidated:bulk
 *
 * @param \Elgg\Hook $hook the plugin hook 'register' 'menu:user:unvalidated:bulk'
 *
 * @return void|ElggMenuItem[]
 *
 * @since 3.0
 * @internal
 */
function _uservalidationbyemail_user_unvalidated_bulk_menu(\Elgg\Hook $hook) {
	
	if (!elgg_is_admin_logged_in()) {
		return;
	}
	
	$return = $hook->getValue();
	
	$return[] = ElggMenuItem::factory([
		'id' => 'uservalidationbyemail-bulk-resend',
		'name' => 'uservalidationbyemail:resend:bulk',
		'text' => elgg_echo('uservalidationbyemail:admin:resend_validation'),
		'href' => 'action/uservalidationbyemail/resend_validation',
		'confirm' => elgg_echo('uservalidationbyemail:confirm_resend_validation_checked'),
		'priority' => 100,
		'section' => 'right',
		'deps' => 'elgg/uservalidationbyemail',
	]);
	
	return $return;
}

return function() {
	elgg_register_event_handler('init', 'system', 'uservalidationbyemail_init');
};
