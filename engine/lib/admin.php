<?php
/**
 * Elgg admin functions.
 *
 * Admin pages
 * Plugins no not need to provide their own page handler to add a page to the
 * admin area. A view placed at admin/<section>/<subsection> can be access
 * at http://example.org/admin/<section>/<subsection>. The title of the page
 * will be elgg_echo('admin:<section>:<subsection>'). For an example of how to
 * add a page to the admin area, see the diagnostics plugin.
 *
 * Admin notices
 * System messages (success and error messages) are used in both the main site
 * and the admin area. There is a special presistent message for the admin area
 * called an admin notice. It should be used when a plugin requires an
 * administrator to take an action. @see elgg_add_admin_notice()
 */

use Elgg\Menu\MenuItems;
use Elgg\Database\QueryBuilder;
use Elgg\Http\ResponseBuilder;

/**
 * Get the admin users
 *
 * @param array $options Options array, @see elgg_get_entities() for parameters
 *
 * @return mixed Array of admin users or false on failure. If a count, returns int.
 * @since 1.8.0
 */
function elgg_get_admins(array $options = []) {
	$options['type'] = 'user';
	$options['metadata_name_value_pairs'] = elgg_extract('metadata_name_value_pairs', $options, []);
	
	$options['metadata_name_value_pairs']['admin'] = 'yes';

	return elgg_get_entities($options);
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
 * @return ElggAdminNotice|bool
 * @since 1.8.0
 */
function elgg_add_admin_notice($id, $message) {
	return _elgg_services()->adminNotices->add($id, $message);
}

/**
 * Remove an admin notice by ID.
 *
 * @param string $id The unique ID assigned in add_admin_notice()
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_delete_admin_notice($id) {
	return _elgg_services()->adminNotices->delete($id);
}

/**
 * Get admin notices. An admin must be logged in since the notices are private.
 *
 * @param array $options Query options
 *
 * @return \ElggObject[]|int|mixed Admin notices
 * @since 1.8.0
 */
function elgg_get_admin_notices(array $options = []) {
	return _elgg_services()->adminNotices->find($options);
}

/**
 * Check if an admin notice is currently active. (Ignores access)
 *
 * @param string $id The unique ID used to register the notice.
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_admin_notice_exists($id) {
	return _elgg_services()->adminNotices->exists($id);
}

/**
 * Add an admin notice when a new \ElggUpgrade object is created.
 *
 * @param \Elgg\Event $event 'create', 'object'
 *
 * @return void
 *
 * @internal
 */
function _elgg_create_notice_of_pending_upgrade(\Elgg\Event $event) {
	if (!$event->getObject() instanceof \ElggUpgrade) {
		return;
	}
	
	// Link to the Upgrades section
	$link = elgg_view('output/url', [
		'href' => 'admin/upgrades',
		'text' => elgg_echo('admin:view_upgrades'),
	]);

	$message = elgg_echo('admin:pending_upgrades');

	elgg_add_admin_notice('pending_upgrades', "$message $link");
}

/**
 * Initialize the admin backend.
 * @return void
 * @internal
 */
function _elgg_admin_init() {

	elgg_register_external_file('css', 'elgg.admin', elgg_get_simplecache_url('admin.css'));
	elgg_register_external_file('css', 'admin/users/unvalidated', elgg_get_simplecache_url('admin/users/unvalidated.css'));

	elgg_define_js('admin/users/unvalidated', [
		'src' => elgg_get_simplecache_url('admin/users/unvalidated.js'),
	]);
	
	elgg_extend_view('admin.css', 'lightbox/elgg-colorbox-theme/colorbox.css');
	
	elgg_register_ajax_view('forms/admin/user/change_email');
		
	elgg_register_plugin_hook_handler('register', 'menu:admin_header', '_elgg_admin_header_menu');
	elgg_register_plugin_hook_handler('register', 'menu:admin_footer', '_elgg_admin_footer_menu');
	elgg_register_plugin_hook_handler('register', 'menu:filter:admin/upgrades', '_elgg_admin_upgrades_menu');
	elgg_register_plugin_hook_handler('register', 'menu:page', '_elgg_admin_page_menu');
	elgg_register_plugin_hook_handler('register', 'menu:page', '_elgg_admin_page_menu_plugin_settings');
	elgg_register_plugin_hook_handler('register', 'menu:user:unvalidated:bulk', '_elgg_admin_user_unvalidated_bulk_menu');

	// maintenance mode
	if (elgg_get_config('elgg_maintenance_mode', null)) {
		elgg_register_plugin_hook_handler('route', 'all', '_elgg_admin_maintenance_handler', 600);
		elgg_register_plugin_hook_handler('action', 'all', '_elgg_admin_maintenance_action_check', 600);
		elgg_register_external_file('css', 'maintenance', elgg_get_simplecache_url('maintenance.css'));

		elgg_register_menu_item('topbar', [
			'name' => 'maintenance_mode',
			'href' => 'admin/configure_utilities/maintenance',
			'text' => elgg_echo('admin:maintenance_mode:indicator_menu_item'),
			'icon' => 'wrench',
			'priority' => 900,
		]);
	}

	elgg_register_simplecache_view('admin.css');
	
	// widgets
	$widgets = ['online_users', 'new_users', 'content_stats', 'banned_users', 'admin_welcome', 'control_panel', 'cron_status'];
	foreach ($widgets as $widget) {
		elgg_register_widget_type(
				$widget,
				elgg_echo("admin:widget:$widget"),
				elgg_echo("admin:widget:$widget:help"),
				['admin']
		);
	}

	// automatic adding of widgets for admin
	elgg_register_event_handler('make_admin', 'user', '_elgg_add_admin_widgets');
	
	elgg_register_notification_event('user', 'user', ['make_admin', 'remove_admin']);
	elgg_register_plugin_hook_handler('get', 'subscriptions', '_elgg_admin_get_admin_subscribers_admin_action');
	elgg_register_plugin_hook_handler('get', 'subscriptions', '_elgg_admin_get_user_subscriber_admin_action');
	elgg_register_plugin_hook_handler('prepare', 'notification:make_admin:user:user', '_elgg_admin_prepare_admin_notification_make_admin');
	elgg_register_plugin_hook_handler('prepare', 'notification:make_admin:user:user', '_elgg_admin_prepare_user_notification_make_admin');
	elgg_register_plugin_hook_handler('prepare', 'notification:remove_admin:user:user', '_elgg_admin_prepare_admin_notification_remove_admin');
	elgg_register_plugin_hook_handler('prepare', 'notification:remove_admin:user:user', '_elgg_admin_prepare_user_notification_remove_admin');
	
	// new users require admin validation
	elgg_register_event_handler('login:before', 'user', '_elgg_admin_user_validation_login_attempt', 999); // allow others to throw exceptions earlier
	elgg_register_event_handler('validate:after', 'user', '_elgg_admin_user_validation_notification');
	elgg_register_plugin_hook_handler('cron', 'daily', '_elgg_admin_notify_admins_pending_user_validation');
	elgg_register_plugin_hook_handler('cron', 'weekly', '_elgg_admin_notify_admins_pending_user_validation');
	elgg_register_plugin_hook_handler('register', 'user', '_elgg_admin_check_admin_validation', 999); // allow others to also disable the user
	elgg_register_plugin_hook_handler('response', 'action:register', '_elgg_admin_set_registration_forward_url', 999); // allow other to set forwar url first
	elgg_register_plugin_hook_handler('usersettings:save', 'user', '_elgg_admin_save_notification_setting');
	
	// Add notice about pending upgrades
	elgg_register_event_handler('create', 'object', '_elgg_create_notice_of_pending_upgrade');
}

/**
 * Returns plugin listing and admin menu to the client (used after plugin (de)activation)
 *
 * @internal
 * @return Elgg\Http\OkResponse
 */
function _elgg_ajax_plugins_update() {
	elgg_admin_gatekeeper();
	elgg_set_context('admin');

	return elgg_ok_response([
		'list' => elgg_view('admin/plugins', ['list_only' => true]),
		'sidebar' => elgg_view('admin/sidebar'),
	]);
}

/**
 * Register menu items for the admin_header menu
 *
 * @param \Elgg\Hook $hook 'register', 'menu:admin_header'
 *
 * @return void|MenuItems
 *
 * @internal
 * @since 3.0
 */
function _elgg_admin_header_menu(\Elgg\Hook $hook) {
	if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
		return;
	}
	
	$return = $hook->getValue();

	$admin = elgg_get_logged_in_user_entity();

	$return[] = \ElggMenuItem::factory([
		'name' => 'account',
		'text' => $admin->getDisplayName(),
		'href' => $admin->getURL(),
		'icon' => elgg_view('output/img', [
			'src' => $admin->getIconURL('small'),
			'alt' => $admin->getDisplayName(),
		]),
		'link_class' => 'elgg-avatar-small',
		'priority' => 1000,
	]);

	$return[] = \ElggMenuItem::factory([
		'name' => 'admin_logout',
		'href' => 'action/logout',
		'text' => elgg_echo('logout'),
		'priority' => 900,
	]);

	$return[] = \ElggMenuItem::factory([
		'name' => 'view_site',
		'href' => elgg_get_site_url(),
		'text' => elgg_echo('admin:view_site'),
		'priority' => 800,
	]);

	if (elgg_get_config('elgg_maintenance_mode')) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'maintenance',
			'href' => 'admin/configure_utilities/maintenance',
			'text' => elgg_echo('admin:configure_utilities:maintenance'),
			'link_class' => 'elgg-maintenance-mode-warning',
			'priority' => 700,
		]);
	}
	
	return $return;
}

