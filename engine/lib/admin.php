<?php
/**
 * Elgg admin functions.
 * Functions for adding and manipulating options on the admin panel.
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Create the plugin settings submenu.
 *
 * This is done in a separate function called from the admin
 * page handler because of performance concerns.
 *
 * @return void
 */
function elgg_admin_add_plugin_settings_menu() {
	global $CONFIG;

	if (!$installed_plugins = get_installed_plugins()) {
		// nothing added because no items
		return FALSE;
	}

	elgg_add_admin_menu_item('plugin_settings', elgg_echo('admin:plugin_settings'));

	foreach ($installed_plugins as $plugin_id => $info) {
		if (!$info['active']) {
			continue;
		}

		if (elgg_view_exists("settings/{$plugin_id}/edit")) {
			elgg_add_admin_menu_item($plugin_id, $info['manifest']['name'], 'plugin_settings');
		}
	}
}

/**
 * Add an admin area section or child section.
 * This is a wrapper for elgg_register_menu_item().
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
function elgg_add_admin_menu_item($section_id, $section_title, $parent_id = NULL) {

	// in the admin section parents never have links
	if ($parent_id) {
		$href = "pg/admin/$parent_id/$section_id";
	} else {
		$href = NULL;
	}

	$name = $section_id;
	if ($parent_id) {
		$name = "$name:$parent_id";
	}

	return elgg_register_menu_item('page', array(
		'name' => $name,
		'url' => $href,
		'title' => $section_title,
		'context' => 'admin',
		'parent_name' => $parent_id,
	));
}

/**
 * Initialise the admin backend.
 *
 * @return void
 */
function admin_init() {
	elgg_register_action('admin/user/ban', '', 'admin');
	elgg_register_action('admin/user/unban', '', 'admin');
	elgg_register_action('admin/user/delete', '', 'admin');
	elgg_register_action('admin/user/resetpassword', '', 'admin');
	elgg_register_action('admin/user/makeadmin', '', 'admin');
	elgg_register_action('admin/user/removeadmin', '', 'admin');

	elgg_register_action('admin/site/update_basic', '', 'admin');
	elgg_register_action('admin/site/update_advanced', '', 'admin');
	
	elgg_register_action('admin/menu/save', '', 'admin');
	
	elgg_register_action('admin/plugins/simple_update_states', '', 'admin');

	elgg_register_action('profile/fields/reset', '', 'admin');
	elgg_register_action('profile/fields/add', '', 'admin');
	elgg_register_action('profile/fields/delete', '', 'admin');
	elgg_register_action('profile/fields/reorder', '', 'admin');

	// statistics
	elgg_add_admin_menu_item('statistics', elgg_echo('admin:statistics'));
	elgg_add_admin_menu_item('overview', elgg_echo('admin:statistics:overview'), 'statistics');

	// site
	elgg_add_admin_menu_item('site', elgg_echo('admin:site'));
	elgg_add_admin_menu_item('basic', elgg_echo('admin:site:basic'), 'site');
	elgg_add_admin_menu_item('advanced', elgg_echo('admin:site:advanced'), 'site');

	// appearance
	elgg_add_admin_menu_item('appearance', elgg_echo('admin:appearance'));
	elgg_add_admin_menu_item('menu_items', elgg_echo('admin:appearance:menu_items'), 'appearance');
	elgg_add_admin_menu_item('profile_fields', elgg_echo('admin:appearance:profile_fields'), 'appearance');

	// users
	elgg_add_admin_menu_item('users', elgg_echo('admin:users'));
	elgg_add_admin_menu_item('online', elgg_echo('admin:users:online'), 'users');
	elgg_add_admin_menu_item('newest', elgg_echo('admin:users:newest'), 'users');
	elgg_add_admin_menu_item('add', elgg_echo('admin:users:add'), 'users');

	// plugins
	elgg_add_admin_menu_item('plugins', elgg_echo('admin:plugins'));
	elgg_add_admin_menu_item('simple', elgg_echo('admin:plugins:simple'), 'plugins');
	elgg_add_admin_menu_item('advanced', elgg_echo('admin:plugins:advanced'), 'plugins');

	// plugins
	elgg_add_admin_menu_item('utilities', elgg_echo('admin:utilities'));

	// dashboard
	elgg_register_menu_item('page', array(
		'name' => 'dashboard',
		'url' => 'pg/admin/dashboard',
		'title' => elgg_echo('admin:dashboard'),
		'context' => 'admin',
	));

	// widgets
	$widgets = array('online_users', 'new_users', 'content_stats');
	foreach ($widgets as $widget) {
		elgg_register_widget_type(
				$widget,
				elgg_echo("admin:widget:$widget"),
				elgg_echo("admin:widget:$widget:help"),
				'admin'
		);
	}

	register_page_handler('admin', 'admin_settings_page_handler');
}

/**
 * Handles any set up required for administration pages
 */
function admin_pagesetup() {
	if (elgg_in_context('admin')) {
		$url = elgg_view_get_simplecache_url('css', 'admin');
		elgg_register_css($url, 'admin');
		elgg_unregister_css('elgg');
	}
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
	elgg_admin_add_plugin_settings_menu();
	elgg_set_context('admin');

	elgg_unregister_css('screen');

	// default to dashboard
	if (!isset($page[0]) || empty($page[0])) {
		$page = array('dashboard');
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
		$title = elgg_echo("admin:{$page[0]}");
	} else {
		$view = 'admin/' . implode('/', $page);
		$title = elgg_echo("admin:{$page[0]}");
		if (count($page) > 1) {
			$title .= ' : ' . elgg_echo('admin:' .  implode(':', $page));
		}
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

	$body = elgg_view_layout('admin', array('content' => $content, 'title' => $title));
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

elgg_register_event_handler('init', 'system', 'admin_init');
elgg_register_event_handler('pagesetup', 'system', 'admin_pagesetup', 1000);
