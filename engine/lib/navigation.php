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
 * Deprecated by elgg_add_submenu_item()
 *
 * @see elgg_add_submenu_item()
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
	elgg_deprecated_notice('add_submenu_item was deprecated by elgg_add_submenu_item', 1.8);

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
/*
	$item = array(
		'text' => $label,
		'href' => $link,
		'selected' => $selected
	);

	return elgg_add_submenu_item($item, $context, $group);
 * 
 */
}

/**
 * Add an entry to the submenu.
 *
 * @param array  $item    The item as:
 * <code>
 * array(
 * 	'title' => 'Text to display',
 * 	'url' => 'URL of the link',
 * 	'id' => 'entry_unique_id' //used by children items to identify parents
 * 	'parent_id' => 'id_of_parent',
 * 	'selected' => BOOL // Is this item selected? (If NULL or unset will attempt to guess)
 * 	'vars' => array() // Array of vars to pass to the navigation/submenu_item view
 * )
 * </code>
 *
 * @param string $context Context in which to display this menu item.  'all'
 *                        will make it show up all the time. Use sparingly.
 * @param string $group   Group for the item. Each submenu group has its own <ul>
 *
 * @return BOOL
 * @since 1.8
 * @see elgg_prepare_submenu
 */
function elgg_add_submenu_item(array $item, $context = 'all', $group = 'default') {
	global $CONFIG;

	if (!isset($CONFIG->submenu_items)) {
		$CONFIG->submenu_items = array();
	}

	if (!isset($CONFIG->submenu_items[$context])) {
		$CONFIG->submenu_items[$context] = array();
	}

	if (!isset($CONFIG->submenu_items[$context][$group])) {
		$CONFIG->submenu_items[$context][$group] = array();
	}

	if (!isset($item['text'])) {
		return FALSE;
	}
	
	if (!empty($item['href'])) {
		$item['href'] = elgg_normalize_url($item['href']);
	}

	// we use persistent object properties in the submenu
	// setup function, so normalize the array to an object.
	// we pass it in as an array because this would be the only
	// place in elgg that we ask for an object like this.
	// consistency ftw.
	$item_obj = new StdClass();

	foreach ($item as $k => $v) {
		switch ($k) {
			case 'parent_id':
			case 'id':
				// make sure '' and false make sense
				$v = (empty($v)) ? NULL : $v;

			default:
				$item_obj->$k = $v;
				break;
		}
	}

	$CONFIG->submenu_items[$context][$group][] = $item_obj;

	return TRUE;
}

/**
 * Properly nest all submenu entries for contexts $context and 'all'
 *
 * @param string $context Context for menus
 * @param bool   $sort    Sort the menu items alphabetically
 *
 * @since 1.8
 * @see elgg_add_submenu_item
 *
 * @return true
 */
function elgg_prepare_submenu($context = 'main', $sort = FALSE) {
	global $CONFIG;

	if (!isset($CONFIG->submenu_items) || !($CONFIG->submenu_items)) {
		return FALSE;
	}

	$groups = array();

	if (isset($CONFIG->submenu_items['all'])) {
		$groups = $CONFIG->submenu_items['all'];
	}

	if (isset($CONFIG->submenu_items[$context])) {
		$groups = array_merge_recursive($groups, $CONFIG->submenu_items[$context]);
	}

	if (!$groups) {
		return FALSE;
	}

	foreach ($groups as $group => $items) {
		if ($sort) {
			usort($items, 'elgg_submenu_item_cmp');
		}

		$parsed_menu = array();
		// determin which children need to go in this item.
		foreach ($items as $i => $item) {
			// can only support children if there's an id
			if (isset($item->id)) {
				foreach ($items as $child_i => $child_item) {
					// don't check ourselves or used children.
					if ($child_i == $i || $child_item->used == TRUE) {
						continue;
					}

					if (isset($child_item->parent_id) && $child_item->parent_id == $item->id) {
						if (!isset($item->children)) {
							$item->children = array();
						}
						$item->children[] = $child_item;
						$child_item->parent = $item;
						// don't unset because we still need to check this item for children
						$child_item->used = TRUE;
					}
				}

				// if the parent doesn't have a url, make it the first child item.
				if (isset($item->children) && $item->children && !$item->href) {
					$child = $item->children[0];
					while ($child && !isset($child->href)) {
						if (isset($child->children) && isset($child->children[0])) {
							$child = $child->children[0];
						} else {
							$child = NULL;
						}
					}

					if ($child && isset($child->href)) {
						$item->href = $child->href;
					} else {
						// @todo There are no URLs anywhere in this tree.
						$item->href = elgg_get_site_url();
					}
				}
			}

			// only add top-level elements to the menu.
			// the rest are children.
			if (!isset($item->parent_id)) {
				$parsed_menu[] = $item;
			}
		}

		$CONFIG->submenu[$context][$group] = $parsed_menu;
	}

	return TRUE;
}