/**
 * Register menu items for the admin_footer menu
 *
 * @param \Elgg\Hook $hook 'register', 'menu:admin_footer'
 *
 * @return void|\ElggMenuItem[]
 *
 * @internal
 * @since 3.0
 */
function _elgg_admin_footer_menu(\Elgg\Hook $hook) {
	if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
		return;
	}

	$return = $hook->getValue();
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'faq',
		'text' => elgg_echo('admin:footer:faq'),
		'href' => 'http://learn.elgg.org/en/stable/appendix/faqs.html',
	]);

	$return[] = \ElggMenuItem::factory([
		'name' => 'manual',
		'text' => elgg_echo('admin:footer:manual'),
		'href' => 'http://learn.elgg.org/en/stable/admin/index.html',
	]);

	$return[] = \ElggMenuItem::factory([
		'name' => 'community_forums',
		'text' => elgg_echo('admin:footer:community_forums'),
		'href' => 'http://elgg.org/groups/all/',
	]);

	$return[] = \ElggMenuItem::factory([
		'name' => 'blog',
		'text' => elgg_echo('admin:footer:blog'),
		'href' => 'https://elgg.org/blog/all',
	]);
	
	return $return;
}

/**
 * Register menu items for the page menu
 *
 * @param \Elgg\Hook $hook 'register' 'menu:page'
 * @return array
 *
 * @internal
 * @see _elgg_default_widgets_init() for default widgets menu items setup
 * @since 3.0
 */
