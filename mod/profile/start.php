<?php
/**
 * Elgg profile plugin
 *
 * @package ElggProfile
 */

/**
 * Profile init function; sets up the profile functions
 *
 */
function profile_init() {
	global $CONFIG;

	require_once 'profile_lib.php';

	// Register a URL handler for users - this means that profile_url()
	// will dictate the URL for all ElggUser objects
	register_entity_url_handler('profile_url', 'user', 'all');

	//if (isloggedin()) {
	//	add_menu(elgg_echo('profile:yours'), get_loggedin_user()->getURL() . '/extend');
	//}

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
	register_page_handler('icon', 'profile_icon_handler');
	register_page_handler('iconjs', 'profile_iconjs_handler');

	// Add Javascript reference to the page header
	elgg_extend_view('html_head/extend', 'profile/metatags');
	elgg_extend_view('css/screen', 'profile/css');
	elgg_extend_view('js/elgg', 'profile/javascript');

	// Now override icons
	elgg_register_plugin_hook_handler('entity:icon:url', 'user', 'profile_usericon_hook');

	// allow ECML in parts of the profile
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'profile_ecml_views_hook');

	// default profile fields admin item
	elgg_add_admin_submenu_item('defaultprofile', elgg_echo('profile:edit:default'), 'appearance');
}

/**
 * This function loads a set of default fields into the profile, then triggers a hook letting other plugins to edit
 * add and delete fields.
 *
 * Note: This is a secondary system:init call and is run at a super low priority to guarantee that it is called after all
 * other plugins have initialised.
 */
function profile_fields_setup() {
	global $CONFIG;

	$profile_defaults = array (
		'description' => 'longtext',
		'briefdescription' => 'text',
		'location' => 'tags',
		'interests' => 'tags',
		'skills' => 'tags',
		'contactemail' => 'email',
		'phone' => 'text',
		'mobile' => 'text',
		'website' => 'url',
		'twitter' => 'text'
	);

	$loaded_default = array();
	if ($fieldlist = get_plugin_setting('user_defined_fields','profile')) {
		if (!empty($fieldlist)) {
			$fieldlistarray = explode(',',$fieldlist);
			$loaded_defaults = array();
			foreach($fieldlistarray as $listitem) {
				if ($translation = get_plugin_setting("admin_defined_profile_{$listitem}", 'profile')) {
					$type = get_plugin_setting("admin_defined_profile_type_{$listitem}", 'profile');
					$loaded_defaults["admin_defined_profile_{$listitem}"] = $type;
					add_translation(get_current_language(), array("profile:admin_defined_profile_{$listitem}" => $translation));
				}
			}
		}
	}

	if (count($loaded_defaults)) {
		$CONFIG->profile_using_custom = true;
		$profile_defaults = $loaded_defaults;
	}

	$CONFIG->profile = elgg_trigger_plugin_hook('profile:fields', 'profile', NULL, $profile_defaults);

	// register any tag metadata names
	foreach ($CONFIG->profile as $name => $type) {
		if ($type == 'tags') {
			elgg_register_tag_metadata_name($name);
			// register a tag name translation
			add_translation(get_current_language(), array("tag_names:$name" => elgg_echo("profile:$name")));
		}
	}
}

/**
 * Profile page handler
 *
 * @param array $page Array of page elements, forwarded by the page handling mechanism
 */
function profile_page_handler($page) {
	global $CONFIG;

	$action = NULL;

	// short circuit if invalid or banned username
	if (isset($page[0])) {
		$username = $page[0];
		$user = get_user_by_username($username);
		set_input('username', $page[0]);
	}

	if (!$user || ($user->isBanned() && !isadminloggedin())) {
		register_error(elgg_echo('profile:notfound'));
		forward();
	}

	if (isset($page[1])) {
		$action = $page[1];
	}

	switch ($action) {
		case 'edit':
			require $CONFIG->path . 'pages/profile/edit.php';
			return;
			/*
			$layout = 'one_column_with_sidebar';

			if (!$user || !$user->canEdit()) {
				register_error(elgg_echo("profile:noaccess"));
				forward();
			}

			$content = profile_get_user_edit_content($user, $page);
			$content = elgg_view_layout($layout, array('content' => $content));
			 *
			 */
			break;

		default:
			$layout = 'one_column';
			if (isset($page[1])) {
				$section = $page[1];
			} else {
				$section = 'activity';
			}
			$content = profile_get_user_profile_html($user, $section);
			$content = elgg_view_layout($layout, array('content' => $content));
			break;
	}

	echo elgg_view_page($title, $content);
	return;
}

