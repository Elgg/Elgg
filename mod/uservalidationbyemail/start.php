<?php
/**
 * Email user validation plugin.
 * Non-admin accounts are invalid until their email address is confirmed.
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

	// admin user validation page
	elgg_register_plugin_hook_handler('register', 'menu:user:unvalidated', '_uservalidationbyemail_user_unvalidated_menu');
	elgg_register_plugin_hook_handler('register', 'menu:user:unvalidated:bulk', '_uservalidationbyemail_user_unvalidated_bulk_menu');

	// prevent the engine from logging in users via login()
	elgg_register_event_handler('login:before', 'user', 'uservalidationbyemail_check_manual_login');
}

/**
 * Disables a user upon registration
 *
 * @param \Elgg\Hook $hook 'register', 'user'
 *
 * @return void
 */
function uservalidationbyemail_disable_new_user(\Elgg\Hook $hook) {
	
	$user = $hook->getUserParam();
	// no clue what's going on, so don't react.
	if (!$user instanceof ElggUser) {
		return;
	}

	// another plugin is requesting that registration be terminated
	// no need for uservalidationbyemail
	if (!$hook->getValue()) {
		return;
	}

	// has the user already been validated?
	if ($user->isValidated()) {
		return;
	}

	// disable user to prevent showing up on the site
	elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function () use ($user) {
		if ($user->isEnabled()) {
			// Don't do a recursive disable.  Any entities owned by the user at this point
			// are products of plugins that hook into create user and might need
			// access to the entities.
			// @todo That ^ sounds like a specific case...would be nice to track it down...
			$user->disable('uservalidationbyemail_new_user', false);
		}
	
		// set user as unvalidated
		$user->setValidationStatus(false);
		
		// set flag for tracking validation status
		elgg_set_plugin_user_setting('email_validated', false, $user->guid, 'uservalidationbyemail');
		
		// send out validation email
		uservalidationbyemail_request_validation($user->guid);
	});
}

/**
 * Override the URL to be forwarded after registration
 *
 * @param \Elgg\Hook $hook 'response', 'action:register'
 *
 * @return void|\Elgg\Http\ResponseBuilder
 */
function uservalidationbyemail_after_registration_url(\Elgg\Hook $hook) {
	if (!elgg_get_session()->get('emailsent')) {
		return;
	}
	
	$value = $hook->getValue();
	$value->setForwardURL(elgg_generate_url('account:validation:email:sent'));
	
	return $value;
}

/**
 * Prevent a manual code login with login()
 *
 * @param \Elgg\Event $event 'login:before', 'user'
 *
 * @return void
 *
 * @throws LoginException
 */
function uservalidationbyemail_check_manual_login(\Elgg\Event $event) {
	$user = $event->getObject();
	if (!$user instanceof ElggUser) {
		return;
	}
	
	elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($user) {
		if ($user->isEnabled() && $user->isValidated() !== false) {
			return;
		}

		if ((bool) elgg_get_plugin_user_setting('email_validated', $user->guid, 'uservalidationbyemail', true)) {
			// email address already validated, or account created before plugin was enabled
			return;
		}
		
		// send new validation email
		uservalidationbyemail_request_validation($user->guid);
		
		// throw error so we get a nice error message
		throw new LoginException(elgg_echo('uservalidationbyemail:login:fail'));
	});
}

/**
 * Add a menu item to an unvalidated user
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
	
	$validated = elgg_get_plugin_user_setting('email_validated', $entity->guid, 'uservalidationbyemail');
	if (!isset($validated) || (bool) $validated) {
		// email address already validated
		return;
	}
	
	$return = $hook->getValue();
	
	$return[] = ElggMenuItem::factory([
		'name' => 'uservalidationbyemail:resend',
		'text' => elgg_echo('uservalidationbyemail:admin:resend_validation'),
		'href' => elgg_generate_action_url('uservalidationbyemail/resend_validation', [
			'user_guids[]' => $entity->guid,
		]),
		'confirm' => elgg_echo('uservalidationbyemail:confirm_resend_validation', [$entity->getDisplayName()]),
		'priority' => 100,
	]);
	
	return $return;
}

/**
 * Add a menu item to the bulk actions for unvalidated users
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