function _elgg_admin_page_menu(\Elgg\Hook $hook) {
	if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
		return;
	}
	
	$return = $hook->getValue();

	// administer
	$return[] = \ElggMenuItem::factory([
		'name' => 'dashboard',
		'href' => 'admin',
		'text' => elgg_echo('admin:dashboard'),
		'priority' => 10,
		'section' => 'administer',
	]);
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'plugins',
		'href' => 'admin/plugins',
		'text' => elgg_echo('admin:plugins'),
		'priority' => 30,
		'section' => 'administer',
	]);

	$return[] = \ElggMenuItem::factory([
		'name' => 'users',
		'text' => elgg_echo('admin:users'),
		'priority' => 40,
		'section' => 'administer',
	]);
	$return[] = \ElggMenuItem::factory([
		'name' => 'users:online',
		'text' => elgg_echo('admin:users:online'),
		'href' => 'admin/users/online',
		'priority' => 10,
		'section' => 'administer',
		'parent_name' => 'users',
	]);
	$return[] = \ElggMenuItem::factory([
		'name' => 'users:admins',
		'text' => elgg_echo('admin:users:admins'),
		'href' => 'admin/users/admins',
		'priority' => 20,
		'section' => 'administer',
		'parent_name' => 'users',
	]);
	$return[] = \ElggMenuItem::factory([
		'name' => 'users:newest',
		'text' => elgg_echo('admin:users:newest'),
		'href' => 'admin/users/newest',
		'priority' => 30,
		'section' => 'administer',
		'parent_name' => 'users',
	]);
	$return[] = \ElggMenuItem::factory([
		'name' => 'users:add',
		'text' => elgg_echo('admin:users:add'),
		'href' => 'admin/users/add',
		'priority' => 40,
		'section' => 'administer',
		'parent_name' => 'users',
	]);
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'users:unvalidated',
		'text' => elgg_echo('admin:users:unvalidated'),
		'href' => 'admin/users/unvalidated',
		'priority' => 50,
		'section' => 'administer',
		'parent_name' => 'users',
	]);
	$return[] = \ElggMenuItem::factory([
		'name' => 'upgrades',
		'href' => 'admin/upgrades',
		'text' => elgg_echo('admin:upgrades'),
		'priority' => 600,
		'section' => 'administer',
	]);
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'administer_utilities',
		'text' => elgg_echo('admin:administer_utilities'),
		'priority' => 50,
		'section' => 'administer',
	]);
	
	// configure
	$return[] = \ElggMenuItem::factory([
		'name' => 'settings:basic',
		'href' => 'admin/site_settings',
		'text' => elgg_echo('admin:site_settings'),
		'priority' => 10,
		'section' => 'configure',
	]);
	$return[] = \ElggMenuItem::factory([
		'name' => 'security',
		'href' => 'admin/security',
		'text' => elgg_echo('admin:security'),
		'priority' => 30,
		'section' => 'configure',
	]);

	$return[] = \ElggMenuItem::factory([
		'name' => 'configure_utilities',
		'text' => elgg_echo('admin:configure_utilities'),
		'priority' => 600,
		'section' => 'configure',
	]);
	$return[] = \ElggMenuItem::factory([
		'name' => 'configure_utilities:maintenance',
		'text' => elgg_echo('admin:configure_utilities:maintenance'),
		'href' => 'admin/configure_utilities/maintenance',
		'section' => 'configure',
		'parent_name' => 'configure_utilities',
	]);
	$return[] = \ElggMenuItem::factory([
		'name' => 'configure_utilities:menu_items',
		'text' => elgg_echo('admin:configure_utilities:menu_items'),
		'href' => 'admin/configure_utilities/menu_items',
		'section' => 'configure',
		'parent_name' => 'configure_utilities',
	]);
	$return[] = \ElggMenuItem::factory([
		'name' => 'configure_utilities:robots',
		'text' => elgg_echo('admin:configure_utilities:robots'),
		'href' => 'admin/configure_utilities/robots',
		'section' => 'configure',
		'parent_name' => 'configure_utilities',
	]);
	
	// information
	$return[] = \ElggMenuItem::factory([
		'name' => 'information:performance',
		'href' => 'admin/performance',
		'text' => elgg_echo('admin:performance'),
		'section' => 'information',
		'priority' => 70,
	]);
	$return[] = \ElggMenuItem::factory([
		'name' => 'information:security',
		'href' => 'admin/security/information',
		'text' => elgg_echo('admin:security'),
		'section' => 'information',
		'priority' => 60,
	]);
	$return[] = \ElggMenuItem::factory([
		'name' => 'statistics',
		'href' => 'admin/statistics',
		'text' => elgg_echo('admin:statistics'),
		'section' => 'information',
		'priority' => 80,
	]);
	$return[] = \ElggMenuItem::factory([
		'name' => 'server',
		'href' => 'admin/server',
		'text' => elgg_echo('admin:server'),
		'section' => 'information',
		'priority' => 50,
	]);
		
	return $return;
}

