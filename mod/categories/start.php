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

	$action_base = elgg_get_plugins_path() . 'categories/actions';
	elgg_register_action('settings/categories/save', "$action_base/save.php", 'admin');

	elgg_register_page_handler('categories', 'categories_page_handler');

	elgg_register_event_handler('update', 'all', 'categories_save');
	elgg_register_event_handler('create', 'all', 'categories_save');
}


/**
 * Page handler
 *
 */
function categories_page_handler() {
	include(dirname(__FILE__) . "/listing.php");
	return TRUE;
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
 * Add a reminder to set default categories.
 */
function categories_on_activate() {
	$site = elgg_get_site_entity();
	
	if (!$site->categories) {
		$url = elgg_normalize_url('admin/plugin_settings/categories');
		$message = elgg_echo('categories:on_enable_reminder', array($url));
		elgg_add_admin_notice('categories_admin_notice_no_categories', $message);
	}
	return TRUE;
}

/**
 * Clean up admin notices on disable.
 */
function categories_on_deactivate() {
	elgg_delete_admin_notice('categories_admin_notice_no_categories');
}
