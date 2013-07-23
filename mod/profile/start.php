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

	elgg_register_plugin_hook_handler('entity:icon:url', 'user', 'profile_set_icon_url');
	elgg_unregister_plugin_hook_handler('entity:icon:url', 'user', 'user_avatar_hook');


	elgg_register_simplecache_view('icon/user/default/tiny');
	elgg_register_simplecache_view('icon/user/default/topbar');
	elgg_register_simplecache_view('icon/user/default/small');
	elgg_register_simplecache_view('icon/user/default/medium');
	elgg_register_simplecache_view('icon/user/default/large');
	elgg_register_simplecache_view('icon/user/default/master');

	elgg_register_page_handler('profile', 'profile_page_handler');

	elgg_extend_view('css/elgg', 'profile/css');
	elgg_extend_view('js/elgg', 'profile/js');

	// allow ECML in parts of the profile
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'profile_ecml_views_hook');

	// allow admins to set default widgets for users on profiles
	elgg_register_plugin_hook_handler('get_list', 'default_widgets', 'profile_default_widgets_hook');
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

	$action = NULL;
	if (isset($page[1])) {
		$action = $page[1];
	}

	if ($action == 'edit') {
		// use the core profile edit page
		$base_dir = elgg_get_root_path();
		require "{$base_dir}pages/profile/edit.php";
		return true;
	}

	$content = elgg_view('profile/layout', array('entity' => $user));
	$body = elgg_view_layout('one_column', array(
		'content' => $content
	));
	echo elgg_view_page($user->name, $body);
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
 * Use a URL for avatars that avoids loading Elgg engine for better performance
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string
 */
function profile_set_icon_url($hook, $type, $url, $params) {

	// if someone already set this, quit
	if ($url) {
		return;
	}

	$user = $params['entity'];
	$size = $params['size'];

	$user_guid = $user->getGUID();
	$icon_time = $user->icontime;

	if (!$icon_time) {
		return "_graphics/icons/user/default{$size}.gif";
	}

	$filehandler = new ElggFile();
	$filehandler->owner_guid = $user_guid;
	$filehandler->setFilename("profile/{$user_guid}{$size}.jpg");

	try {
		if ($filehandler->exists()) {
			$join_date = $user->getTimeCreated();
			return "mod/profile/icondirect.php?lastcache=$icon_time&joindate=$join_date&guid=$user_guid&size=$size";
		}
	} catch (InvalidParameterException $e) {
		elgg_log("Unable to get profile icon for user with GUID $user_guid", 'ERROR');
		return "_graphics/icons/default/$size.png";
	}
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
	$return[] = array(
		'name' => elgg_echo('profile'),
		'widget_context' => 'profile',
		'widget_columns' => 3,

		'event' => 'create',
		'entity_type' => 'user',
		'entity_subtype' => ELGG_ENTITIES_ANY_VALUE,
	);

	return $return;
}
