<?php
/**
 * Elgg categories plugin
 *
 * @package ElggCategories
 */

elgg_register_event_handler('init', 'system', 'categories_init');

/**
 * Initialise categories plugin
 *
 */
function categories_init() {

	elgg_extend_view('css/elgg', 'categories/css');

	elgg_register_page_handler('categories', 'categories_page_handler');

	elgg_register_event_handler('update', 'all', 'categories_save');
	elgg_register_event_handler('create', 'all', 'categories_save');

	// To keep the category plugins in the settings area and because we have to do special stuff,
	// handle saving ourself.
	elgg_register_plugin_hook_handler('action', 'plugins/settings/save', 'categories_save_site_categories');
}


/**
 * Category page handler
 * @return bool
 */
function categories_page_handler() {
	include(dirname(__FILE__) . "/pages/categories/listing.php");
	return true;
}

/**
 * Save categories to object upon save / edit
 *
 */
function categories_save($event, $object_type, $object) {
	if ($object instanceof ElggEntity) {
		$marker = get_input('universal_category_marker');

		if ($marker == 'on') {
			$categories = get_input('universal_categories_list');

			if (empty($categories)) {
				$categories = array();
			}

			$object->universal_categories = $categories;
		}
	}
	return TRUE;
}

/**
 * Saves the site categories.
 *
 * @param type $hook
 * @param type $type
 * @param type $value
 * @param type $params
 */
function categories_save_site_categories($hook, $type, $value, $params) {
	$plugin_id = get_input('plugin_id');
	if ($plugin_id != 'categories') {
		return $value;
	}

	$categories = get_input('categories');
	$categories = string_to_tag_array($categories);

	$site = elgg_get_site_entity();
	$site->categories = $categories;
	system_message(elgg_echo("categories:save:success"));

	elgg_delete_admin_notice('categories_admin_notice_no_categories');

	forward(REFERER);
}
