<?php
/**
 * Elgg navigation library
 * Functions for managing menus and other navigational elements
 *
 * Breadcrumbs
 * Elgg uses a breadcrumb stack. The page handlers (controllers in MVC terms)
 * push the breadcrumb links onto the stack. @see elgg_push_breadcrumb()
 *
 *
 * Pagination
 * Automatically handled by Elgg when using elgg_list_entities* functions.
 * @see elgg_list_entities()
 *
 *
 * Tabs
 * @see navigation/tabs view
 *
 *
 * Menus
 * Elgg uses a single interface to manage its menus. Menu items are added with
 * {@link elgg_register_menu_item()}. This is generally used for menus that
 * appear only once per page. For dynamic menus (such as the hover
 * menu for user's avatar), a plugin hook is emitted when the menu is being
 * created. The hook is 'register', 'menu:<menu_name>'. For more details on this,
 * @see elgg_view_menu().
 *
 * Menus supported by the Elgg core
 * Standard menus:
 *     site   Site navigation shown on every page.
 *     page   Page menu usually shown in a sidebar. Uses Elgg's context.
 *     topbar Topbar menu shown on every page. The default has two sections.
 *     footer Like the topbar but in the footer.
 *
 * Dynamic menus (also called just-in-time menus):
 *     user_hover  Avatar hover menu. The user entity is passed as a parameter.
 *     entity      The set of links shown in the summary of an entity.
 *     river       Links shown on river items.
 *     owner_block Links shown for a user or group in their owner block.
 *     filter      The tab filter for content (all, mine, friends)
 *     title       The buttons shown next to a content title.
 *     longtext    The links shown above the input/longtext view.
 *     login       Menu of links at bottom of login box
 *
 * @package    Elgg.Core
 * @subpackage Navigation
 */

use Elgg\Menu\MenuItems;
use Elgg\Menu\PreparedMenu;
use Elgg\Menu\UnpreparedMenu;

/**
 * Register an item for an Elgg menu
 *
 * @warning Generally you should not use this in response to the plugin hook:
 * 'register', 'menu:<menu_name>'. If you do, you may end up with many incorrect
 * links on a dynamic menu.
 *
 * @warning A menu item's name must be unique per menu. If more than one menu
 * item with the same name are registered, the last menu item takes priority.
 *
 * @see elgg_view_menu() for the plugin hooks available for modifying a menu as
 * it is being rendered.
 *
 * @see ElggMenuItem::factory() is used to turn an array value of $menu_item into an
 * ElggMenuItem object.
 *
 * @param string $menu_name The name of the menu: site, page, userhover,
 *                          userprofile, groupprofile, or any custom menu
 * @param mixed  $menu_item A \ElggMenuItem object or an array of options in format:
 *                          name        => STR  Menu item identifier (required)
 *                          text        => STR  Menu item display text as HTML (required)
 *                          href        => STR  Menu item URL (required)
 *                                              false = do not create a link.
 *                                              null = current URL.
 *                                              "" = current URL.
 *                                              "/" = site home page.
 *                                              @warning If href is false, the <a> tag will
 *                                              not appear, so the link_class will not apply. If you
 *                                              put <a> tags in manually through the 'text' option
 *                                              the default CSS selector .elgg-menu-$menu > li > a
 *                                              may affect formatting. Wrap in a <span> if it does.)
 *                          contexts    => ARR  Page context strings
 *                          section     => STR  Menu section identifier
 *                          title       => STR  Menu item tooltip
 *                          selected    => BOOL Is this menu item currently selected
 *                          parent_name => STR  Identifier of the parent menu item
 *                          link_class  => STR  A class or classes for the <a> tag
 *                          item_class  => STR  A class or classes for the <li> tag
 *                          deps     => STR  One or more AMD modules to require
 *
 *                          Additional options that the view output/url takes can be
 *							passed in the array. Custom options can be added by using
 *							the 'data' key with the	value being an associative array.
 *
 * @return bool False if the item could not be added
 * @since 1.8.0
 */
