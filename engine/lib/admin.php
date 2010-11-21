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
 * This function extends the view "admin/main" with the provided view.
 * This view should provide a description and either a control or a link to.
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
 * @param string $view           The view to extend, by default this is 'admin/main'.
 * @param int    $priority       Optional priority to govern the appearance in the list.
 *
 * @return void
 */
function extend_elgg_admin_page($new_admin_view, $view = 'admin/main', $priority = 500) {
	elgg_deprecated_notice('extend_elgg_admin_page() does nothing. Extend admin views manually.', 1.8);
}

/**
 * Calculate the plugin settings submenu.
 * This is done in a separate function called from the admin
 * page handler because of performance concerns.
 *
 * @return void
 */
function elgg_admin_add_plugin_settings_sidemenu() {
	global $CONFIG;

	if (!$installed_plugins = get_installed_plugins()) {
		// nothing added because no items
		return FALSE;
	}

	$parent_item = array(
		'text' => elgg_echo('admin:plugin_settings'),
		'id' => 'admin:plugin_settings'
	);

	elgg_add_submenu_item($parent_item, 'admin');

	foreach ($installed_plugins as $plugin_id => $info) {
		if (!$info['active']) {
			continue;
		}

		if (elgg_view_exists("settings/{$plugin_id}/edit")) {
			$item = array(
				'text' => $info['manifest']['name'],
				'href' => "pg/admin/plugin_settings/$plugin_id",
				'parent_id' => 'admin:plugin_settings'
			);

			elgg_add_submenu_item($item, 'admin');
		}
	}
}

/**
 * Add an admin area section or child section.
 * This is a wrapper for elgg_add_admin_item(array(...), 'admin').
 *
 * Used in conjuction with http://elgg.org/admin/section_id/child_section style
 * page handler.
 *
 * @param string $section_id    The Unique ID of section
 * @param string $section_title Human readable section title.
 * @param string $parent_id     If a child section, the parent section id.
 *
 * @return bool
 */
function elgg_add_admin_submenu_item($section_id, $section_title, $parent_id = NULL) {
	global $CONFIG;

	// in the admin section parents never have links
	if ($parent_id) {
		$href = "pg/admin/$parent_id/$section_id";
	} elseif ($section_id == 'overview') {
		$href = "pg/admin/$section_id";

	} else {
		$href = NULL;
	}

	$item = array(
		'text' => $section_title,
		'href' => $href,
		'id' => $section_id,
		'parent_id' => $parent_id
	);

	return elgg_add_submenu_item($item, 'admin');
}

/**
 * Initialise the admin page.
 *
 * @return void
 */
function admin_init() {
	register_action('admin/user/ban', FALSE, "", TRUE);
	register_action('admin/user/unban', FALSE, "", TRUE);
	register_action('admin/user/delete', FALSE, "", TRUE);
	register_action('admin/user/resetpassword', FALSE, "", TRUE);
	register_action('admin/user/makeadmin', FALSE, "", TRUE);
	register_action('admin/user/removeadmin', FALSE, "", TRUE);

	register_action('admin/site/update_basic', FALSE, "", TRUE);
	register_action('admin/site/update_advanced', FALSE, "", TRUE);

	register_action('admin/menu_items', FALSE, "", TRUE);

	register_action('admin/plugins/simple_update_states', FALSE, '', TRUE);

	// admin area overview and basic site settings
	elgg_add_admin_submenu_item('overview', elgg_echo('admin:overview'));

	elgg_add_admin_submenu_item('site', elgg_echo('admin:site'));
	elgg_add_admin_submenu_item('basic', elgg_echo('admin:site:basic'), 'site');
	elgg_add_admin_submenu_item('advanced', elgg_echo('admin:site:advanced'), 'site');

	// appearance
	elgg_add_admin_submenu_item('appearance', elgg_echo('admin:appearance'));

	//elgg_add_admin_submenu_item('basic', elgg_echo('admin:appearance'), 'appearance');
	elgg_add_admin_submenu_item('menu_items', elgg_echo('admin:menu_items'), 'appearance');

	// users
	elgg_add_admin_submenu_item('users', elgg_echo('admin:users'));
	elgg_add_admin_submenu_item('online', elgg_echo('admin:users:online'), 'users');
	elgg_add_admin_submenu_item('newest', elgg_echo('admin:users:newest'), 'users');
	elgg_add_admin_submenu_item('add', elgg_echo('admin:users:add'), 'users');

	// plugins
	elgg_add_admin_submenu_item('plugins', elgg_echo('admin:plugins'));
	elgg_add_admin_submenu_item('simple', elgg_echo('admin:plugins:simple'), 'plugins');
	elgg_add_admin_submenu_item('advanced', elgg_echo('admin:plugins:advanced'), 'plugins');

	// handled in the admin sidemenu so we don't have to generate this on every page load.
	//elgg_add_admin_submenu_item('plugin_settings', elgg_echo('admin:plugin_settings'));

	register_page_handler('admin', 'admin_settings_page_handler');
}

