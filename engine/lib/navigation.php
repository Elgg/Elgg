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
 *                          title       => STR  Menu item title (required)
 *                          url         => STR  Menu item URL (required)
 *                          contexts    => ARR  Page context strings
 *                          section     => STR  Menu section identifier
 *                          tooltip     => STR  Menu item tooltip
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
		$menu_item = ElggMenuItem::factory($menu_item);
		if (!$menu_item) {
			return false;
		}
	}

	$CONFIG->menus[$menu_name][] = $menu_item;
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
		if ($menu_object->name == $item_name) {
			unset($CONFIG->menus[$menu_name][$index]);
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
 * Deprecated by elgg_register_menu_item(). Set $menu_name to 'page'.
 *
 * @see elgg_register_menu_item()
 * @deprecated 1.8
 *
 * @param string  $label    The label
 * @param string  $link     The link
 * @param string  $group    The group to store item in
 * @param boolean $onclick  Add a confirmation when clicked?
 * @param boolean $selected Is menu item selected
 *
 * @return bool
 */
function add_submenu_item($label, $link, $group = 'default', $onclick = false, $selected = NULL) {
	elgg_deprecated_notice('add_submenu_item was deprecated by elgg_register_menu_item', 1.8);

	// submenu items were added in the page setup hook usually by checking
	// the context.  We'll pass in the current context here, which will
	// emulate that effect.
	// if context == 'main' (default) it probably means they always wanted
	// the menu item to show up everywhere.
	$context = elgg_get_context();

	if ($context == 'main') {
		$context = 'all';
	}

	$item = array(
		'name' => $label,
		'title' => $label,
		'url' => $link,
		'context' => $context,
		'section' => $group,
	);

	if ($selected) {
		$item['selected'] = true;
	}

	if ($onclick) {
		$js = "onclick=\"javascript:return confirm('" . elgg_echo('deleteconfirm') . "')\"";
		$item['vars'] = array('js' => $js);
	}

	return elgg_register_menu_item('page', $item);
}

/**
 * Use elgg_view_menu(). Set $menu_name to 'owner_block'.
 *
 * @see elgg_view_menu()
 * @deprecated 1.8
 *
 * @return string
 */
function get_submenu() {
	elgg_deprecated_notice("get_submenu() has been deprecated by elgg_view_menu()", 1.8);
	return elgg_view_menu('owner_block', array(
		'entity' => $owner,
		'class' => 'elgg-owner-block-menu',
	));
}

/**
 * Adds an item to the site-wide menu.
 *
 * You can obtain the menu array by calling {@link get_register('menu')}
 *
 * @param string $menu_name     The name of the menu item
 * @param string $menu_url      The URL of the page
 * @param array  $menu_children Optionally, an array of submenu items (not used)
 * @param string $context       (not used)
 *
 * @return true|false Depending on success
 * @deprecated 1.8 use elgg_register_menu_item() for the menu 'site'
 */
function add_menu($menu_name, $menu_url, $menu_children = array(), $context = "") {
	elgg_deprecated_notice('add_menu() deprecated by elgg_register_menu_item()', 1.8);

	return elgg_register_menu_item('site', array(
		'name' => $menu_name,
		'title' => $menu_name,
		'url' => $menu_url,
	));
}

/**
 * Removes an item from the menu register
 *
 * @param string $menu_name The name of the menu item
 *
 * @return true|false Depending on success
 * @deprecated 1.8
 */
function remove_menu($menu_name) {
	elgg_deprecated_notice("remove_menu() deprecated by elgg_unregister_menu_item()", 1.8);
	return elgg_unregister_menu_item('site', $menu_name);
}

/**
 * Returns a menu item for use in the children section of add_menu()
 * This is not currently used in the Elgg core.
 *
 * @param string $menu_name The name of the menu item
 * @param string $menu_url  Its URL
 *
 * @return stdClass|false Depending on success
 * @deprecated 1.7
 */
function menu_item($menu_name, $menu_url) {
	elgg_deprecated_notice('menu_item() is deprecated by add_submenu_item', 1.7);
	return make_register_object($menu_name, $menu_url);
}