function elgg_register_menu_item($menu_name, $menu_item) {
	if (is_array($menu_item)) {
		$options = $menu_item;
		$menu_item = \ElggMenuItem::factory($options);
		if (!$menu_item) {
			$menu_item_name = elgg_extract('name', $options, 'MISSING NAME');
			elgg_log("Unable to add menu item '{$menu_item_name}' to '$menu_name' menu", 'WARNING');
			return false;
		}
	}

	if (!$menu_item instanceof ElggMenuItem) {
		elgg_log('Second argument of elgg_register_menu_item() must be an instance of '
			. 'ElggMenuItem or an array of menu item factory options', 'ERROR');
		return false;
	}

	$menus = _elgg_config()->menus;
	if (!$menus) {
		$menus = [];
	}

	$menus[$menu_name][] = $menu_item;
	_elgg_config()->menus = $menus;

	return true;
}

/**
 * Remove an item from a menu
 *
 * @param string $menu_name The name of the menu
 * @param string $item_name The unique identifier for this menu item
 *
 * @return \ElggMenuItem|null
 * @since 1.8.0
 */
function elgg_unregister_menu_item($menu_name, $item_name) {
	$menus = _elgg_config()->menus;
	if (!$menus) {
		return null;
	}
	
	if (!isset($menus[$menu_name])) {
		return null;
	}

	foreach ($menus[$menu_name] as $index => $menu_object) {
		/* @var \ElggMenuItem $menu_object */
		if ($menu_object instanceof ElggMenuItem && $menu_object->getName() == $item_name) {
			$item = $menus[$menu_name][$index];
			unset($menus[$menu_name][$index]);
			elgg_set_config('menus', $menus);
			return $item;
		}
	}

	return null;
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
	$menus = _elgg_config()->menus;
	if (!$menus) {
		return false;
	}

	if (!isset($menus[$menu_name])) {
		return false;
	}

	foreach ($menus[$menu_name] as $menu_object) {
		/* @var \ElggMenuItem $menu_object */
		if ($menu_object->getName() == $item_name) {
			return true;
		}
	}

	return false;
}

/**
 * Get a menu item registered for a menu
 *
 * @param string $menu_name The name of the menu
 * @param string $item_name The unique identifier for this menu item
 *
 * @return ElggMenuItem|null
 * @since 1.9.0
 */
function elgg_get_menu_item($menu_name, $item_name) {
	$menus = _elgg_config()->menus;
	if (!$menus) {
		return null;
	}

	if (!isset($menus[$menu_name])) {
		return null;
	}

	foreach ($menus[$menu_name] as $index => $menu_object) {
		/* @var \ElggMenuItem $menu_object */
		if ($menu_object->getName() == $item_name) {
			return $menus[$menu_name][$index];
		}
	}

	return null;
}

/**
 * Convenience function for registering a button to the title menu
 *
 * The URL must be $handler/$name/$guid where $guid is the guid of the page owner.
 * The label of the button is "$handler:$name" so that must be defined in a
 * language file.
 *
 * This is used primarily to support adding an add content button
 *
 * @param string $handler        The handler to use or null to autodetect from context
 * @param string $name           Name of the button (defaults to 'add')
 * @param string $entity_type    Optional entity type to be added (used to verify canWriteToContainer permission)
 * @param string $entity_subtype Optional entity subtype to be added (used to verify canWriteToContainer permission)
 * @return void
 * @since 1.8.0
 */
function elgg_register_title_button($handler = null, $name = 'add', $entity_type = 'all', $entity_subtype = 'all') {

	$owner = elgg_get_page_owner_entity();
	if (!$owner) {
		// noone owns the page so this is probably an all site list page
		$owner = elgg_get_logged_in_user_entity();
	}
	
	if (($name === 'add') && ($owner instanceof ElggUser)) {
		// make sure the add link goes to the current logged in user, not the page owner
		$logged_in_user = elgg_get_logged_in_user_entity();
		if (!empty($logged_in_user) && ($logged_in_user->guid !== $owner->guid)) {
			// change the 'owner' for the link to the current logged in user
			$owner = $logged_in_user;
		}
	}
	
	// do we have an owner and is the current user allowed to create content here
	if (!$owner || !$owner->canWriteToContainer(0, $entity_type, $entity_subtype)) {
		return;
	}
	
	$href = elgg_generate_url("$name:$entity_type:$entity_subtype", [
		'guid' => $owner->guid,
	]);
		
	if (!$href) {
		if (!$handler) {
			$handler = elgg_get_context();
		}
		$href = "$handler/$name/$owner->guid";
	}
	
	if (elgg_language_key_exists("$name:$entity_type:$entity_subtype")) {
		$text = elgg_echo("$name:$entity_type:$entity_subtype");
	} elseif (elgg_language_key_exists("$handler:$name")) {
		$text = elgg_echo("$handler:$name");
	} else {
		$text = elgg_echo($name);
	}
	
	// register the title menu item
	elgg_register_menu_item('title', [
		'name' => $name,
		'icon' => $name === 'add' ? 'plus' : '',
		'href' => $href,
		'text' => $text,
		'link_class' => 'elgg-button elgg-button-action',
	]);
}

