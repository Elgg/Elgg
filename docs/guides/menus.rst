Menus
#####

Elgg contains helper code to build menus throughout the site.

Every single menu requires a name, as does every single menu item. These are
required in order to allow easy overriding and manipulation, as well as to
provide hooks for theming.

.. contents:: Contents
   :local:
   :depth: 1

Basic usage
===========

Basic functionalities can be achieved through these two functions:

 - `elgg_register_menu_item()`__ to add an item to a menu
 - `elgg_unregister_menu_item()`__ to remove an item from a menu

You normally want to call them from your plugin's init function.

__ http://reference.elgg.org/engine_2lib_2navigation_8php.html#a344445364078d03607904c44bad36c1c
__ http://reference.elgg.org/engine_2lib_2navigation_8php.html#ae26ee09e330a130984c9a6f9e19f6546

Examples
--------

.. code-block:: php

	// Add a new menu item to the site main menu
	elgg_register_menu_item('site', array(
		'name' => 'itemname',
		'text' => 'This is text of the item',
		'href' => '/item/url',
	));

.. code:: php

	// Remove the "Elgg" logo from the topbar menu
	elgg_unregister_menu_item('topbar', 'elgg_logo');

Advanced usage
==============

You can get more control over menus by using :doc:`plugin hooks </design/events>`
and the public methods provided by the ElggMenuItem__ class.

There are two hooks that can be used to modify a menu:
 - ``'register', 'menu:<menu name>'`` to add or modify items (especially in dynamic menus)
 - ``'prepare', 'menu:<menu name>'`` to modify the structure of the menu before it is displayed

When you register a plugin hook handler, replace the ``<menu name>`` part with the
internal name of the menu.

The third parameter passed into a menu handler contains all the menu items that
have been registered so far by Elgg core and other enabled plugins. In the
handler we can loop through the menu items and use the class methods to
interact with the properties of the menu item.

__ http://reference.elgg.org/classElggMenuItem.html

Examples
--------

**Example 1:** Change the URL for menu item called "albums" in the ``owner_block`` menu:

.. code-block:: php

	/**
	 * Initialize the plugin
	 */
	function my_plugin_init() {
		// Register a plugin hook handler for the owner_block menu 
		elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'my_owner_block_menu_handler');
	}

	/**
	 * Change the URL of the "Albums" menu item in the owner_block menu
	 */
	function my_owner_block_menu_handler($hook, $type, $menu, $params) {
		$owner = $params['entity'];

		// Owner can be either user or a group, so we
		// need to take both URLs into consideration:
		switch ($owner->getType()) {
			case 'user':
				$url = "album/owner/{$owner->guid}";
				break;
			case 'group':
				$url = "album/group/{$owner->guid}:
				break;
		}

		foreach ($menu as $key => $item) {
			if ($item->getName() == 'albums') {
				// Set the new URL
				$item->setURL($url);
				break;
			}
		}

		return $menu;
	}

**Example 2:** Modify the ``entity`` menu for the ``ElggBlog`` objects
 - Remove the thumb icon
 - Change the "Edit" text into a custom icon

.. code-block:: php

	/**
	 * Initialize the plugin
	 */
	function my_plugin_init() {
		// Register a plugin hook handler for the entity menu 
		elgg_register_plugin_hook_handler('register', 'menu:entity', 'my_entity_menu_handler');
	}

	/**
	 * Customize the entity menu for ElggBlog objects
	 */
	function my_entity_menu_handler($hook, $type, $menu, $params) {
		// The entity can be found from the $params parameter
		$entity = $params['entity'];

		// We want to modify only the ElggBlog objects, so we
		// return immediately if the entity is something else
		if (!$entity instanceof ElggBlog) {
			return $menu;
		}

		foreach ($menu as $key => $item) {
			switch ($item->getName()) {
				case 'likes':
					// Remove the "likes" menu item
					unset($menu[$key]);
					break;
				case 'edit':
					// Change the "Edit" text into a custom icon
					$item->setText(elgg_view_icon('pencil'));
					break;
			}
		}

		return $menu;
	}

Creating a new menu
===================

Elgg provides multiple different menus by default. Sometimes you may however
need some menu items that don't fit in any of the existing menus.
If this is the case, you can create your very own menu with the
`elgg_view_menu()`__ function. You must call the function from the view,
where you want to menu to be displayed.

__ http://reference.elgg.org/views_8php.html#ac2d475d3efbbec30603537013ac34e22

**Example:** Display a menu called "my_menu" that displays it's menu items 
in alphapetical order:

.. code-block:: php

	echo elgg_view_menu('my_menu', array('sort_by' => 'title'));

You can now add new items to the menu like this:

.. code-block:: php

	elgg_register_menu_item('my_menu', array(
		'name' => 'my_page',
		'href' => 'path/to/my_page',
		'text' => elgg_echo('my_plugin:my_page'),
	));

Furthermore it is now possible to modify the menu using the hooks
``'register', 'menu:my_menu'`` and ``'prepare', 'menu:my_menu'``.

Theming
=======

The menu name, section names, and item names are all embedded into the HTML as
CSS classes (normalized to contain only hyphens, rather that underscores or
colons). This increases the size of the markup slightly but provides themers
with a high degree of control and flexibility when styling the site.

**Example:** The following would be the output of the ``foo`` menu with sections
``alt`` and ``default`` containing items ``baz`` and ``bar`` respectively.

.. code-block:: html

	<ul class="elgg-menu elgg-menu-foo elgg-menu-foo-alt">
		<li class="elgg-menu-item elgg-menu-item-baz"></li>
	</ul>
	<ul class="elgg-menu elgg-menu-foo elgg-menu-foo-default">
		<li class="elgg-menu-item elgg-menu-item-bar"></li>
	</ul>
