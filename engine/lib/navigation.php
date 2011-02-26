<?php
/**
 * Elgg navigation library
 * Functions for managing menus and other navigational elements
 *
 * @package Elgg.Core
 * @subpackage Navigation
 */

/**
 * Register an item for an Elgg menu
 *
 * @param string $menu_name The name of the menu: site, page, userhover,
 *                          userprofile, groupprofile, or any custom menu
 * @param mixed  $menu_item A ElggMenuItem object or an array of options in format:
 *                          name        => STR  Menu item identifier (required)
 *                          text        => STR  Menu item display text (required)
 *                          href        => STR  Menu item URL (required)
 *                          contexts    => ARR  Page context strings
 *                          section     => STR  Menu section identifier
 *                          title       => STR  Menu item tooltip
 *                          selected    => BOOL Is this menu item currently selected
 *                          parent_name => STR  Identifier of the parent menu item
 *
 *                          Custom options can be added as key value pairs.
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_register_menu_item($menu_name, $menu_item) {
	global $CONFIG;

	if (!isset($CONFIG->menus[$menu_name])) {
		$CONFIG->menus[$menu_name] = array();
	}

	if (is_array($menu_item)) {
		$item = ElggMenuItem::factory($menu_item);
		if (!$item) {
			elgg_log("Unable to add menu item '{$menu_item['name']}' to '$menu_name' menu", 'WARNING');
			elgg_log(print_r($menu_item, true), 'DEBUG');
			return false;
		}
	} else {
		$item = $menu_item;
	}

	$CONFIG->menus[$menu_name][] = $item;
	return true;
}

/**
 * Remove an item from a menu
 *
 * @param string $menu_name The name of the menu
 * @param string $item_name The unique identifier for this menu item
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_unregister_menu_item($menu_name, $item_name) {
	global $CONFIG;

	if (!isset($CONFIG->menus[$menu_name])) {
		return false;
	}

	foreach ($CONFIG->menus[$menu_name] as $index => $menu_object) {
		if ($menu_object->getName() == $item_name) {
			unset($CONFIG->menus[$menu_name][$index]);
			return true;
		}
	}

	return false;
}

/**
 * Check if a menu item has been registered
 *
 * @param string $menu_name The name of the menu
 * @param string $item_name The unique identifier for this menu item
 * 
 * @return bool
 * @since 1.8.0
 */
function elgg_is_menu_item_registered($menu_name, $item_name) {
	global $CONFIG;

	if (!isset($CONFIG->menus[$menu_name])) {
		return false;
	}

	foreach ($CONFIG->menus[$menu_name] as $index => $menu_object) {
		if ($menu_object->getName() == $item_name) {
			return true;
		}
	}

	return false;
}

/**
 * Adds a breadcrumb to the breadcrumbs stack.
 *
 * @param string $title The title to display
 * @param string $link  Optional. The link for the title.
 *
 * @return void
 * @since 1.8.0
 *
 * @link http://docs.elgg.org/Tutorials/UI/Breadcrumbs
 */
function elgg_push_breadcrumb($title, $link = NULL) {
	global $CONFIG;
	if (!is_array($CONFIG->breadcrumbs)) {
		$CONFIG->breadcrumbs = array();
	}

	// avoid key collisions.
	$CONFIG->breadcrumbs[] = array('title' => $title, 'link' => $link);
}

/**
 * Removes last breadcrumb entry.
 *
 * @return array popped item.
 * @since 1.8.0
 * @link http://docs.elgg.org/Tutorials/UI/Breadcrumbs
 */
function elgg_pop_breadcrumb() {
	global $CONFIG;

	if (is_array($CONFIG->breadcrumbs)) {
		array_pop($CONFIG->breadcrumbs);
	}

	return FALSE;
}

/**
 * Returns all breadcrumbs as an array of array('title' => 'Readable Title', 'link' => 'URL')
 *
 * @return array Breadcrumbs
 * @since 1.8.0
 * @link http://docs.elgg.org/Tutorials/UI/Breadcrumbs
 */
function elgg_get_breadcrumbs() {
	global $CONFIG;

	return (is_array($CONFIG->breadcrumbs)) ? $CONFIG->breadcrumbs : array();
}

/**
 * Set up the site menu
 *
 * Handles default, featured, and custom menu items
 *
 * @param string $hook
 * @param string $type
 * @param array $return Menu array
 * @param array $params
 * @return array
 */
function elgg_site_menu_setup($hook, $type, $return, $params) {

	$featured_menu_names = elgg_get_config('site_featured_menu_names');
	$custom_menu_items = elgg_get_config('site_custom_menu_items');
	if ($featured_menu_names || $custom_menu_items) {
		// we have featured or custom menu items

		$registered = $return['default'];

		// set up featured menu items
		$featured = array();
		foreach ($featured_menu_names as $name) {
			foreach ($registered as $index => $item) {
				if ($item->getName() == $name) {
					$featured[] = $item;
					unset($registered[$index]);
				}
			}
		}

		// add custom menu items
		$n = 1;
		foreach ($custom_menu_items as $title => $url) {
			$item = new ElggMenuItem("custom$n", $title, $url);
			$featured[] = $item;
			$n++;
		}

		$return['default'] = $featured;
		$return['more'] = $registered;
	} else {
		// no featured menu items set
		$max_display_items = 5;

		// the first n are shown, rest added to more list
		$num_menu_items = count($return['default']);
		if ($num_menu_items > $max_display_items) {
			$return['more'] =  array_splice($return['default'], $max_display_items);
		}
	}

	return $return;
}

/**
 * Navigation initialization
 */
function elgg_nav_init() {
	elgg_register_plugin_hook_handler('prepare', 'menu:site', 'elgg_site_menu_setup');
}

elgg_register_event_handler('init', 'system', 'elgg_nav_init');