/**
 * Adds a breadcrumb to the breadcrumbs stack.
 *
 * See elgg_get_breadcrumbs() and the navigation/breadcrumbs view.
 *
 * @param string $text The title to display. During rendering this is HTML encoded.
 * @param string $href Optional. The href for the title. During rendering links are
 *                     normalized via elgg_normalize_url().
 *
 * @return void
 * @since 1.8.0
 * @see elgg_get_breadcrumbs()
 */
function elgg_push_breadcrumb($text, $href = false) {
	$breadcrumbs = (array) _elgg_config()->breadcrumbs;
	
	$breadcrumbs[] = [
		'text' => $text,
		'href' => $href,
	];
	
	elgg_set_config('breadcrumbs', $breadcrumbs);
}

/**
 * Removes last breadcrumb entry.
 *
 * @return array popped breadcrumb array or empty array
 * @since 1.8.0
 */
function elgg_pop_breadcrumb() {
	$breadcrumbs = (array) _elgg_config()->breadcrumbs;

	if (empty($breadcrumbs)) {
		return [];
	}

	$popped = array_pop($breadcrumbs);
	elgg_set_config('breadcrumbs', $breadcrumbs);

	return $popped;
}

/**
 * Returns all breadcrumbs as an array
 * <code>
 * [
 *    [
 *       'text' => 'Breadcrumb title',
 *       'href' => '/path/to/page',
 *    ]
 * ]
 * </code>
 *
 * Breadcrumbs are filtered through the plugin hook [prepare, breadcrumbs] before
 * being returned.
 *
 * @param array $breadcrumbs An array of breadcrumbs
 *                           If set, will override breadcrumbs in the stack
 * @return array
 * @since 1.8.0
 * @see elgg_prepare_breadcrumbs()
 */
function elgg_get_breadcrumbs(array $breadcrumbs = null) {
	if (!isset($breadcrumbs)) {
		// if no crumbs set, still allow hook to populate it
		$breadcrumbs = (array) _elgg_config()->breadcrumbs;
	}

	if (!is_array($breadcrumbs)) {
		_elgg_services()->logger->error(__FUNCTION__ . ' expects breadcrumbs as an array');
		$breadcrumbs = [];
	}
	
	$params = [
		'breadcrumbs' => $breadcrumbs,
	];

	$params['identifier'] = _elgg_services()->request->getFirstUrlSegment();
	$params['segments'] = _elgg_services()->request->getUrlSegments();
	array_shift($params['segments']);

	$breadcrumbs = elgg_trigger_plugin_hook('prepare', 'breadcrumbs', $params, $breadcrumbs);
	if (!is_array($breadcrumbs)) {
		_elgg_services()->logger->error('"prepare, breadcrumbs" hook must return an array of breadcrumbs');
		return [];
	}
	
	foreach ($breadcrumbs as $key => $breadcrumb) {
		$text = elgg_extract('text', $breadcrumb, elgg_extract('title', $breadcrumb));
		if (isset($breadcrumb['link'])) {
			elgg_deprecated_notice("Breadcrumb [{$text}] requires 'href' instead of 'link' set in the configuration", '3.0');
			
			$breadcrumbs[$key]['href'] = $breadcrumb['link'];
			unset($breadcrumbs[$key]['link']);
		}
		if (isset($breadcrumb['title'])) {
			elgg_deprecated_notice("Breadcrumb [{$text}] requires 'text' instead of 'title' set in the configuration", '3.0');
			
			$breadcrumbs[$key]['text'] = $breadcrumb['title'];
			unset($breadcrumbs[$key]['title']);
		}
		
		// adds name for usage in menu items
		if (!isset($breadcrumb['name'])) {
			$breadcrumbs[$key]['name'] = $key;
		}
	}

	return $breadcrumbs;
}

