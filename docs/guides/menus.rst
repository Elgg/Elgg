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

 - ``elgg_register_menu_item()`` to add an item to a menu
 - ``elgg_unregister_menu_item()`` to remove an item from a menu

You normally want to call them from your plugin's init function.

Examples
--------

.. code-block:: php

	// Add a new menu item to the site main menu
	elgg_register_menu_item('site', array(
		'name' => 'itemname',
		'text' => 'This is text of the item',
		'href' => '/item/url',
	));

.. code-block:: php

	// Remove the "Elgg" logo from the topbar menu
	elgg_unregister_menu_item('topbar', 'elgg_logo');
	
Admin menu
==========

You can also register ``page`` menu items to the admin backend menu. When registering for the admin menu you can set the context of
the menu items to ``admin`` so the menu items only show in the ``admin`` context. There are 3 default sections to add your menu items to.
 
 - ``administer`` for daily tasks, usermanagement and other actionable tasks
 - ``configure`` for settings, configuration and utilities that configure stuff
 - ``information`` for statistics, overview of information or status


Advanced usage
==============

You can get more control over menus by using :doc:`plugin hooks </design/events>`
and the public methods provided by the ``ElggMenuItem`` class.

There are three hooks that can be used to modify a menu:
 - ``'parameters', 'menu:<menu name>'`` to add or modify parameters use for the menu building (eg. sorting)
 - ``'register', 'menu:<menu name>'`` to add or modify items (especially in dynamic menus)
 - ``'prepare', 'menu:<menu name>'`` to modify the structure of the menu before it is displayed

When you register a plugin hook handler, replace the ``<menu name>`` part with the
internal name of the menu.

The third parameter passed into a menu handler contains all the menu items that
have been registered so far by Elgg core and other enabled plugins. In the
handler we can loop through the menu items and use the class methods to
interact with the properties of the menu item.

In some cases a more granular version of the ``register`` and ``prepare`` menu hooks exist with ``menu:<menu name>:<type>:<subtype>``,
this applies when the menu gets provided an ``\ElggEntity`` in ``$params['entity']`` or an ``\ElggAnnotation`` in ``$params['annotation']``
or an ``\ElggRelationship`` in ``$params['relationship']``.

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
	function my_owner_block_menu_handler(\Elgg\Hook $hook) {
		$owner = $hook->getEntityParam();

		// Owner can be either user or a group, so we
		// need to take both URLs into consideration:
		switch ($owner->getType()) {
			case 'user':
				$url = "album/owner/{$owner->guid}";
				break;
			case 'group':
				$url = "album/group/{$owner->guid}";
				break;
		}

		$items = $hook->getValue();
		if ($items->has('albums')) {
			$items->get('albums')->setURL($url);
		}

		return $items;
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
	function my_entity_menu_handler(\Elgg\Hook $hook) {
		// The entity can be found from the $params parameter
		$entity = $hook->getEntityParam();

		// We want to modify only the ElggBlog objects, so we
		// return immediately if the entity is something else
		if (!$entity instanceof ElggBlog) {
			return;
		}

		$items = $hook->getValue();
		
		$items->remove('likes');

		if ($items->has('edit')) {
			$items->get('edit')->setText('Modify');
			$items->get('edit')->icon = 'pencil';
		}

		return $items;
	}

Creating a new menu
===================

Elgg provides multiple different menus by default. Sometimes you may however
need some menu items that don't fit in any of the existing menus.
If this is the case, you can create your very own menu with the
``elgg_view_menu()`` function. You must call the function from the view,
where you want to menu to be displayed.

**Example:** Display a menu called "my_menu" that displays it's menu items 
in alphapetical order:

.. code-block:: php

	// in a resource view
	echo elgg_view_menu('my_menu', array('sort_by' => 'text'));

You can now add new items to the menu like this:

.. code-block:: php

	// in plugin init
	elgg_register_menu_item('my_menu', array(
		'name' => 'my_page',
		'href' => 'path/to/my_page',
		'text' => elgg_echo('my_plugin:my_page'),
	));

Furthermore it is now possible to modify the menu using the hooks
``'register', 'menu:my_menu'`` and ``'prepare', 'menu:my_menu'``.

Child Dropdown Menus
====================

Child menus can be configured using ``child_menu`` factory option on the parent item.

``child_menu`` options array accepts ``display`` parameter, which can be used
to set the child menu to open as ``dropdown`` or be displayed via ``toggle``.
All other key value pairs will be passed as attributes to the ``ul`` element.


.. code-block:: php

	// Register a parent menu item that has a dropdown submenu
	elgg_register_menu_item('my_menu', array(
		'name' => 'parent_item',
		'href' => '#',
		'text' => 'Show dropdown menu',
		'child_menu' => [
			'display' => 'dropdown',
			'class' => 'elgg-additional-child-menu-class',
			'data-position' => json_encode([
				'at' => 'right bottom',
				'my' => 'right top',
				'collision' => 'fit fit',
			]),
			'data-foo' => 'bar',
			'id' => 'dropdown-menu-id',
		],
	));

	// Register a parent menu item that has a hidden submenu toggled when item is clicked
	elgg_register_menu_item('my_menu', array(
		'name' => 'parent_item',
		'href' => '#',
		'text' => 'Show submenu',
		'child_menu' => [
			'display' => 'dropdown',
			'class' => 'elgg-additional-submenu-class',
			'data-toggle-duration' => 'medium',
			'data-foo' => 'bar2',
			'id' => 'submenu-id',
		],
	));


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

Toggling Menu Items
===================

There are situations where you wish to toggle menu items that are actions that are the opposite
of each other and ajaxify them. E.g. like/unlike, friend/unfriend, ban/unban, etc. Elgg has built-in support
for this kind of actions. When you register a menu item you can provide a name of the menu item (in the same menu)
that should be toggled. An ajax call will be made using the href of the menu item.

.. code-block:: php

	elgg_register_menu_item('my_menu', [
		'name' => 'like',
		'data-toggle' => 'unlike',
		'href' => 'action/like',
		'text' => elgg_echo('like'),
	]);

	elgg_register_menu_item('my_menu', [
		'name' => 'unlike',
		'data-toggle' => 'like',
		'href' => 'action/unlike',
		'text' => elgg_echo('unlike'),
	]);

.. note::

	The menu items are optimistically toggled. This means the menu items are toggled before the actions finish. If the actions fail,
	the menu items will be toggled back.
	
JavaScript
==========

It is common that menu items rely on JavaScript. You can bind client-side events
to menu items by placing your JavaScript into AMD module and defining the
requirement during the registration.

.. code-block:: php

	elgg_register_menu_item('my_menu', array(
		'name' => 'hide_on_click',
		'href' => '#',
		'text' => elgg_echo('hide:on:click'),
		'item_class' => '.hide-on-click',
		'deps' => ['navigation/menu/item/hide_on_click'],
	));


.. code-block:: js

    // in navigation/menu/item/hide_on_click.js
    define(function(require) {
        var $ = require('jquery');

        $(document).on('click', '.hide-on-click', function(e) {
            e.preventDefault();
            $(this).hide();
        });
    });
