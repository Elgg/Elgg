<?php
/**
 * Elgg profile plugin
 *
 * @package ElggProfile
 */

/**
 * Profile init function
 *
 * @return void
 */
function profile_init() {
	elgg_extend_view('elgg.css', 'profile/profile.css');
	
	elgg_register_ajax_view('forms/profile/fields/add');

	// allow ECML in parts of the profile
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'profile_ecml_views_hook');

	// allow admins to set default widgets for users on profiles
	elgg_register_plugin_hook_handler('get_list', 'default_widgets', 'profile_default_widgets_hook');
	
	elgg_register_plugin_hook_handler('register', 'menu:topbar', '_profile_topbar_menu');
	elgg_register_plugin_hook_handler('register', 'menu:title', '_profile_title_menu');
	elgg_register_plugin_hook_handler('register', 'menu:page', '_profile_admin_page_menu');
	elgg_register_plugin_hook_handler('register', 'menu:page', '_profile_user_page_menu');
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', '_profile_user_hover_menu');
	elgg_register_plugin_hook_handler('search:fields', 'user', \Elgg\Search\UserSearchProfileFieldsHandler::class);
}

/**
 * Parse ECML on parts of the profile
 *
 * @param string $hook         'get_views'
 * @param string $type         'ecml'
 * @param array  $return_value current return value
 *
 * @return array
 */
function profile_ecml_views_hook($hook, $type, $return_value) {
	$return_value['profile/profile_content'] = elgg_echo('profile');

	return $return_value;
}

/**
 * Register profile widgets with default widgets
 *
 * @param string $hook   'get_list'
 * @param string $type   'default_widgets'
 * @param array  $return current return value
 *
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
 * This function loads a set of default fields into the profile, then triggers a hook letting other plugins to edit
 * add and delete fields.
 *
 * Note: This is a secondary system:init call and is run at a super low priority to guarantee that it is called after all
 * other plugins have initialised.
 *
 * @return void
 *
 * @access private
 */
function _profile_fields_setup() {
	$profile_defaults =  [
		'description' => 'longtext',
		'briefdescription' => 'text',
		'location' => 'location',
		'interests' => 'tags',
		'skills' => 'tags',
		'contactemail' => 'email',
		'phone' => 'text',
		'mobile' => 'text',
		'website' => 'url',
		'twitter' => 'text',
	];

	$loaded_defaults = [];
	$fieldlist = elgg_get_config('profile_custom_fields');
	if ($fieldlist || $fieldlist === '0') {
		$fieldlistarray = explode(',', $fieldlist);
		foreach ($fieldlistarray as $listitem) {
			if ($translation = elgg_get_config("admin_defined_profile_{$listitem}")) {
				$type = elgg_get_config("admin_defined_profile_type_{$listitem}");
				$loaded_defaults["admin_defined_profile_{$listitem}"] = $type;
				add_translation(get_current_language(), ["profile:admin_defined_profile_{$listitem}" => $translation]);
			}
		}
	}

	if (count($loaded_defaults)) {
		elgg_set_config('profile_using_custom', true);
		$profile_defaults = $loaded_defaults;
	}
	
	$profile_fields = elgg_trigger_plugin_hook('profile:fields', 'profile', null, $profile_defaults);
	elgg_set_config('profile_fields', $profile_fields);

	// register any tag metadata names
	foreach ($profile_fields as $name => $type) {
		if ($type == 'tags' || $type == 'location' || $type == 'tag') {
			elgg_register_tag_metadata_name($name);
			// register a tag name translation
			add_translation(get_current_language(), ["tag_names:$name" => elgg_echo("profile:$name")]);
		}
	}
}

/**
 * Register menu items for the topbar menu
 *
 * @param \Elgg\Hook $hook 'register' 'menu:topbar'
 *
 * @return void|ElggMenuItem[]
 *
 * @access private
 * @since 3.0
 */
function _profile_topbar_menu(\Elgg\Hook $hook) {

	$viewer = elgg_get_logged_in_user_entity();
	if (!$viewer) {
		 return;
	}
	$return = $hook->getValue();
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'profile',
		'href' => $viewer->getURL(),
		'text' => elgg_echo('profile'),
		'icon' => 'user',
		'parent_name' => 'account',
		'section' => 'alt',
		'priority' => 100,
	]);
	
	return $return;
}

/**
 * Register menu items for the user hover menu
 *
 * @param \Elgg\Hook $hook 'register' 'menu:user_hover'
 *
 * @return void|ElggMenuItem[]
 *
 * @access private
 * @since 3.0
 */
function _profile_user_hover_menu(\Elgg\Hook $hook) {

	if (!elgg_is_logged_in()) {
		return;
	}
	
	$user = $hook->getEntityParam();
	if (!($user instanceof \ElggUser) || !$user->canEdit()) {
		return;
	}
	
	$return = $hook->getValue();
	$return[] = ElggMenuItem::factory([
		'name' => 'profile:edit',
		'text' => elgg_echo('profile:edit'),
		'icon' => 'address-card',
		'href' => elgg_generate_entity_url($user, 'edit'),
		'section' => (elgg_get_logged_in_user_guid() == $user->guid) ? 'action' : 'admin',
	]);
	
	return $return;
}

/**
 * Register menu items for the admin page menu
 *
 * @param \Elgg\Hook $hook 'register' 'menu:page'
 *
 * @return void|ElggMenuItem[]
 *
 * @access private
 * @since 3.0
 */
function _profile_admin_page_menu(\Elgg\Hook $hook) {

	if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
		return;
	}
	
	$return = $hook->getValue();
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'configure_utilities:profile_fields',
		'text' => elgg_echo('admin:configure_utilities:profile_fields'),
		'href' => 'admin/configure_utilities/profile_fields',
		'section' => 'configure',
		'parent_name' => 'configure_utilities',
	]);
	
	return $return;
}

/**
 * Register menu items for the page menu
 *
 * @param \Elgg\Hook $hook 'register' 'menu:page'
 *
 * @return void|ElggMenuItem
 *
 * @access private
 * @since 3.0
 */
function _profile_user_page_menu(\Elgg\Hook $hook) {

	$owner = elgg_get_page_owner_entity();
	if (!$owner instanceof \ElggUser) {
		return;
	}
	
	$return = $hook->getValue();
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'edit_profile',
		'href' => elgg_generate_entity_url($owner, 'edit'),
		'text' => elgg_echo('profile:edit'),
		'section' => '1_profile',
		'contexts' => ['settings'],
	]);
	
	return $return;
}

/**
 * Register menu items for the title menu
 *
 * @param \Elgg\Hook $hook 'register' 'menu:title'
 *
 * @return void|ElggMenuItem[]
 *
 * @access private
 * @since 3.0
 */
function _profile_title_menu(\Elgg\Hook $hook) {

	$user = $hook->getEntityParam();
	if (!($user instanceof \ElggUser) || !$user->canEdit()) {
		return;
	}
	
	$return = $hook->getValue();
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'edit_profile',
		'href' => elgg_generate_entity_url($user, 'edit'),
		'text' => elgg_echo('profile:edit'),
		'icon' => 'address-card',
		'class' => ['elgg-button', 'elgg-button-action'],
		'contexts' => ['profile', 'profile_edit'],
	]);
	
	return $return;
}

return function() {
	elgg_register_event_handler('init', 'system', 'profile_init', 1);
	elgg_register_event_handler('init', 'system', '_profile_fields_setup', 10000); // Ensure this runs after other plugins
};