/**
 * Prepare breadcrumbs before display. This turns titles into 100-character excerpts, and also
 * removes the last crumb if it's not a link.
 *
 * @param string $hook        "prepare"
 * @param string $type        "breadcrumbs"
 * @param array  $breadcrumbs Breadcrumbs to be altered
 * @param array  $params      Hook parameters
 *
 * @return array
 * @since 1.11
 */
function elgg_prepare_breadcrumbs($hook, $type, $breadcrumbs, $params) {
	// remove last crumb if not a link
	$last_crumb = end($breadcrumbs);
	if (empty($last_crumb['href'])) {
		array_pop($breadcrumbs);
	}

	// apply excerpt to text
	foreach (array_keys($breadcrumbs) as $i) {
		$breadcrumbs[$i]['text'] = elgg_get_excerpt($breadcrumbs[$i]['text'], 100);
	}
	return $breadcrumbs;
}

/**
 * Resolves and pushes entity breadcrumbs based on named routes
 *
 * @param ElggEntity $entity    Entity
 * @param bool       $link_self Use entity link in the last crumb
 *
 * @return void
 */
function elgg_push_entity_breadcrumbs(ElggEntity $entity, $link_self = true) {

	$container = $entity->getContainerEntity() ? : null;
	elgg_push_collection_breadcrumbs($entity->type, $entity->subtype, $container);

	$entity_url = $link_self ? $entity->getURL() : false;
	elgg_push_breadcrumb($entity->getDisplayName(), $entity_url);
}

/**
 * Resolves and pushes collection breadcrumbs for a container
 *
 * @param string          $entity_type    Entity type in the collection
 * @param string          $entity_subtype Entity subtype in the collection
 * @param ElggEntity|null $container      Container/page owner entity
 * @param bool            $friends        Collection belongs to container's friends?
 *
 * @return void
 */
function elgg_push_collection_breadcrumbs($entity_type, $entity_subtype, ElggEntity $container = null, $friends = false) {

	if ($container) {
		if (!$container instanceof \ElggSite) {
			elgg_push_breadcrumb($container->getDisplayName(), $container->getURL());
		}

		if ($friends) {
			$collection_route = "collection:$entity_type:$entity_subtype:friends";
		} else if ($container instanceof ElggUser) {
			$collection_route = "collection:$entity_type:$entity_subtype:owner";
		} else if ($container instanceof ElggGroup) {
			$collection_route = "collection:$entity_type:$entity_subtype:group";
		} else if ($container instanceof ElggSite) {
			$collection_route = "collection:$entity_type:$entity_subtype:all";
		} else {
			$collection_route = "collection:$entity_type:$entity_subtype:container";
		}

		$parameters = _elgg_services()->routes->resolveRouteParameters($collection_route, $container);
		if ($parameters !== false) {
			$label = elgg_echo("collection:$entity_type:$entity_subtype");
			if ($friends) {
				if (elgg_language_key_exists("collection:$entity_type:$entity_subtype:friends")) {
					$label = elgg_echo("collection:$entity_type:$entity_subtype:friends");
				} else {
					$label = elgg_echo('collection:friends', [$label]);
				}
			}
			$collection_url = elgg_generate_url($collection_route, $parameters);
			elgg_push_breadcrumb($label, $collection_url);
		}
	} else {
		$label = elgg_echo("collection:$entity_type:$entity_subtype");
		$collection_url = elgg_generate_url("collection:$entity_type:$entity_subtype:all");
		elgg_push_breadcrumb($label, $collection_url);
	}
}

/**
 * Returns default filter tabs (All, Mine, Friends) for the user
 *
 * @param string   $context  Context to be used to prefix tab URLs
 * @param string   $selected Name of the selected tab
 * @param ElggUser $user     User who owns the layout (defaults to logged in user)
 * @param array    $vars     Additional vars
 * @return ElggMenuItem[]
 * @since 2.3
 */
