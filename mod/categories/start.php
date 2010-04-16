<?php

/**
 * Elgg categories plugin
 *
 * @package ElggCategories
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

/**
 * Initialise categories plugin
 *
 */
function categories_init() {

	global $CONFIG;

	elgg_extend_view('css', 'categories/css');

	register_action('categories/save',false,$CONFIG->pluginspath . 'categories/actions/save.php',true);

	register_page_handler('categories', 'categories_page_handler');

}

/**
 * Set up admin menu item
 *
 */
function categories_pagesetup() {
	if (get_context() == 'admin' && isadminloggedin()) {
		global $CONFIG;
		add_submenu_item(elgg_echo('categories:settings'), $CONFIG->wwwroot . 'mod/categories/settings.php');
	}
}

/**
 * Page handler
 *
 */
function categories_page_handler() {

	include(dirname(__FILE__) . "/listing.php");
	return true;
}

/**
 * Save site categories
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

	return true;
}


register_elgg_event_handler('init','system','categories_init');
register_elgg_event_handler('pagesetup','system','categories_pagesetup');
register_elgg_event_handler('update','all','categories_save');
register_elgg_event_handler('create','all','categories_save');