/**
 * Register plugin settings menu items for the admin page menu
 *
 * @note Plugin settings are alphabetically sorted in the submenu
 *
 * @param \Elgg\Hook $hook 'register' 'menu:page'
 * @return array
 *
 * @internal
 * @since 3.0
 */
function _elgg_admin_page_menu_plugin_settings(\Elgg\Hook $hook) {
	if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
		return;
	}
	
	// plugin settings
	$active_plugins = elgg_get_plugins('active');
	if (empty($active_plugins)) {
		// nothing added because no items
		return;
	}
	
	$plugins_with_settings = [];
	
	foreach ($active_plugins as $plugin) {
		$plugin_id = $plugin->getID();
		
		if (!elgg_view_exists("plugins/{$plugin_id}/settings") ) {
			continue;
		}
		$plugin_name = $plugin->getDisplayName();
		$plugins_with_settings[$plugin_name] = [
			'name' => $plugin_id,
			'href' => "admin/plugin_settings/$plugin_id",
			'text' => $plugin_name,
			'parent_name' => 'plugin_settings',
			'section' => 'configure',
		];
	}
	
	if (empty($plugins_with_settings)) {
		return;
	}

	$return = $hook->getValue();
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'plugin_settings',
		'text' => elgg_echo('admin:plugin_settings'),
		'section' => 'configure',
	]);
	
	ksort($plugins_with_settings);
	$priority = 0;
	foreach ($plugins_with_settings as $plugin_item) {
		$priority += 10;
		$plugin_item['priority'] = $priority;
		$return[] = \ElggMenuItem::factory($plugin_item);
	}
	
	return $return;
}

/**
 * Register menu items to the bulk actions for unvalidated users
 *
 * @elgg_plugin_hook register menu:user:unvalidated:bulk
 *
 * @param \Elgg\Hook $hook 'register' 'menu:user:unvalidated:bulk'
 *
 * @return void|ElggMenuItem[]
 *
 * @since 3.0
 * @internal
 */
function _elgg_admin_user_unvalidated_bulk_menu(\Elgg\Hook $hook) {
	
	if (!elgg_is_admin_logged_in()) {
		return;
	}
	
	$return = $hook->getValue();
	
	$return[] = ElggMenuItem::factory([
		'name' => 'select_all',
		'text' => elgg_view('input/checkbox', [
			'name' => 'select_all',
			'label' => elgg_echo('all'),
			'id' => 'admin-users-unvalidated-bulk-select',
		]),
		'href' => false,
		'priority' => 100,
		'deps' => 'admin/users/unvalidated',
	]);
	
	$return[] = ElggMenuItem::factory([
		'id' => 'admin-users-unvalidated-bulk-validate',
		'name' => 'bulk_validate',
		'text' => elgg_echo('validate'),
		'href' => 'action/admin/user/bulk/validate',
		'confirm' => true,
		'priority' => 400,
		'section' => 'right',
		'deps' => 'admin/users/unvalidated',
	]);
	
	$return[] = ElggMenuItem::factory([
		'id' => 'admin-users-unvalidated-bulk-delete',
		'name' => 'bulk_delete',
		'text' => elgg_echo('delete'),
		'href' => 'action/admin/user/bulk/delete',
		'confirm' => elgg_echo('deleteconfirm:plural'),
		'priority' => 500,
		'section' => 'right',
		'deps' => 'admin/users/unvalidated',
	]);
	
	return $return;
}

/**
 * Handle admin pages.  Expects corresponding views as admin/section/subsection
 *
 * @param array $page Array of pages
 *
 * @return bool
 * @internal
 */
