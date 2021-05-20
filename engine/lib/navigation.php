<?php
/**
 * Elgg navigation library
 * Functions for managing menus and other navigational elements
 *
 * Pagination
 * Automatically handled by Elgg when using elgg_list_entities* functions.
 * @see elgg_list_entities()
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
 * @see ElggMenuItem::factory() is used to turn an array value of $menu_item into an
 * ElggMenuItem object.
 *
 * The \ElggMenuItem constructor and factory support the following array of menu item options:
 *  name        => STR  Menu item identifier (required)
 *  text        => STR  Menu item display text as HTML (required)
 *  href        => STR  Menu item URL (required)
 *                      false = do not create a link.
 *                      null = current URL.
 *                      "" = current URL.
 *                      "/" = site home page.
 *                      @warning If href is false, the <a> tag will
 *                      not appear, so the link_class will not apply. If you
 *                      put <a> tags in manually through the 'text' option
 *                      the default CSS selector .elgg-menu-$menu > li > a
 *                      may affect formatting. Wrap in a <span> if it does.)
 *
 *  contexts    => ARR  Page context strings
 *  section     => STR  Menu section identifier
 *  title       => STR  Menu item tooltip
 *  selected    => BOOL Is this menu item currently selected
 *  parent_name => STR  Identifier of the parent menu item
 *  link_class  => STR  A class or classes for the <a> tag
 *  item_class  => STR  A class or classes for the <li> tag
 *  deps     => STR  One or more AMD modules to require
 *
 *  Additional options that the view output/url takes can be
 *	passed in the array. Custom options can be added by using
 *	the 'data' key with the	value being an associative array.
 *
 * @param string $menu_name The name of the menu: site, page, userhover, userprofile, groupprofile, or any custom menu
 * @param mixed  $menu_item A \ElggMenuItem object or an array of options
 *
 * @return bool False if the item could not be added
 * @since 1.8.0
 */
function elgg_register_menu_item($menu_name, $menu_item) {
	if (is_array($menu_item)) {
		$options = $menu_item;
		$menu_item = ElggMenuItem::factory($options);
		if (!$menu_item instanceof ElggMenuItem) {
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

	$menus = _elgg_services()->config->menus;
	if (empty($menus)) {
		$menus = [];
	}

	$menus[$menu_name][] = $menu_item;
	_elgg_services()->config->menus = $menus;

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
	$menus = _elgg_services()->config->menus;
	if (empty($menus)) {
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
 * Convenience function for registering a button to the title menu
 *
 * The URL must be resolvable in a route definition with the name "$name:$entity_type:$entity_subtype".
 * The label of the button is "$name:$entity_type:$entity_subtype" so that must be defined in a
 * language file.
 *
 * This is used primarily to support adding an add content button
 *
 * @param string $handler        (deprecated) The handler to use or null to autodetect from context
 * @param string $name           Name of the button (defaults to 'add')
 * @param string $entity_type    Optional entity type to be added (used to verify canWriteToContainer permission)
 * @param string $entity_subtype Optional entity subtype to be added (used to verify canWriteToContainer permission)
 * @return void
 * @since 1.8.0
 */
function elgg_register_title_button($handler = null, $name = 'add', $entity_type = '', $entity_subtype = '') {

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
	if (!$owner instanceof \ElggEntity || empty($entity_type) || empty($entity_subtype) || !$owner->canWriteToContainer(0, $entity_type, $entity_subtype)) {
		return;
	}
	
	$href = elgg_generate_url("$name:$entity_type:$entity_subtype", [
		'guid' => $owner->guid,
	]);
	
	if (empty($href) && isset($handler)) {
		elgg_deprecated_notice('Using handler for fallback HREF determination is no longer supported in ' . __METHOD__, '4.0');
	}
	
	if (elgg_language_key_exists("$name:$entity_type:$entity_subtype")) {
		$text = elgg_echo("$name:$entity_type:$entity_subtype");
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