/**
 * Pagesetup function
 *
 */
function profile_pagesetup()
{
	global $CONFIG;

	//add submenu options
	if (elgg_get_context() == "profile") {
		$page_owner = elgg_get_page_owner();
		if ($page_owner && $page_owner->canEdit()) {
			add_submenu_item(elgg_echo('profile:editdetails'), "pg/profile/{$page_owner->username}/edit/details");
			add_submenu_item(elgg_echo('profile:editicon'), "pg/profile/{$page_owner->username}/edit/icon");
		}
	}
}

/**
 * Profile icon page handler
 *
 * @param array $page Array of page elements, forwarded by the page handling mechanism
 */
function profile_icon_handler($page) {
	global $CONFIG;

	// The username should be the file we're getting
	if (isset($page[0])) {
		set_input('username',$page[0]);
	}
	if (isset($page[1])) {
		set_input('size',$page[1]);
	}
	// Include the standard profile index
	include($CONFIG->pluginspath . "profile/icon.php");
}

/**
 * Icon JS
 */
function profile_iconjs_handler($page) {
	global $CONFIG;
	include($CONFIG->pluginspath . "profile/javascript.php");
}

/**
 * Profile URL generator for $user->getUrl();
 *
 * @param ElggUser $user
 * @return string User URL
 */
function profile_url($user) {
	global $CONFIG;
	return elgg_get_site_url() . "pg/profile/" . $user->username;
}

/**
 * This hooks into the getIcon API and provides nice user icons for users where possible.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 * @return unknown
 */
function profile_usericon_hook($hook, $entity_type, $returnvalue, $params){
	global $CONFIG;
	if ((!$returnvalue) && ($hook == 'entity:icon:url') && ($params['entity'] instanceof ElggUser)){
		$entity = $params['entity'];
		$type = $entity->type;
		$subtype = get_subtype_from_id($entity->subtype);
		$viewtype = $params['viewtype'];
		$size = $params['size'];
		$username = $entity->username;

		if ($icontime = $entity->icontime) {
			$icontime = "{$icontime}";
		} else {
			$icontime = "default";
		}

		if ($entity->isBanned()) {
			return elgg_view('icon/user/default/'.$size);
		}

		$filehandler = new ElggFile();
		$filehandler->owner_guid = $entity->getGUID();
		$filehandler->setFilename("profile/" . $entity->guid . $size . ".jpg");

		if ($filehandler->exists()) {
			//$url = "pg/icon/$username/$size/$icontime.jpg";
			return 'mod/profile/icondirect.php?lastcache='.$icontime.'&joindate=' . $entity->time_created . '&guid=' . $entity->guid . '&size='.$size;
		}
	}
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

// Make sure the profile initialisation function is called on initialisation
elgg_register_event_handler('init','system','profile_init',1);
elgg_register_event_handler('init','system','profile_fields_setup', 10000); // Ensure this runs after other plugins

elgg_register_event_handler('pagesetup','system','profile_pagesetup');
elgg_register_event_handler('profileupdate','all','object_notifications');


// Register actions
global $CONFIG;
elgg_register_action("profile/edit", $CONFIG->pluginspath . "profile/actions/edit.php");
elgg_register_action("profile/iconupload", $CONFIG->pluginspath . "profile/actions/iconupload.php");
elgg_register_action("profile/cropicon", $CONFIG->pluginspath . "profile/actions/cropicon.php");
elgg_register_action("profile/editdefault", $CONFIG->pluginspath . "profile/actions/editdefault.php", 'admin');
elgg_register_action("profile/editdefault/delete", $CONFIG->pluginspath . "profile/actions/deletedefaultprofileitem.php", 'admin');
elgg_register_action("profile/editdefault/reset", $CONFIG->pluginspath . "profile/actions/resetdefaultprofile.php", 'admin');
elgg_register_action("profile/editdefault/reorder", $CONFIG->pluginspath . "profile/actions/reorder.php", 'admin');
elgg_register_action("profile/editdefault/editfield", $CONFIG->pluginspath . "profile/actions/editfield.php", 'admin');
elgg_register_action("profile/addcomment", $CONFIG->pluginspath . "profile/actions/addcomment.php");
elgg_register_action("profile/deletecomment", $CONFIG->pluginspath . "profile/actions/deletecomment.php");
