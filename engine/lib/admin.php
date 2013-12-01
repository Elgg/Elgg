<?php
/**
 * Elgg admin functions.
 * Functions for adding and manipulating options on the admin panel.
 *
 * @package Elgg
 * @subpackage Core
 */


/**
 * Register an admin page with the admin panel.
 * This function extends the view "admin/main" with the provided view. This view should provide a description
 * and either a control or a link to.
 *
 * Usage:
 * 	- To add a control to the main admin panel then extend admin/main
 *  - To add a control to a new page create a page which renders a view admin/subpage
 *    (where subpage is your new page -
 *    nb. some pages already exist that you can extend), extend the main view to point to it,
 *    and add controls to your new view.
 *
 * At the moment this is essentially a wrapper around elgg_extend_view().
 *
 * @param string $new_admin_view The view associated with the control you're adding
 * @param string $view The view to extend, by default this is 'admin/main'.
 * @param int $priority Optional priority to govern the appearance in the list.
 */
function extend_elgg_admin_page( $new_admin_view, $view = 'admin/main', $priority = 500) {
	return elgg_extend_view($view, $new_admin_view, $priority);
}

/**
 * Initialise the admin page.
 */
function admin_init() {
	// Add plugin main menu option (last)
	extend_elgg_admin_page('admin/main_opt/statistics', 'admin/main');
	extend_elgg_admin_page('admin/main_opt/site', 'admin/main');
	extend_elgg_admin_page('admin/main_opt/user', 'admin/main');
	extend_elgg_admin_page('admin/main_opt/plugins', 'admin/main', 999); // Always last

	register_action('admin/user/ban', false, "", true);
	register_action('admin/user/unban', false, "", true);
	register_action('admin/user/delete', false, "", true);
	register_action('admin/user/resetpassword', false, "", true);
	register_action('admin/user/makeadmin', false, "", true);
	register_action('admin/user/removeadmin', false, "", true);

	// Register some actions
	register_action('admin/site/update_basic', false, "", true); // Register basic site admin action
	register_action('admin/site/regenerate_secret', false, "", true);
	register_action('admin/delete_admin_notice', false, "", true);

	// Page handler
	register_page_handler('admin','admin_settings_page_handler');

//	if (isadminloggedin()) {
//		global $is_admin;
//		$is_admin = true;
//	}
}

/**
 * Add submenu items for admin page.
 */
function admin_pagesetup() {
	if (get_context() == 'admin') {
		global $CONFIG;

		add_submenu_item(elgg_echo('admin:statistics'), $CONFIG->wwwroot . 'pg/admin/statistics/');
		add_submenu_item(elgg_echo('admin:site'), $CONFIG->wwwroot . 'pg/admin/site/');
		add_submenu_item(elgg_echo('admin:user'), $CONFIG->wwwroot . 'pg/admin/user/');
		add_submenu_item(elgg_echo('admin:plugins'), $CONFIG->wwwroot . 'pg/admin/plugins/');
		add_submenu_item(elgg_echo('admin:settings:advanced:site_secret'), $CONFIG->wwwroot . 'pg/admin/site_secret/');
	}
}