/**
 * Helper function used to sort submenu items by their display text.
 *
 * @param object $a First object
 * @param object $b Second object
 *
 * @return int
 * @since 1.8
 * @see elgg_prepare_submenu
 */
function elgg_submenu_item_cmp($a, $b) {
	$a = $a->text;
	$b = $b->text;

	return strnatcmp($a, $b);
}

/**
 * Use elgg_get_submenu().
 *
 * @see elgg_get_submenu()
 * @deprecated 1.8
 *
 * @return string
 */
function get_submenu() {
	elgg_deprecated_notice("get_submenu() has been deprecated by elgg_get_submenu()", 1.8);
	return elgg_get_submenu();
}

/**
 * Return the HTML for a sidemenu.
 *
 * @param string $context The context of the submenu (defaults to main)
 * @param BOOL   $sort    Sort by display name?
 *
 * @return string Formatted HTML.
 * @since 1.8
 * @todo Rename to a view function. See {@trac #2320}.
 */
function elgg_get_submenu($context = NULL, $sort = FALSE) {
	global $CONFIG;

	if (!$context) {
		$context = elgg_get_context();
	}

	if (!elgg_prepare_submenu($context, $sort)) {
		return '';
	}

	$groups = $CONFIG->submenu[$context];
	$submenu_html = '';

	foreach ($groups as $group => $items) {
		// how far down we are in children arrays
		$depth = 0;
		// push and pop parent items
		$temp_items = array();

		while ($item = current($items)) {
			// ignore parents created by a child but parent never defined properly
			if (!isset($item->text) || !($item->text)) {
				next($items);
				continue;
			}

			// try to guess if this should be selected if they don't specify
			if ((!isset($item->selected) || $item->selected === NULL) && isset($item->href)) {
				$item->selected = elgg_http_url_is_identical(full_url(), $item->href);
			}

			// traverse up the parent tree if matached to mark all parents as selected/expanded.
			if ($item->selected && isset($item->parent)) {
				$parent = $item->parent;
				while ($parent) {
					$parent->selected = TRUE;
					if (isset($parent->parent)) {
						$parent = $parent->parent;
					} else {
						$parent = NULL;
					}
				}
			}

			// get the next item
			if (isset($item->children) && $item->children) {
				$depth++;
				array_push($temp_items, $items);
				$items = $item->children;
			} elseif ($depth > 0) {
				// check if there are more children elements in the current items
				// pop back up to the parent(s) if not
				if ($item = next($items)) {
					continue;
				} else {
					while ($depth > 0) {
						$depth--;
						$items = array_pop($temp_items);
						if ($item = next($items)) {
							break;
						}
					}
				}
			} else {
				next($items);
			}
		}

		$vars = array('group' => $group, 'items' => $items);
		$submenu_html .= elgg_view('navigation/submenu_group', $vars);
	}

	// include the JS for the expand menus too
	return elgg_view('navigation/submenu_js') . $submenu_html;
}