/**
 * Handle admin pages.  Expects corresponding views as admin/section/subsection
 *
 * @param array $page Array of pages
 *
 * @return void
 */
function admin_settings_page_handler($page) {
	global $CONFIG;

	admin_gatekeeper();
	elgg_admin_add_plugin_settings_sidemenu();
	elgg_set_context('admin');

	// default to overview
	if (!isset($page[0]) || empty($page[0])) {
		$page = array('overview');
	}

	// was going to fix this in the page_handler() function but
	// it's commented to explicitly return a string if there's a trailing /
	if (empty($page[count($page) - 1])) {
		array_pop($page);
	}

	$vars = array('page' => $page);

	// special page for plugin settings since we create the form for them
	if ($page[0] == 'plugin_settings' && isset($page[1])
	&& elgg_view_exists("settings/{$page[1]}/edit")) {

		$view = '/admin/components/plugin_settings';
		$vars['plugin'] = $page[1];
		$vars['entity'] = find_plugin_settings($page[1]);
		$title = elgg_echo("admin:plugin_settings:{$page[1]}");
	} else {
		$view = 'admin/' . implode('/', $page);
		$title = elgg_echo('admin:' .  implode(':', $page));
	}

	// allow a place to store helper views outside of the web-accessible views
	if ($page[0] == 'components' || !($content = elgg_view($view, $vars))) {
		$title = elgg_echo('admin:unknown_section');
		$content = elgg_echo('admin:unknown_section');
	}

	$notices_html = '';
	if ($notices = elgg_get_admin_notices()) {
		foreach ($notices as $notice) {
			$notices_html .= elgg_view_entity($notice);
		}

		$content = "<div class=\"admin_notices\">$notices_html</div>$content";
	}

	$body = elgg_view_layout('administration', array('content' => $content));
	echo elgg_view_page($title, $body, 'admin');
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
 * @param string $id      A unique ID that your plugin can remember
 * @param string $message Body of the message
 *
 * @return boo
 */
function elgg_add_admin_notice($id, $message) {
	if ($id && $message) {
		$admin_notice = new ElggObject();
		$admin_notice->subtype = 'admin_notice';
		// admins can see ACCESS_PRIVATE but no one else can.
		$admin_notice->access_id = ACCESS_PRIVATE;
		$admin_notice->admin_notice_id = $id;
		$admin_notice->description = $message;

		return $admin_notice->save();
	}

	return FALSE;
}


/**
 * Remove an admin notice by ID.
 *
 * eg In actions/twitter_service/save_settings:
 * 	if (is_valid_twitter_api_key()) {
 * 		delete_admin_notice('twitter_services_no_api');
 * 	}
 *
 * @param string $id The unique ID assigned in add_admin_notice()
 *
 * @return bool
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
 * List all admin messages.
 *
 * @param int $limit Limit
 *
 * @return array List of admin notices
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
 * @param string $id The unique ID used to register the notice.
 *
 * @return bool
 */
function elgg_admin_notice_exists($id) {
	$notice = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'admin_notice',
		'metadata_name_value_pair' => array('name' => 'admin_notice_id', 'value' => $id)
	));

	return ($notice) ? TRUE : FALSE;
}

// Register init functions
elgg_register_event_handler('init', 'system', 'admin_init');
elgg_register_event_handler('pagesetup', 'system', 'admin_pagesetup');
