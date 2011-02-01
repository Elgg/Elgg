<?php
/**
 * Email user validation plugin.
 * Non-admin or admin created accounts are invalid until their email address is confirmed.
 *
 * @package ElggUserValidationByEmail
 */

function uservalidationbyemail_init() {
	global $CONFIG;

	// Register page handler to validate users
	// This isn't an action because security is handled by the validation codes.
	register_page_handler('uservalidationbyemail', 'uservalidationbyemail_page_handler');

	// Register hook listening to new users.
	register_elgg_event_handler('validate', 'user', 'uservalidationbyemail_email_validation');

	// admin section
	register_elgg_event_handler('pagesetup', 'system', 'uservalidationbyemail_pagesetup');

	// styles
	elgg_extend_view('css', 'uservalidationbyemail/css');

	$action_path = dirname(__FILE__) . '/actions';

	register_action('uservalidationbyemail/validate', FALSE, "$action_path/validate.php", TRUE);
	register_action('uservalidationbyemail/resend_validation', FALSE, "$action_path/resend_validation.php", TRUE);
	register_action('uservalidationbyemail/delete', FALSE, "$action_path/delete.php", TRUE);
	register_action('uservalidationbyemail/bulk_action', FALSE, "$action_path/bulk_action.php", TRUE);
}

/**
 * Get security token, forward to action.
 *
 * @param unknown_type $page
 * @return unknown_type
 */
function uservalidationbyemail_page_handler($page) {
	global $CONFIG;

	$page = (isset($page[0])) ? $page[0] : FALSE;

	if ($page == 'confirm') {
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

				notify_user($user_guid, $CONFIG->site->guid, sprintf(elgg_echo('email:validate:success:subject'), $user->username), sprintf(elgg_echo('email:validate:success:body'), $user->name), NULL, 'email');

			} else {
				register_error(elgg_echo('email:confirm:fail'));
			}
		} else {
			register_error(elgg_echo('email:confirm:fail'));
		}

		access_show_hidden_entities($access_status);
	} elseif ($page == 'admin') {
		set_context('admin');
		admin_gatekeeper();
		$content = elgg_view('uservalidationbyemail/admin/users/unvalidated');
		$title = elgg_echo('uservalidationbyemail:admin:unvalidated');

		$body = elgg_view_layout('two_column_left_sidebar', '', elgg_view_title($title) . $content);

		page_draw($title, $body);


		return TRUE;
	} else {
		register_error(elgg_echo('email:confirm:fail'));
	}

	forward();
}

/**
 * Request email validation.
 */
function uservalidationbyemail_email_validation($event, $object_type, $object) {
	if (($object) && ($object instanceof ElggUser)) {
		uservalidationbyemail_request_validation($object->guid);
	}

	return true;
}

/**
 * Generate an email activation code.
 *
 * @param int $user_guid The guid of the user
 * @param string $email_address Email address
 * @return string
 */
function uservalidationbyemail_generate_code($user_guid, $email_address) {
	global $CONFIG;

	// Note I bind to site URL, this is important on multisite!
	return md5($user_guid . $email_address . $CONFIG->site->url . get_site_secret());
}

/**
 * Request user validation email.
 * Send email out to the address and request a confirmation.
 *
 * @param int $user_guid The user
 * @return mixed
 */
function uservalidationbyemail_request_validation($user_guid, $admin_requested = FALSE) {
	global $CONFIG;

	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);
	$site = $CONFIG->site;

	if (($user) && ($user instanceof ElggUser)) {
		// Work out validate link
		$code = uservalidationbyemail_generate_code($user_guid, $user->email);
		$link = "{$CONFIG->site->url}pg/uservalidationbyemail/confirm?u=$user_guid&c=$code";

		// Send validation email
		$subject = sprintf(elgg_echo('email:validate:subject'), $user->name, $site->name);
		$body = sprintf(elgg_echo('email:validate:body'), $user->name, $site->name, $link, $site->name, $site->url);
		$result = notify_user($user->guid, $CONFIG->site->guid, $subject, $body, NULL, 'email');

		if ($result && !$admin_requested) {
			system_message(elgg_echo('uservalidationbyemail:registerok'));
		}

		return $result;
	}

	return FALSE;
}

/**
 * Validate a user
 *
 * @param unknown_type $user_guid
 * @param unknown_type $code
 * @return unknown
 */
function uservalidationbyemail_validate_email($user_guid, $code) {
	$user = get_entity($user_guid);

	if ($code == uservalidationbyemail_generate_code($user_guid, $user->email)) {
		return set_user_validation_status($user_guid, true, 'email');
	}

	return false;
}

/**
 * Add Unvalidated users list to the admin menu
 *
 */
function uservalidationbyemail_pagesetup() {
	if (get_context() == 'admin' && isadminloggedin()) {
		global $CONFIG;
		add_submenu_item(elgg_echo('uservalidationbyemail:admin:unvalidated'), $CONFIG->wwwroot . 'pg/uservalidationbyemail/admin/');
	}
}


/**
 * Returns all users who haven't been validated.
 *
 * "Unvalidated" means metadata of validated is not set or not truthy.
 * We can't use elgg_get_entities_from_metadata() because you can't say
 * "where the entity has metadata set OR it's not equal to 1".
 *
 * @return array
 */
function uservalidationbyemail_get_unvalidated_users_sql_where() {
	global $CONFIG;

	$validated_id = get_metastring_id('validated');
	$one_id = get_metastring_id(1);

	// thanks to daveb@freenode for the SQL tips!
	$wheres = array();
	$wheres[] = "e.enabled='no'";
	$wheres[] = "NOT EXISTS (
			SELECT 1 FROM {$CONFIG->dbprefix}metadata md
			WHERE md.entity_guid = e.guid
				AND md.name_id = $validated_id
				AND md.value_id = $one_id)";

	return $wheres;
}

/**
 * Returns the validation status of a user.
 *
 * @param unknown_type $user_guid
 * @return int|null
 */
function uservalidationbyemail_get_user_validation_status($user_guid) {
	$md = get_metadata_byname($user_guid, 'validated');

	if ($md && $md->value) {
		return TRUE;
	}

	return FALSE;
}

// Initialise
register_elgg_event_handler('init', 'system', 'uservalidationbyemail_init');