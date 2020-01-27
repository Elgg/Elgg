Plugin bootstrap
################

In order to bootstrap your plugin as of Elgg 3.0 you can use a bootstrap class. This class must implement 
the ``\Elgg\PluginBootstrapInterface`` interface, but it's recommended you extend the ``\Elgg\PluginBootstrap`` abstract 
class as some preparations have already been done.

If you only need a limited subset of the bootstrap functions your class can also extend the ``\Elgg\DefaultPluginBootstrap`` class,
this class already has all the functions of ``\Elgg\PluginBootstrapInterface`` implemented. So you can overload only the functions you need.

.. contents:: Contents
   :local:
   :depth: 2

Registering the bootstrap class
===============================

You must register your bootstrap class in the ``elgg-plugin.php`` file.

.. code-block:: php

	return [
		// Bootstrap must implement \Elgg\PluginBootstrapInterface
		'bootstrap' => MyPluginBootstrap::class,
	];

Available functions
===================

->load()
--------

Executed during ``plugins_load``, ``system`` event

Allows the plugin to require additional files, as well as configure services prior to booting the plugin.

->boot()
--------

Executed during ``plugins_boot:before``, ``system`` event

Allows the plugin to register handlers for ``plugins_boot``, ``system`` and ``init``, ``system`` events, as 
well as implement boot time logic.

->init()
--------

Executed during ``init``, ``system`` event

Allows the plugin to implement business logic and register all other handlers.

->ready()
---------

Executed during ``ready``, ``system`` event

Allows the plugin to implement logic after all plugins are initialized.

->shutdown()
------------

Executed during ``shutdown``, ``system`` event

Allows the plugin to implement logic during shutdown.

->activate()
------------

Executed when plugin is activated, after ``activate``, ``plugin`` event.

->deactivate()
--------------

Executed when plugin is deactivated, after ``deactivate``, ``plugin`` event.

->upgrade()
-----------

Registered as handler for ``upgrade``, ``system`` event

Allows the plugin to implement logic during system upgrade.

Available helper functions
==========================

This assumes your bootstrap class extends the ``\Elgg\PluginBootstrap`` abstract class or the ``\Elgg\DefaultPluginBootstrap`` class.

->elgg()
--------

Returns Elgg's public DI container. This can be helpfull if you wish to register plugin hooks or event listeners.

.. code-block:: php

	$hooks = $this->elgg()->hooks;
	$hooks->registerHandler('register', 'menu:entity', 'my_custom_menu_callback');
	
	$events = $this->elgg()->events;
	$events->registerHandler('create', 'object', MyCustomObjectHandler::class);

->plugin()
----------

Returns plugin entity this bootstrap is related to. This makes it easier to get plugin settings.

.. code-block:: php

	$plugin = $this->plugin();
	$my_setting = $plugin->getSetting('my_setting');
