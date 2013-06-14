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
 *     extras Links about content on the page. The RSS link is added to this.
 *
 * Dynamic menus (also called just-in-time menus):
 *     user_hover  Avatar hover menu. The user entity is passed as a parameter.
 *     entity      The set of links shown in the summary of an entity.
 *     river       Links shown on river items.
 *     owner_block Links shown for a user or group in their owner block.
 *     filter      The tab filter for content (all, mine, friends)
 *     title       The buttons shown next to a content title.
 *     long-text   The links shown above the input/longtext view.
 *
 * @package Elgg.Core
 * @subpackage Navigation
 */

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
 * @param string $menu_name The name of the menu: site, page, userhover,
 *                          userprofile, groupprofile, or any custom menu
 * @param mixed  $menu_item A ElggMenuItem object or an array of options in format:
 *                          name        => STR  Menu item identifier (required)
 *                          text        => STR  Menu item display text (required)
 *                          href        => STR  Menu item URL (required) (false for non-links.
 *                                              @warning If you disable the href the <a> tag will
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
 *
 *                          Additional options that the view output/url takes can be
 *							passed in the array. If the 'confirm' key is passed, the
 *							menu link uses the 'output/confirmlink' view. Custom
 *							options can be added by using the 'data' key with the
 *							value being an associative array.
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
		/* @var ElggMenuItem $menu_object */
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

	foreach ($CONFIG->menus[$menu_name] as $menu_object) {
		/* @var ElggMenuItem $menu_object */
		if ($menu_object->getName() == $item_name) {
			return true;
		}
	}

	return false;
}

/**
 * Convenience function for registering a button to title menu
 *
 * The URL must be $handler/$name/$guid where $guid is the guid of the page owner.
 * The label of the button is "$handler:$name" so that must be defined in a
 * language file.
 *
 * This is used primarily to support adding an add content button
 *
 * @param string $handler The handler to use or null to autodetect from context
 * @param string $name    Name of the button
 * @return void
 * @since 1.8.0
 */
function elgg_register_title_button($handler = null, $name = 'add') {
	if (elgg_is_logged_in()) {

		if (!$handler) {
			$handler = elgg_get_context();
		}

		$owner = elgg_get_page_owner_entity();
		if (!$owner) {
			// no owns the page so this is probably an all site list page
			$owner = elgg_get_logged_in_user_entity();
		}
		if ($owner && $owner->canWriteToContainer()) {
			$guid = $owner->getGUID();
			elgg_register_menu_item('title', array(
				'name' => $name,
				'href' => "$handler/$name/$guid",
				'text' => elgg_echo("$handler:$name"),
				'link_class' => 'elgg-button elgg-button-action',
			));
		}
	}
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
	if (!isset($CONFIG->breadcrumbs)) {
		$CONFIG->breadcrumbs = array();
	}

	// avoid key collisions.
	$CONFIG->breadcrumbs[] = array('title' => elgg_get_excerpt($title, 100), 'link' => $link);
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
		return array_pop($CONFIG->breadcrumbs);
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

	if (isset($CONFIG->breadcrumbs) && is_array($CONFIG->breadcrumbs)) {
		return $CONFIG->breadcrumbs;
	}

	return array();
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
 * @access private
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
		if (count($registered) > 0) {
			$return['more'] = $registered;
		}
	} else {
		// no featured menu items set
		$max_display_items = 5;

		// the first n are shown, rest added to more list
		// if only one item on more menu, stick it with the rest
		$num_menu_items = count($return['default']);
		if ($num_menu_items > ($max_display_items + 1)) {
			$return['more'] = array_splice($return['default'], $max_display_items);
		}
	}
	
	// check if we have anything selected
	$selected = false;
	foreach ($return as $section) {
		foreach ($section as $item) {
			if ($item->getSelected()) {
				$selected = true;
				break 2;
			}
		}
	}
	
	if (!$selected) {
		// nothing selected, match name to context or match url
		$current_url = current_page_url();
		foreach ($return as $section_name => $section) {
			foreach ($section as $key => $item) {
				// only highlight internal links
				if (strpos($item->getHref(), elgg_get_site_url()) === 0) {
					if ($item->getName() == elgg_get_context()) {
						$return[$section_name][$key]->setSelected(true);
						break 2;
					}
					if ($item->getHref() == $current_url) {
						$return[$section_name][$key]->setSelected(true);
						break 2;
					}
				}
			}
		}
	}

	return $return;
}

/**
 * Add the comment and like links to river actions menu
 * @access private
 */