function elgg_get_filter_tabs($context = null, $selected = null, ElggUser $user = null, array $vars = []) {

	if (!isset($selected)) {
		$selected = 'all';
	}

	if (!$user) {
		$user = elgg_get_logged_in_user_entity();
	}

	$items = [];
	$items[] = ElggMenuItem::factory([
		'name' => 'all',
		'text' => elgg_echo('all'),
		'href' => (isset($vars['all_link'])) ? $vars['all_link'] : "$context/all",
		'selected' => ($selected == 'all'),
		'priority' => 200,
	]);
	
	if ($user) {
		$items[] = ElggMenuItem::factory([
			'name' => 'mine',
			'text' => elgg_echo('mine'),
			'href' => (isset($vars['mine_link'])) ? $vars['mine_link'] : "$context/owner/{$user->username}",
			'selected' => ($selected == 'mine'),
			'priority' => 300,
		]);
	}

	$params = [
		'selected' => $selected,
		'user' => $user,
		'vars' => $vars,
	];
	$items = _elgg_services()->hooks->trigger('filter_tabs', $context, $params, $items);

	return $items;
}

/**
 * Init site menu
 *
 * Registers custom menu items
 *
 * @param string    $hook   'register'
 * @param string    $type   'menu:site'
 * @param MenuItems $return Menu
 * @param array     $params Hook params
 *
 * @return MenuItems
 *
 * @access private
 */
function _elgg_site_menu_init($hook, $type, $return, $params) {
	$custom_menu_items = elgg_get_config('site_custom_menu_items');

	if ($custom_menu_items) {
		// add custom menu items
		$n = 1;
		foreach ($custom_menu_items as $title => $url) {
			$item = new ElggMenuItem("custom$n", $title, $url);
			$return[] = $item;
			$n++;
		}
	}

	return $return;
}

/**
 * Set up the site menu
 *
 * Handles default, featured, and custom menu items
 *
 * @param string       $hook   'prepare'
 * @param string       $type   'menu:site'
 * @param PreparedMenu $menu   current return value
 * @param array        $params supplied params
 *
 * @return PreparedMenu
 *
 * @access private
 */
function _elgg_site_menu_setup($hook, $type, $menu, $params) {

	$featured_menu_names = array_values((array) elgg_get_config('site_featured_menu_names'));

	$registered = $menu->getItems('default');
	if (empty($registered)) {
		return;
	}
	
	$has_selected = false;
	$priority = 500;

	foreach ($registered as $item) {
		if (in_array($item->getName(), $featured_menu_names)) {
			$featured_index = array_search($item->getName(), $featured_menu_names);
			$item->setPriority($featured_index);
		} else {
			$item->setPriority($priority);
			$priority++;
		}
		if ($item->getSelected()) {
			$has_selected = true;
		}
	}

	if (!$has_selected) {
		$is_selected = function (ElggMenuItem $item) {
			$current_url = current_page_url();
			if (strpos($item->getHref(), elgg_get_site_url()) === 0) {
				if ($item->getName() == elgg_get_context()) {
					return true;
				}
				if ($item->getHref() == $current_url) {
					return true;
				}
			}

			return false;
		};

		foreach ($registered as $item) {
			if ($is_selected($item)) {
				$item->setSelected(true);
				break;
			}
		}
	}

	usort($registered, [\ElggMenuBuilder::class, 'compareByPriority']);

	$max_display_items = 5;

	$num_menu_items = count($registered);

	$more = [];
	if ($max_display_items && $num_menu_items > ($max_display_items + 1)) {
		$more = array_splice($registered, $max_display_items);
	}

	if (!empty($more)) {
		$dropdown = ElggMenuItem::factory([
			'name' => 'more',
			'href' => false,
			'text' => elgg_echo('more'),
			'icon_alt' => 'angle-down',
			'priority' => 999,
		]);

		foreach ($more as &$item) {
			$item->setParentName('more');
		}

		$dropdown->setChildren($more);

		$registered[] = $dropdown;
	}

	$menu->getSection('default')->fill($registered);

}

/**
 * Prepare vertically stacked menu
 *
 * Sets the display child menu option to "toggle" if not set
 * Recursively marks parents of the selected item as selected (expanded)
 *
 * @elgg_plugin_hook prepare menu:page
 * @elgg_plugin_hook prepare menu:owner_block
 *
 * @param \Elgg\Hook $hook Hook
 *
 * @return PreparedMenu
 *
 * @access private
 */