function _elgg_admin_page_handler($page) {
	elgg_admin_gatekeeper();
	elgg_set_context('admin');

	elgg_unregister_external_file('css', 'elgg');
	elgg_require_js('elgg/admin');

	// default to dashboard
	if (!isset($page[0]) || empty($page[0])) {
		$page = ['dashboard'];
	}

	// was going to fix this in the page_handler() function but
	// it's commented to explicitly return a string if there's a trailing /
	if (empty($page[count($page) - 1])) {
		array_pop($page);
	}

	$vars = ['page' => $page];

	// special page for plugin settings since we create the form for them
	if ($page[0] == 'plugin_settings') {
		if (isset($page[1]) && (elgg_view_exists("plugins/{$page[1]}/settings"))) {
			$view = 'admin/plugin_settings';
			$plugin = elgg_get_plugin_from_id($page[1]);
			$vars['plugin'] = $plugin; // required for plugin settings backward compatibility
			$vars['entity'] = $plugin;

			$title = elgg_echo("admin:{$page[0]}") . ': ' . $plugin->getDisplayName();
		} else {
			throw new \Elgg\PageNotFoundException();
		}
	} else {
		$view = 'admin/' . implode('/', $page);
		$title = elgg_echo("admin:{$page[0]}");
		if (count($page) > 1) {
			$title .= ' : ' . elgg_echo('admin:' .  implode(':', $page));
		}
	}

	// gets content and prevents direct access to 'components' views
	if ($page[0] == 'components' || !($content = elgg_view($view, $vars))) {
		throw new \Elgg\PageNotFoundException();
	}

	$body = elgg_view_layout('admin', ['content' => $content, 'title' => $title]);
	echo elgg_view_page($title, $body, 'admin');
	return true;
}

/**
 * When in maintenance mode, should the given URL be handled normally?
 *
 * @param string $current_url Current page URL
 * @return bool
 *
 * @internal
 */
function _elgg_admin_maintenance_allow_url($current_url) {
	$site_path = preg_replace('~^https?~', '', elgg_get_site_url());
	$current_path = preg_replace('~^https?~', '', $current_url);
	if (0 === elgg_strpos($current_path, $site_path)) {
		$current_path = ($current_path === $site_path) ? '' : elgg_substr($current_path, elgg_strlen($site_path));
	} else {
		$current_path = false;
	}

	// allow plugins to control access for specific URLs/paths
	$params = [
		'current_path' => $current_path,
		'current_url' => $current_url,
	];
	return (bool) elgg_trigger_plugin_hook('maintenance:allow', 'url', $params, false);
}

/**
 * Handle requests when in maintenance mode
 *
 * @param \Elgg\Hook $hook 'route', 'all'
 *
 * @return void|false
 *
 * @internal
 */
function _elgg_admin_maintenance_handler(\Elgg\Hook $hook) {
	if (elgg_is_admin_logged_in()) {
		return;
	}

	$info = $hook->getValue();
	
	if ($info['identifier'] == 'action' && $info['segments'][0] == 'login') {
		return;
	}

	if (_elgg_admin_maintenance_allow_url(current_page_url())) {
		return;
	}

	elgg_unregister_plugin_hook_handler('register', 'menu:login', '_elgg_login_menu_setup');

	echo elgg_view_resource('maintenance');

	return false;
}

/**
 * Prevent non-admins from using actions
 *
 * @param \Elgg\Hook $hook 'action', 'all'
 *
 * @return bool
 * @internal
 */
function _elgg_admin_maintenance_action_check(\Elgg\Hook $hook) {
	if (elgg_is_admin_logged_in()) {
		return true;
	}

	if ($hook->getType() == 'login') {
		$username = get_input('username');

		$user = get_user_by_username($username);

		if (!$user) {
			$users = get_user_by_email($username);
			if (!empty($users)) {
				$user = $users[0];
			}
		}

		if ($user && $user->isAdmin()) {
			return true;
		}
	}

	if (_elgg_admin_maintenance_allow_url(current_page_url())) {
		return true;
	}

	register_error(elgg_echo('actionunauthorized'));

	return false;
}

/**
 * Adds default admin widgets to the admin dashboard.
 *
 * @param \Elgg\Event $event 'make_admin', 'user'
 *
 * @return void
 * @internal
 */
function _elgg_add_admin_widgets(\Elgg\Event $event) {
	$user = $event->getObject();
	
	elgg_call(ELGG_IGNORE_ACCESS, function() use ($user) {
		// check if the user already has widgets
		if (elgg_get_widgets($user->guid, 'admin')) {
			return;
		}
	
		// In the form column => array of handlers in order, top to bottom
		$adminWidgets = [
			1 => ['control_panel', 'admin_welcome'],
			2 => ['online_users', 'new_users', 'content_stats'],
		];
	
		foreach ($adminWidgets as $column => $handlers) {
			foreach ($handlers as $position => $handler) {
				$guid = elgg_create_widget($user->getGUID(), $handler, 'admin');
				if ($guid !== false) {
					$widget = get_entity($guid);
					/* @var \ElggWidget $widget */
					$widget->move($column, $position);
				}
			}
		}
	});
}