function elgg_river_menu_setup($hook, $type, $return, $params) {
	if (elgg_is_logged_in()) {
		$item = $params['item'];
		/* @var ElggRiverItem $item */
		$object = $item->getObjectEntity();
		// comments and non-objects cannot be commented on or liked
		if (!elgg_in_context('widgets') && $item->annotation_id == 0) {
			// comments
			if ($object->canComment()) {
				$options = array(
					'name' => 'comment',
					'href' => "#comments-add-$object->guid",
					'text' => elgg_view_icon('speech-bubble'),
					'title' => elgg_echo('comment:this'),
					'rel' => 'toggle',
					'priority' => 50,
				);
				$return[] = ElggMenuItem::factory($options);
			}
		}
		
		if (elgg_is_admin_logged_in()) {
			$options = array(
				'name' => 'delete',
				'href' => elgg_add_action_tokens_to_url("action/river/delete?id=$item->id"),
				'text' => elgg_view_icon('delete'),
				'title' => elgg_echo('delete'),
				'confirm' => elgg_echo('deleteconfirm'),
				'priority' => 200,
			);
			$return[] = ElggMenuItem::factory($options);
		}
	}

	return $return;
}

/**
 * Entity menu is list of links and info on any entity
 * @access private
 */
function elgg_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}
	
	$entity = $params['entity'];
	/* @var ElggEntity $entity */
	$handler = elgg_extract('handler', $params, false);

	// access
	$access = elgg_view('output/access', array('entity' => $entity));
	$options = array(
		'name' => 'access',
		'text' => $access,
		'href' => false,
		'priority' => 100,
	);
	$return[] = ElggMenuItem::factory($options);

	if ($entity->canEdit() && $handler) {
		// edit link
		$options = array(
			'name' => 'edit',
			'text' => elgg_echo('edit'),
			'title' => elgg_echo('edit:this'),
			'href' => "$handler/edit/{$entity->getGUID()}",
			'priority' => 200,
		);
		$return[] = ElggMenuItem::factory($options);

		// delete link
		$options = array(
			'name' => 'delete',
			'text' => elgg_view_icon('delete'),
			'title' => elgg_echo('delete:this'),
			'href' => "action/$handler/delete?guid={$entity->getGUID()}",
			'confirm' => elgg_echo('deleteconfirm'),
			'priority' => 300,
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

/**
 * Widget menu is a set of widget controls
 * @access private
 */
function elgg_widget_menu_setup($hook, $type, $return, $params) {

	$widget = $params['entity'];
	/* @var ElggWidget $widget */
	$show_edit = elgg_extract('show_edit', $params, true);

	$collapse = array(
		'name' => 'collapse',
		'text' => ' ',
		'href' => "#elgg-widget-content-$widget->guid",
		'class' => 'elgg-widget-collapse-button',
		'rel' => 'toggle',
		'priority' => 1
	);
	$return[] = ElggMenuItem::factory($collapse);

	if ($widget->canEdit()) {
		$delete = array(
			'name' => 'delete',
			'text' => elgg_view_icon('delete-alt'),
			'title' => elgg_echo('widget:delete', array($widget->getTitle())),
			'href' => "action/widgets/delete?widget_guid=$widget->guid",
			'is_action' => true,
			'class' => 'elgg-widget-delete-button',
			'id' => "elgg-widget-delete-button-$widget->guid",
			'priority' => 900
		);
		$return[] = ElggMenuItem::factory($delete);

		if ($show_edit) {
			$edit = array(
				'name' => 'settings',
				'text' => elgg_view_icon('settings-alt'),
				'title' => elgg_echo('widget:edit'),
				'href' => "#widget-edit-$widget->guid",
				'class' => "elgg-widget-edit-button",
				'rel' => 'toggle',
				'priority' => 800,
			);
			$return[] = ElggMenuItem::factory($edit);
		}
	}

	return $return;
}

/**
 * Adds a delete link to "generic_comment" annotations
 * @access private
 */
function elgg_annotation_menu_setup($hook, $type, $return, $params) {
	$annotation = $params['annotation'];
	/* @var ElggAnnotation $annotation */

	if ($annotation->name == 'generic_comment' && $annotation->canEdit()) {
		$url = elgg_http_add_url_query_elements('action/comments/delete', array(
			'annotation_id' => $annotation->id,
		));

		$options = array(
			'name' => 'delete',
			'href' => $url,
			'text' => "<span class=\"elgg-icon elgg-icon-delete\"></span>",
			'confirm' => elgg_echo('deleteconfirm'),
			'encode_text' => false
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}


/**
 * Navigation initialization
 * @access private
 */
function elgg_nav_init() {
	elgg_register_plugin_hook_handler('prepare', 'menu:site', 'elgg_site_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:river', 'elgg_river_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'elgg_entity_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:widget', 'elgg_widget_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:annotation', 'elgg_annotation_menu_setup');
}

elgg_register_event_handler('init', 'system', 'elgg_nav_init');