function _elgg_setup_vertical_menu(\Elgg\Hook $hook) {
	$menu = $hook->getValue();
	/* @var $menu PreparedMenu */

	$prepare = function(ElggMenuItem $menu_item) use (&$prepare) {
		$child_menu_vars = $menu_item->getChildMenuOptions();
		if (empty($child_menu_vars['display'])) {
			$child_menu_vars['display'] = 'toggle';
		}
		$menu_item->setChildMenuOptions($child_menu_vars);

		foreach ($menu_item->getChildren() as $child_menu_item) {
			$prepare($child_menu_item);
		}
	};

	foreach ($menu as $section => $menu_items) {
		foreach ($menu_items as $menu_item) {
			if ($menu_item instanceof ElggMenuItem) {
				$prepare($menu_item);
			}
		}
	}

	$selected_item = $hook->getParam('selected_item');
	if ($selected_item instanceof \ElggMenuItem) {
		$parent = $selected_item->getParent();
		while ($parent instanceof \ElggMenuItem) {
			$parent->setSelected();
			$parent->addItemClass('elgg-has-selected-child');
			$parent = $parent->getParent();
		}
	}

	return $menu;
}

/**
 * Entity menu is list of links and info on any entity
 *
 * @param string    $hook   'register'
 * @param string    $type   'menu:entity'
 * @param MenuItems $return current return value
 * @param array     $params supplied params
 *
 * @return MenuItems
 *
 * @access private
 */
function _elgg_entity_menu_setup($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params);
	if (!($entity instanceof \ElggEntity) || $entity instanceof \ElggUser) {
		// users mostly use the hover menu for their actions
		return;
	}

	$handler = elgg_extract('handler', $params, false);
	if ($handler) {
		elgg_deprecated_notice("Using 'handler' in entity menu parameters is deprecated. Use named routes instead.", '3.0');

		$edit_url = "$handler/edit/{$entity->guid}";

		if (elgg_action_exists("$handler/delete")) {
			$action = "$handler/delete";
		} else {
			$action = "entity/delete";
		}
		$delete_url = elgg_generate_action_url($action, [
			'guid' => $entity->guid,
		]);
	} else {
		$edit_url = elgg_generate_entity_url($entity, 'edit');
		$delete_url = elgg_generate_action_url('entity/delete', [
			'guid' => $entity->guid,
		]);
	}

	if ($entity->canEdit() && $edit_url) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'edit',
			'icon' => 'edit',
			'text' => elgg_echo('edit'),
			'title' => elgg_echo('edit:this'),
			'href' => $edit_url,
			'priority' => 900,
		]);
	}

	if ($entity->canDelete() && $delete_url) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'delete',
			'icon' => 'delete',
			'text' => elgg_echo('delete'),
			'title' => elgg_echo('delete:this'),
			'href' => $delete_url,
			'confirm' => elgg_echo('deleteconfirm'),
			'priority' => 950,
		]);
	}

	return $return;
}

/**
 * Moves default menu items into a dropdown
 *
 * @tip    Pass 'dropdown' => false to entity menu options to render the menu inline without a dropdown
 *
 * @elgg_plugin_hook prepare menu:entity
 * @elgg_plugin_hook prepare menu:river
 *
 * @param \Elgg\Hook $hook Hook
 *
 * @return void
 *
 * @access private
 */
function _elgg_menu_transform_to_dropdown(\Elgg\Hook $hook) {
	$menu = $hook->getValue();
	/* @var $menu PreparedMenu */

	$is_dropdown = $hook->getParam('dropdown', true);
	if ($is_dropdown === false) {
		return;
	}

	$items = $menu->getItems('default');
	if (empty($items)) {
		return;
	}

	$menu->getSection('default')->fill([
		\ElggMenuItem::factory([
			'name' => 'entity-menu-toggle',
			'icon' => 'ellipsis-v',
			'href' => false,
			'text' => '',
			'child_menu' => [
				'display' => 'dropdown',
				'data-position' => json_encode([
					'at' => 'right bottom',
					'my' => 'right top',
					'collision' => 'fit fit',
				]),
				'class' => "elgg-{$hook->getParam('name')}-dropdown-menu",
			],
			'children' => $items,
		]),
	]);
}

/**
 * Entity navigation menu is previous/next link for an entity
 *
 * @elgg_plugin_hook register menu:entity_navigation
 *
 * @param \Elgg\Hook $hook Hook
 *
 * @return MenuItems
 *
 * @access private
 */