/**
 * Add the current site admins to the subscribers when making/removing an admin user
 *
 * @param \Elgg\Hook $hook 'get', 'subscribers'
 *
 * @return void|array
 */
function _elgg_admin_get_admin_subscribers_admin_action(\Elgg\Hook $hook) {
	
	if (!_elgg_config()->security_notify_admins) {
		return;
	}
	
	$event = $hook->getParam('event');
	if (!$event instanceof \Elgg\Notifications\SubscriptionNotificationEvent) {
		return;
	}
	
	if (!in_array($event->getAction(), ['make_admin', 'remove_admin'])) {
		return;
	}
	
	$user = $event->getObject();
	if (!$user instanceof \ElggUser) {
		return;
	}
	
	/* @var $admin_batch \Elgg\BatchResult */
	$admin_batch = elgg_get_admins([
		'limit' => false,
		'wheres' => [
			function (QueryBuilder $qb, $main_alias) use ($user) {
				return $qb->compare("{$main_alias}.guid", '!=', $user->guid, ELGG_VALUE_GUID);
			},
		],
		'batch' => true,
	]);
	
	$return_value = $hook->getValue();
	
	/* @var $admin \ElggUser */
	foreach ($admin_batch as $admin) {
		$return_value[$admin->guid] = ['email'];
	}
	
	return $return_value;
}

/**
 * Prepare the notification content for site admins about making a site admin
 *
 * @param \Elgg\Hook $hook 'prepare', 'notification:make_admin:user:'
 *
 * @return void|\Elgg\Notifications\Notification
 */
function _elgg_admin_prepare_admin_notification_make_admin(\Elgg\Hook $hook) {
	
	$return_value = $hook->getValue();
	if (!$return_value instanceof \Elgg\Notifications\Notification) {
		return;
	}
	
	$recipient = $hook->getParam('recipient');
	$object = $hook->getParam('object');
	$actor = $hook->getParam('sender');
	$language = $hook->getParam('language');
	
	if (!($recipient instanceof ElggUser) || !($object instanceof ElggUser) || !($actor instanceof ElggUser)) {
		return;
	}
	
	if ($recipient->getGUID() === $object->getGUID()) {
		// recipient is the user being acted on, this is handled elsewhere
		return;
	}
	
	$site = elgg_get_site_entity();
	
	$return_value->subject = elgg_echo('admin:notification:make_admin:admin:subject', [$site->getDisplayName()], $language);
	$return_value->body = elgg_echo('admin:notification:make_admin:admin:body', [
		$recipient->getDisplayName(),
		$actor->getDisplayName(),
		$object->getDisplayName(),
		$site->getDisplayName(),
		$object->getURL(),
		$site->getURL(),
	], $language);

	$return_value->url = elgg_normalize_url('admin/users/admins');
	
	return $return_value;
}

/**
 * Prepare the notification content for site admins about removing a site admin
 *
 * @param \Elgg\Hook $hook 'prepare', 'notification:remove_admin:user:user'
 *
 * @return void|\Elgg\Notifications\Notification
 */
function _elgg_admin_prepare_admin_notification_remove_admin(\Elgg\Hook $hook) {
	
	$return_value = $hook->getValue();
	if (!$return_value instanceof \Elgg\Notifications\Notification) {
		return;
	}
	
	$recipient = $hook->getParam('recipient');
	$object = $hook->getParam('object');
	$actor = $hook->getParam('sender');
	$language = $hook->getParam('language');
	
	if (!($recipient instanceof ElggUser) || !($object instanceof ElggUser) || !($actor instanceof ElggUser)) {
		return;
	}
	
	if ($recipient->getGUID() === $object->getGUID()) {
		// recipient is the user being acted on, this is handled elsewhere
		return;
	}
	
	$site = elgg_get_site_entity();
	
	$return_value->subject = elgg_echo('admin:notification:remove_admin:admin:subject', [$site->getDisplayName()], $language);
	$return_value->body = elgg_echo('admin:notification:remove_admin:admin:body', [
		$recipient->getDisplayName(),
		$actor->getDisplayName(),
		$object->getDisplayName(),
		$site->getDisplayName(),
		$object->getURL(),
		$site->getURL(),
	], $language);

	$return_value->url = elgg_normalize_url('admin/users/admins');
	
	return $return_value;
}

/**
 * Add the user to the subscribers when making/removing the admin role
 *
 * @param \Elgg\Hook $hook 'get', 'subscribers'
 *
 * @return void|array
 */
