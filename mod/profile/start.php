<?php
/**
 * Elgg profile plugin
 *
 * @package ElggProfile
 */

elgg_register_event_handler('init', 'system', 'profile_init', 1);

/**
 * Profile init function; sets up the profile functions
 *
 */
function profile_init() {
	global $CONFIG;

	// Register a URL handler for users - this means that profile_url()
	// will dictate the URL for all ElggUser objects
	register_entity_url_handler('profile_url', 'user', 'all');

	// Metadata on users needs to be independent
	register_metadata_as_independent('user');

	elgg_view_register_simplecache('icon/user/default/tiny');
	elgg_view_register_simplecache('icon/user/default/topbar');
	elgg_view_register_simplecache('icon/user/default/small');
	elgg_view_register_simplecache('icon/user/default/medium');
	elgg_view_register_simplecache('icon/user/default/large');
	elgg_view_register_simplecache('icon/user/default/master');

	// Register a page handler, so we can have nice URLs
	register_page_handler('profile', 'profile_page_handler');

	elgg_extend_view('html_head/extend', 'profile/metatags');
	elgg_extend_view('css/screen', 'profile/css');
	elgg_extend_view('js/elgg', 'profile/javascript');

	// Register actions
	elgg_register_action("profile/addcomment", $CONFIG->pluginspath . "profile/actions/addcomment.php");
	elgg_register_action("profile/deletecomment", $CONFIG->pluginspath . "profile/actions/deletecomment.php");

	elgg_register_event_handler('profileupdate', 'all', 'object_notifications');
	
	// allow ECML in parts of the profile
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'profile_ecml_views_hook');
}

/**
 * Profile page handler
 *
 * @param array $page Array of page elements, forwarded by the page handling mechanism
 */
function profile_page_handler($page) {
	global $CONFIG;

	if (isset($page[0])) {
		$username = $page[0];
		$user = get_user_by_username($username);
		elgg_set_page_owner_guid($user->guid);
	}

	// short circuit if invalid or banned username
	if (!$user || ($user->isBanned() && !isadminloggedin())) {
		register_error(elgg_echo('profile:notfound'));
		forward();
	}

	$action = NULL;
	if (isset($page[1])) {
		$action = $page[1];
	}

	switch ($action) {
		case 'edit':
			// use for the core profile edit page
			require $CONFIG->path . 'pages/profile/edit.php';
			return;
			break;

		default:
			if (isset($page[1])) {
				$section = $page[1];
			} else {
				$section = 'activity';
			}
			$content = profile_get_user_profile_html($user, $section);
			$content = elgg_view_layout('one_column', array('content' => $content));
			break;
	}

	echo elgg_view_page($title, $content);
}

/**
 * Returns the html for a user profile.
 *
 * @param string $username The username of the profile to display
 * @param string $section Which section is currently selected.
 *
 * @todo - This should really use a plugin hook to get the list of plugin tabs
 *
 * @return mixed FALSE or html for the profile.
 */
function profile_get_user_profile_html($user, $section = 'activity') {
	$body = elgg_view('profile/tab_navigation', array('section' => $section, 'entity' => $user));
	$view_options = array('entity' => $user);

	$content = elgg_view("profile/tabs/$section", $view_options);

	$body .= elgg_view('profile/content_wrapper', array('content' => $content));

	$body .= elgg_view('profile/sidebar', array('section' => $section));
	return $body;
}

/**
 * Profile URL generator for $user->getUrl();
 *
 * @param ElggUser $user
 * @return string User URL
 */
function profile_url($user) {
	return elgg_get_site_url() . "pg/profile/" . $user->username;
}

/**
 * Parse ECML on parts of the profile
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $return_value
 * @param unknown_type $params
 */
function profile_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['profile/profile_content'] = elgg_echo('profile');

	return $return_value;
}