function _elgg_entity_navigation_menu_setup(\Elgg\Hook $hook) {
	$entity = $hook->getEntityParam();
	if (!$entity) {
		return;
	}

	$return = $hook->getValue();
	/* @var $return MenuItems */

	$options = [
		'type' => $entity->getType(),
		'subtype' => $entity->getSubtype(),
		'container_guid' => $entity->container_guid,
		'limit' => 1,
	];

	$previous_options = $options;
	$previous_options['wheres'] = [
		function (\Elgg\Database\QueryBuilder $qb, $main_alias) use ($entity) {
			return $qb->merge([
				$qb->compare("{$main_alias}.time_created", '<', $entity->time_created, ELGG_VALUE_INTEGER),
				$qb->merge([
					$qb->compare("{$main_alias}.time_created", '=', $entity->time_created, ELGG_VALUE_INTEGER),
					$qb->compare("{$main_alias}.guid", '<', $entity->guid, ELGG_VALUE_GUID),
				], 'AND'),
			], 'OR');
		},
	];
	$previous_options['order_by'] = [
		new \Elgg\Database\Clauses\OrderByClause('time_created', 'DESC'),
		new \Elgg\Database\Clauses\OrderByClause('guid', 'DESC'),
	];

	$previous = elgg_get_entities($previous_options);
	if ($previous) {
		$previous = $previous[0];
		$return[] = \ElggMenuItem::factory([
			'name' => 'previous',
			'text' => elgg_echo('previous'),
			'entity' => $previous,
			'href' => $previous->getUrl(),
			'title' => $previous->getDisplayName(),
			'icon' => 'angle-double-left',
			'link_class' => 'elgg-button elgg-button-outline',
			'priority' => 100,
		]);
	}
	
	$next_options = $options;
	$next_options['wheres'] = [
		function (\Elgg\Database\QueryBuilder $qb, $main_alias) use ($entity) {
			return $qb->merge([
				$qb->compare("{$main_alias}.time_created", '>', $entity->time_created, ELGG_VALUE_INTEGER),
				$qb->merge([
					$qb->compare("{$main_alias}.time_created", '=', $entity->time_created, ELGG_VALUE_INTEGER),
					$qb->compare("{$main_alias}.guid", '>', $entity->guid, ELGG_VALUE_GUID),
				], 'AND'),
			], 'OR');
		},
	];
	$next_options['order_by'] = [
		new \Elgg\Database\Clauses\OrderByClause('time_created', 'ASC'),
		new \Elgg\Database\Clauses\OrderByClause('guid', 'ASC'),
	];
	
	$next = elgg_get_entities($next_options);
	if ($next) {
		$next = $next[0];
		$return[] = \ElggMenuItem::factory([
			'name' => 'next',
			'text' => elgg_echo('next'),
			'entity' => $next,
			'href' => $next->getUrl(),
			'title' => $next->getDisplayName(),
			'icon_alt' => 'angle-double-right',
			'link_class' => 'elgg-button elgg-button-outline',
			'priority' => 800,
		]);
	}
	
	return $return;
}

/**
 * Widget menu is a set of widget controls
 *
 * @param string    $hook   'register'
 * @param string    $type   'menu:widget'
 * @param MenuItems $return current return value
 * @param array     $params supplied params
 *
 * @return MenuItems
 *
 * @access private
 */
function _elgg_widget_menu_setup($hook, $type, $return, $params) {

	$widget = elgg_extract('entity', $params);
	if (!($widget instanceof \ElggWidget)) {
		return;
	}
	
	if ($widget->canDelete()) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'delete',
			'text' => elgg_view_icon('delete-alt'),
			'title' => elgg_echo('widget:delete', [$widget->getDisplayName()]),
			'href' => "action/widgets/delete?widget_guid=$widget->guid",
			'is_action' => true,
			'link_class' => 'elgg-widget-delete-button',
			'id' => "elgg-widget-delete-button-$widget->guid",
			'data-elgg-widget-type' => $widget->handler,
			'priority' => 900,
		]);
	}
	
	$show_edit = elgg_extract('show_edit', $params, $widget->canEdit());
	if ($show_edit) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'settings',
			'text' => elgg_view_icon('settings-alt'),
			'title' => elgg_echo('widget:edit'),
			'href' => "#widget-edit-$widget->guid",
			'link_class' => "elgg-widget-edit-button",
			'rel' => 'toggle',
			'priority' => 800,
		]);
	}

	return $return;
}