function _elgg_admin_get_user_subscriber_admin_action(\Elgg\Hook $hook) {
	
	if (!_elgg_config()->security_notify_user_admin) {
		return;
	}
	
	$event = $hook->getParam('event');
	if (!$event instanceof \Elgg\Notifications\SubscriptionNotificationEvent) {
		return;
	}
	
	if (!in_array($event->getAction(), ['make_admin', 'remove_admin'])) {
		return;
	}
	
	$user = $event->getObject();
	if (!$user instanceof \ElggUser) {
		return;
	}
	
	$return_value = $hook->getValue();
	
	$return_value[$user->guid] = ['email'];
	
	return $return_value;
}

/**
 * Prepare the notification content for the user being made as a site admins
 *
 * @param \Elgg\Hook $hook 'prepare', 'notification:make_admin:user:user'
 *
 * @return void|\Elgg\Notifications\Notification
 */
function _elgg_admin_prepare_user_notification_make_admin(\Elgg\Hook $hook) {
	
	$return_value = $hook->getValue();
	if (!$return_value instanceof \Elgg\Notifications\Notification) {
		return;
	}
	
	$recipient = $hook->getParam('recipient');
	$object = $hook->getParam('object');
	$actor = $hook->getParam('sender');
	$language = $hook->getParam('language');
	
	if (!($recipient instanceof ElggUser) || !($object instanceof ElggUser) || !($actor instanceof ElggUser)) {
		return;
	}
	
	if ($recipient->guid !== $object->guid) {
		// recipient is some other user, this is handled elsewhere
		return;
	}
	
	$site = elgg_get_site_entity();
	
	$return_value->subject = elgg_echo('admin:notification:make_admin:user:subject', [$site->getDisplayName()], $language);
	$return_value->body = elgg_echo('admin:notification:make_admin:user:body', [
		$recipient->getDisplayName(),
		$actor->getDisplayName(),
		$site->getDisplayName(),
		$site->getURL(),
	], $language);

	$return_value->url = elgg_normalize_url('admin');
	
	return $return_value;
}

/**
 * Prepare the notification content for the user being removed as a site admins
 *
 * @param \Elgg\Hook $hook 'prepare', 'notification:remove_admin:user:user'
 *
 * @return void|\Elgg\Notifications\Notification
 */
function _elgg_admin_prepare_user_notification_remove_admin(\Elgg\Hook $hook) {
	
	$return_value = $hook->getValue();
	if (!$return_value instanceof \Elgg\Notifications\Notification) {
		return;
	}
	
	$recipient = $hook->getParam('recipient');
	$object = $hook->getParam('object');
	$actor = $hook->getParam('sender');
	$language = $hook->getParam('language');
	
	if (!($recipient instanceof ElggUser) || !($object instanceof ElggUser) || !($actor instanceof ElggUser)) {
		return;
	}
	
	if ($recipient->getGUID() !== $object->getGUID()) {
		// recipient is some other user, this is handled elsewhere
		return;
	}
	
	$site = elgg_get_site_entity();
	
	$return_value->subject = elgg_echo('admin:notification:remove_admin:user:subject', [$site->getDisplayName()], $language);
	$return_value->body = elgg_echo('admin:notification:remove_admin:user:body', [
		$recipient->getDisplayName(),
		$actor->getDisplayName(),
		$site->getDisplayName(),
		$site->getURL(),
	], $language);

	$return_value->url = '';
	
	return $return_value;
}

/**
 * Add menu items to the filter menu on the admin upgrades page
 *
 * @param \Elgg\Hook $hook 'register', 'menu:filter:admin/upgrades'
 *
 * @return MenuItems
 * @internal
 */
function _elgg_admin_upgrades_menu(\Elgg\Hook $hook) {
	
	$result = $hook->getValue();
	
	$result[] = ElggMenuItem::factory([
		'name' => 'pending',
		'text' => elgg_echo('admin:upgrades:menu:pending'),
		'href' => 'admin/upgrades',
		'priority' => 100,
	]);
	
	$result[] = ElggMenuItem::factory([
		'name' => 'completed',
		'text' => elgg_echo('admin:upgrades:menu:completed'),
		'href' => 'admin/upgrades/finished',
		'priority' => 200,
	]);
	
	$result[] = ElggMenuItem::factory([
		'name' => 'db',
		'text' => elgg_echo('admin:upgrades:menu:db'),
		'href' => 'admin/upgrades/db',
		'priority' => 300,
	]);
	
	return $result;
}

/**
 * Check if new users need to be validated by an administrator
 *
 * @param \Elgg\Hook $hook 'register', 'user'
 *
 * @return void
 * @internal
 * @since 3.2
 */
function _elgg_admin_check_admin_validation(\Elgg\Hook $hook) {
	
	if (!(bool) elgg_get_config('require_admin_validation')) {
		return;
	}
	
	$user = $hook->getUserParam();
	if (!$user instanceof ElggUser) {
		return;
	}
	
	elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($user) {
		
		if ($user->isEnabled()) {
			// disable the user until validation
			$user->disable('admin_validation_required', false);
		}
		
		// set validation status
		$user->setValidationStatus(false);
		
		// store a flag in session so we can forward the user correctly
		$session = elgg_get_session();
		$session->set('admin_validation', true);
		
		if (elgg_get_config('admin_validation_notification') === 'direct') {
			_elgg_admin_notify_admins_pending_user_validation();
		}
	});
}

