<?php
/**
 * Elgg profile plugin
 *
 * @package ElggProfile
 */

elgg_register_event_handler('init', 'system', 'profile_init', 1);

// Metadata on users needs to be independent
// outside of init so it happens earlier in boot. See #3316
register_metadata_as_independent('user');

/**
 * Profile init function
 */
function profile_init() {

	// Register a URL handler for users
	elgg_register_plugin_hook_handler('entity:url', 'user', 'profile_set_url');

	elgg_register_page_handler('profile', 'profile_page_handler');

	elgg_extend_view('elgg.css', 'profile/css');

	// allow ECML in parts of the profile
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'profile_ecml_views_hook');

	// allow admins to set default widgets for users on profiles
	elgg_register_plugin_hook_handler('get_list', 'default_widgets', 'profile_default_widgets_hook');
	
	elgg_register_plugin_hook_handler('register', 'menu:topbar', '_profile_topbar_menu');
	elgg_register_plugin_hook_handler('register', 'menu:title', '_profile_title_menu');
}

/**
 * Profile page handler
 *
 * @param array $page Array of URL segments passed by the page handling mechanism
 * @return bool
 */
function profile_page_handler($page) {

	if (isset($page[0])) {
		$username = $page[0];
		$user = get_user_by_username($username);
		elgg_set_page_owner_guid($user->guid);
	} elseif (elgg_is_logged_in()) {
		forward(elgg_get_logged_in_user_entity()->getURL());
	}

	// short circuit if invalid or banned username
	if (!$user || ($user->isBanned() && !elgg_is_admin_logged_in())) {
		register_error(elgg_echo('profile:notfound'));
		forward();
	}

	$action = null;
	if (isset($page[1])) {
		$action = $page[1];
	}
	
	if ($action == 'edit') {
		// use the core profile edit page
		echo elgg_view_resource('profile/edit');
		return true;
	}

	echo elgg_view_resource('profile/view', [
		'username' => $page[0],
	]);
	return true;
}

/**
 * Profile URL generator for $user->getUrl();
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string
 */
function profile_set_url($hook, $type, $url, $params) {
	$user = $params['entity'];
	return "profile/" . $user->username;
}

/**
 * Parse ECML on parts of the profile
 *
 * @param string $hook
 * @param string $entity_type
 * @param array  $return_value
 * @return array
 */
function profile_ecml_views_hook($hook, $entity_type, $return_value) {
	$return_value['profile/profile_content'] = elgg_echo('profile');

	return $return_value;
}

/**
 * Register profile widgets with default widgets
 *
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @return array
 */
function profile_default_widgets_hook($hook, $type, $return) {
	$return[] = [
		'name' => elgg_echo('profile'),
		'widget_context' => 'profile',
		'widget_columns' => 2,

		'event' => 'create',
		'entity_type' => 'user',
		'entity_subtype' => ELGG_ENTITIES_ANY_VALUE,
	];

	return $return;
}

/**
 * Register menu items for the topbar menu
 *
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @param array  $params
 * @return array
 *
 * @access private
 *
 * @since 3.0
 */
function _profile_topbar_menu($hook, $type, $return, $params) {

	$viewer = elgg_get_logged_in_user_entity();
	if (!$viewer) {
		 return;
	}

	$return[] = \ElggMenuItem::factory([
		'name' => 'profile',
		'href' => $viewer->getURL(),
		'text' => $viewer->name,
		'title' => elgg_echo('profile'),
		'icon' => elgg_view('output/img', [
			'src' => $viewer->getIconURL('topbar'),
			'alt' => $viewer->name,
			'class' => 'elgg-border-plain elgg-transition',
		]),
		'priority' => 100,
	]);
	
	return $return;
}

/**
 * Register menu items for the title menu
 *
 * @param string $hook   Hook
 * @param string $type   Type
 * @param array  $return Current return value
 * @param array  $params Hook parameters
 * @return array
 *
 * @access private
 *
 * @since 3.0
 */
function _profile_title_menu($hook, $type, $return, $params) {

	if (!elgg_in_context('profile') || elgg_in_context('profile_edit')) {
		return;
	}
	
	$user = elgg_get_page_owner_entity();
	
	// grab the actions and admin menu items from user hover
	$menu = elgg()->menus->getMenu('user_hover', [
		'entity' => $user,
		'username' => $user->username,
	]);
	
	$actions = $menu->getSection('action', []);
	foreach ($actions as $action) {
		$action->addLinkClass('elgg-button elgg-button-action');
		$return[] = $action;
	}
	
	return $return;
}