/**
 * Write a persistent message to the admin view.
 * Useful to alert the admin to take a certain action.
 * The id is a unique ID that can be cleared once the admin
 * completes the action.
 *
 * eg: add_admin_notice('twitter_services_no_api',
 * 	'Before your users can use Twitter services on this site, you must set up
 * 	the Twitter API key in the <a href="link">Twitter Services Settings</a>');
 *
 * Do not use this function in 1.7 plugins. It will not be supported.
 *
 * @param string $id      A unique ID that your plugin can remember
 * @param string $message Body of the message
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_add_admin_notice($id, $message) {
	if ($id && $message) {
		if (elgg_admin_notice_exists($id)) {
			return false;
		}

		// need to handle when no one is logged in
		$old_ia = elgg_set_ignore_access(true);

		$admin_notice = new ElggObject();
		$admin_notice->subtype = 'admin_notice';
		// admins can see ACCESS_PRIVATE but no one else can.
		$admin_notice->access_id = ACCESS_PRIVATE;
		$admin_notice->admin_notice_id = $id;
		$admin_notice->description = $message;

		$result = $admin_notice->save();

		elgg_set_ignore_access($old_ia);

		return (bool)$result;
	}

	return false;
}

/**
 * Remove an admin notice by ID.
 *
 * eg In actions/twitter_service/save_settings:
 * 	if (is_valid_twitter_api_key()) {
 * 		delete_admin_notice('twitter_services_no_api');
 * 	}
 *
 * Do not use this function in 1.7 plugins. It will not be supported.
 *
 * @param string $id The unique ID assigned in add_admin_notice()
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_delete_admin_notice($id) {
	if (!$id) {
		return FALSE;
	}
	$result = TRUE;
	$notices = elgg_get_entities_from_metadata(array(
		'metadata_name' => 'admin_notice_id',
		'metadata_value' => $id
	));

	if ($notices) {
		// in case a bad plugin adds many, let it remove them all at once.
		foreach ($notices as $notice) {
			$result = ($result && $notice->delete());
		}
		return $result;
	}
	return FALSE;
}

/**
 * Get admin notices. An admin must be logged in since the notices are private.
 *
 * Do not use this function in 1.7 plugins. It will not be supported.
 *
 * @param int $limit Limit
 *
 * @return array Array of admin notices
 * @since 1.8.0
 */
function elgg_get_admin_notices($limit = 10) {
	return elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'admin_notice',
		'limit' => $limit
	));
}

/**
 * Check if an admin notice is currently active.
 *
 * Do not use this function in 1.7 plugins. It will not be supported.
 *
 * @param string $id The unique ID used to register the notice.
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_admin_notice_exists($id) {
	$old_ia = elgg_set_ignore_access(true);
	$notice = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'admin_notice',
		'metadata_name_value_pairs' => array('name' => 'admin_notice_id', 'value' => $id)
	));
	elgg_set_ignore_access($old_ia);

	return ($notice) ? TRUE : FALSE;
}

/**
 * Handle admin pages.
 *
 * @todo this should probably return something to prevent the default page handler from running
 * @param $page
 * @return unknown_type
 */
function admin_settings_page_handler($page) {
	global $CONFIG;

	$path = $CONFIG->path . "admin/index.php";

	if ($page[0]) {
		switch ($page[0]) {
			case 'user':
				$path = $CONFIG->path . "admin/user.php";
				break;
			case 'statistics':
				$path = $CONFIG->path . "admin/statistics.php";
				break;
			case 'plugins':
				$path = $CONFIG->path . "admin/plugins.php";
				break;
			case 'site':
				$path = $CONFIG->path . "admin/site.php";
				break;
			case 'site_secret':
				$path = $CONFIG->path . "admin/site_secret.php";
				break;
		}
	}

	if ($page[1]) {
		set_input('username', $page[1]);
	}

	include($path);
}

/**
 * Write a persistent message to the administrator's notification window.
 *
 * Currently this writes a message to the admin store, we may want to come up with another way at some point.
 *
 * @param string $subject Subject of the message
 * @param string $message Body of the message
 */
function send_admin_message($subject, $message) {
	$subject = sanitise_string($subject);
	$message = sanitise_string($message);

	if (($subject) && ($message)) {
		$admin_message = new ElggObject();
		$admin_message->subtype = 'admin_message';
		$admin_message->access_id = ACCESS_PUBLIC;
		$admin_message->title = $subject;
		$admin_message->description = $message;

		return $admin_message->save();
	}

	return false;
}

/**
 * List all admin messages.
 *
 * @param int $limit Limit
 */
function list_admin_messages($limit = 10) {
	return elgg_list_entities(array(
		'type' => 'object', 
		'subtype' => 'admin_message', 
		'limit' => $limit
	));
}

/**
 * Remove an admin message.
 *
 * @param int $guid The
 */
function clear_admin_message($guid) {
	return delete_entity($guid);
}

/// Register init functions
register_elgg_event_handler('init', 'system', 'admin_init');
register_elgg_event_handler('pagesetup', 'system', 'admin_pagesetup');