/**
 * Add the register and forgot password links to login menu
 *
 * @param string    $hook   'register'
 * @param string    $type   'menu:login'
 * @param MenuItems $return current return value
 * @param array     $params supplied params
 *
 * @return MenuItems
 *
 * @access private
 */
function _elgg_login_menu_setup($hook, $type, $return, $params) {

	if (_elgg_config()->allow_registration) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'register',
			'href' => elgg_get_registration_url(),
			'text' => elgg_echo('register'),
			'link_class' => 'registration_link',
		]);
	}

	$return[] = \ElggMenuItem::factory([
		'name' => 'forgotpassword',
		'href' => elgg_generate_url('account:password:reset'),
		'text' => elgg_echo('user:password:lost'),
		'link_class' => 'forgot_link',
	]);

	return $return;
}

/**
 * Add the RSS link to the menu
 *
 * @param string    $hook   'register'
 * @param string    $type   'menu:footer'
 * @param MenuItems $return current return value
 * @param array     $params supplied params
 *
 * @return MenuItems
 *
 * @access private
 */
function _elgg_rss_menu_setup($hook, $type, $return, $params) {

	if (!elgg_is_logged_in()) {
		return;
	}
	
	if (!_elgg_has_rss_link()) {
		return;
	}

	$return[] = ElggMenuItem::factory([
		'name' => 'rss',
		'text' => elgg_echo('feed:rss'),
		'icon' => 'rss',
		'href' => elgg_http_add_url_query_elements(current_page_url(), [
			'view' => 'rss',
		]),
		'title' => elgg_echo('feed:rss:title'),
	]);

	return $return;
}

/**
 * Navigation initialization
 *
 * @return void
 *
 * @access private
 */
function _elgg_nav_init() {
	elgg_register_plugin_hook_handler('prepare', 'breadcrumbs', 'elgg_prepare_breadcrumbs');

	elgg_register_plugin_hook_handler('prepare', 'menu:site', '_elgg_site_menu_setup', 999);
	elgg_register_plugin_hook_handler('register', 'menu:site', '_elgg_site_menu_init');

	elgg_register_plugin_hook_handler('prepare', 'menu:page', '_elgg_setup_vertical_menu', 999);
	elgg_register_plugin_hook_handler('prepare', 'menu:owner_block', '_elgg_setup_vertical_menu', 999);

	elgg_register_plugin_hook_handler('prepare', 'menu:entity', '_elgg_menu_transform_to_dropdown');
	elgg_register_plugin_hook_handler('prepare', 'menu:river', '_elgg_menu_transform_to_dropdown');
	elgg_register_plugin_hook_handler('register', 'menu:entity', '_elgg_entity_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:widget', '_elgg_widget_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:login', '_elgg_login_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:footer', '_elgg_rss_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:entity_navigation', '_elgg_entity_navigation_menu_setup');

	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', '_elgg_nav_public_pages');

	if (!_elgg_config()->remove_branding) {
		elgg_register_menu_item('footer', \ElggMenuItem::factory([
			'name' => 'powered',
			'text' => elgg_echo("elgg:powered"),
			'href' => 'http://elgg.org',
			'title' => 'Elgg ' . elgg_get_version(true),
			'section' => 'meta',
			'priority' => 600,
		]));
	}
	
	elgg_register_ajax_view('navigation/menu/user_hover/contents');

	// Using a view extension to ensure that themes that have replaced the item view
	// still load the required AMD modules
	elgg_extend_view('navigation/menu/elements/item', 'navigation/menu/elements/item_deps');
}

/**
 * Extend public pages
 *
 * @param string   $hook_name    "public_pages"
 * @param string   $entity_type  "walled_garden"
 * @param string[] $return_value Paths accessible outside the "walled garden"
 * @param mixed    $params       unused
 *
 * @return string[]
 * @access private
 * @since 1.11.0
 */
function _elgg_nav_public_pages($hook_name, $entity_type, $return_value, $params) {
	if (is_array($return_value)) {
		$return_value[] = 'navigation/menu/user_hover/contents';
	}

	return $return_value;
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_nav_init');
};