/**
 * Prevent unvalidated users from logging in
 *
 * @param \Elgg\Event $event 'login:before', 'user'
 *
 * @return void
 * @throws LoginException
 * @internal
 * @since 3.2
 */
function _elgg_admin_user_validation_login_attempt(\Elgg\Event $event) {
	
	if (!(bool) elgg_get_config('require_admin_validation')) {
		return;
	}
	
	$user = $event->getObject();
	if (!$user instanceof ElggUser) {
		return;
	}
	
	elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($user) {
		if ($user->isEnabled() && $user->isValidated() !== false) {
			return;
		}
		
		throw new LoginException(elgg_echo('LoginException:AdminValidationPending'));
	});
}

/**
 * Send a notification to all admins that there are pending user validations
 *
 * @return void
 * @internal
 * @since 3.2
 */
function _elgg_admin_notify_admins_pending_user_validation() {
	
	if (empty(elgg_get_config('admin_validation_notification'))) {
		return;
	}
	
	$unvalidated_count = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() {
		return elgg_count_entities([
			'type' => 'user',
			'metadata_name_value_pairs' => [
				'validated' => 0,
			],
		]);
	});
	if (empty($unvalidated_count)) {
		// shouldn't be able to get here because this function is triggered when a user is marked as unvalidated
		return;
	}
	
	$site = elgg_get_site_entity();
	$admins = elgg_get_admins([
		'limit' => false,
		'batch' => true,
	]);
	
	$url = elgg_normalize_url('admin/users/unvalidated');
	
	/* @var $admin ElggUser */
	foreach ($admins as $admin) {
		$user_setting = $admin->getPrivateSetting('admin_validation_notification');
		if (isset($user_setting) && !(bool) $user_setting) {
			continue;
		}
		
		$subject = elgg_echo('admin:notification:unvalidated_users:subject', [$site->getDisplayName()], $admin->getLanguage());
		$body = elgg_echo('admin:notification:unvalidated_users:body', [
			$admin->getDisplayName(),
			$unvalidated_count,
			$site->getDisplayName(),
			$url,
		], $admin->getLanguage());
		
		$params = [
			'action' => 'admin:unvalidated',
			'object' => $admin,
		];
		notify_user($admin->guid, $site->guid, $subject, $body, $params, ['email']);
	}
}

/**
 * Save a setting related to admin approval of new users
 *
 * @param \Elgg\Hook $hook 'usersettings:save', 'user'
 *
 * @return void
 * @internal
 * @since 3.2
 */
function _elgg_admin_save_notification_setting(\Elgg\Hook $hook) {
	
	$user = $hook->getUserParam();
	if (!$user instanceof ElggUser || !$user->isAdmin()) {
		return;
	}
	
	$request = $hook->getParam('request');
	if (!$request instanceof \Elgg\Request) {
		return;
	}
	
	$value = (bool) $request->getParam('admin_validation_notification', true);
	$user->setPrivateSetting('admin_validation_notification', $value);
}

/**
 * Set the correct forward url after user registration
 *
 * @param \Elgg\Hook $hook 'response', 'action:register'
 *
 * @return void|ResponseBuilder
 * @internal
 * @since 3.2
 */
function _elgg_admin_set_registration_forward_url(\Elgg\Hook $hook) {
	
	$response = $hook->getValue();
	if (!$response instanceof ResponseBuilder) {
		return;
	}
	
	$session = elgg_get_session();
	if (!$session->get('admin_validation')) {
		return;
	}
	
	// if other plugins already have set forwarding, don't do anything
	if (!empty($response->getForwardURL()) && $response->getForwardURL() !== REFERER) {
		return;
	}
	
	$response->setForwardURL(elgg_generate_url('account:validation:pending'));
	
	return $response;
}

/**
 * Notify the user that their account is approved
 *
 * @param \Elgg\Event $event 'validate:after', 'user'
 *
 * @return void
 * @internal
 * @since 3.2
 */
function _elgg_admin_user_validation_notification(\Elgg\Event $event) {
	
	if (!(bool) elgg_get_config('require_admin_validation')) {
		return;
	}
	
	$user = $event->getObject();
	if (!$user instanceof ElggUser) {
		return;
	}
	
	$site = elgg_get_site_entity();
	
	$subject = elgg_echo('account:notification:validation:subject', [$site->getDisplayName()], $user->getLanguage());
	$body = elgg_echo('account:notification:validation:body', [
		$user->getDisplayName(),
		$site->getDisplayName(),
		$site->getURL(),
	], $user->getLanguage());
	
	$params = [
		'action' => 'account:validated',
		'object' => $user,
	];
	
	notify_user($user->guid, $site->guid, $subject, $body, $params, ['email']);
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events) {
	$events->registerHandler('init', 'system', '_elgg_admin_init');
};