/**
 * Registers any custom menu items with the main Site Menu.
 *
 * @note Custom menu items are added through the admin interface.  Plugins
 * can add standard menu items by using {@link add_menu()}.
 *
 * @since 1.8
 * @link http://docs.elgg.org/Tutorials/UI/SiteMenu
 * @elgg_event_handler init system
 * @return void
 */
function add_custom_menu_items() {
	if ($custom_items = get_config('menu_items_custom_items')) {
		foreach ($custom_items as $url => $name) {
			add_menu($name, $url);
		}
	}
}

/**
 * Returns the main site menu.
 *
 * @note The main site menu is split into "featured" links and
 * "more" links.
 *
 * @return array ('featured_urls' and 'more')
 * @since 1.8
 * @link http://docs.elgg.org/Tutorials/UI/SiteMenu
 */
function elgg_get_nav_items() {
	$menu_items = get_register('menu');
	$featured_urls_info = get_config('menu_items_featured_urls');

	$more = array();
	$featured_urls = array();
	$featured_urls_sanitised = array();

	// easier to compare with in_array() than embedded foreach()es
	$valid_urls = array();
	foreach ($menu_items as $info) {
		$valid_urls[] = $info->value->url;
	}

	// make sure the url is a valid link.
	// this prevents disabled plugins leaving behind
	// valid links when not using a pagehandler.
	if ($featured_urls_info) {
		foreach ($featured_urls_info as $info) {
			if (in_array($info->value->url, $valid_urls)) {
				$featured_urls[] = $info->value->url;
				$featured_urls_sanitised[] = $info;
			}
		}
	}

	// add toolbar entries if not hiding dupes.
	foreach ($menu_items as $name => $info) {
		if (!in_array($info->value->url, $featured_urls)) {
			$more[] = $info;
		}
	}

	return array(
		'featured' => $featured_urls_sanitised,
		'more' => $more
	);
}

/**
 * Adds an item to the site-wide menu.
 *
 * You can obtain the menu array by calling {@link get_register('menu')}
 *
 * @param string $menu_name     The name of the menu item
 * @param string $menu_url      The URL of the page
 * @param array  $menu_children Optionally, an array of submenu items (not currently used)
 * @param string $context       The context of the menu
 *
 * @return true|false Depending on success
 * @todo Can be deprecated when the new menu system is introduced.
 */
function add_menu($menu_name, $menu_url, $menu_children = array(), $context = "") {
	global $CONFIG;

	if (!isset($CONFIG->menucontexts)) {
		$CONFIG->menucontexts = array();
	}

	if (empty($context)) {
		$context = get_plugin_name();
	}

	$value = new stdClass();
	$value->url = elgg_normalize_url($menu_url);
	$value->context = $context;

	$CONFIG->menucontexts[] = $context;
	return add_to_register('menu', $menu_name, $value, $menu_children);
}

/**
 * Removes an item from the menu register
 *
 * @param string $menu_name The name of the menu item
 *
 * @return true|false Depending on success
 */
function remove_menu($menu_name) {
	return remove_from_register('menu', $menu_name);
}

/**
 * Returns a menu item for use in the children section of add_menu()
 * This is not currently used in the Elgg core.
 *
 * @param string $menu_name The name of the menu item
 * @param string $menu_url  Its URL
 *
 * @return stdClass|false Depending on success
 * @todo Can be deprecated when the new menu system is introduced.
 */
function menu_item($menu_name, $menu_url) {
	elgg_deprecated_notice('menu_item() is deprecated by add_submenu_item', 1.7);
	return make_register_object($menu_name, $menu_url);
}

/**
 * Adds a breadcrumb to the breadcrumbs stack.
 *
 * @param string $title The title to display
 * @param string $link  Optional. The link for the title.
 *
 * @return void
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