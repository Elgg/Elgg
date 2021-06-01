Plugins
#######

Plugins must provide a ``composer.json`` file in the plugin root in order to be recognized by Elgg.

.. contents:: Contents
   :local:
   :depth: 1

elgg-plugin.php
===============

``elgg-plugin.php`` is a static plugin configuration file. It is read by Elgg to configure various services,
and must return an array if present. It should not be included by plugins and is not guaranteed to run at any particular time.
Besides magic constants like ``__DIR__``, its return value should not change. The currently supported sections are:

 * ``plugin`` - defines plugin information and dependencies
 * ``bootstrap`` - defines a class used to bootstrap the plugin
 * ``entities`` - defines entity types and classes, and optionally registers them for search
 * ``actions`` - eliminates the need for calling ``elgg_register_action()``
 * ``routes`` - eliminates the need for calling ``elgg_register_route()``
 * ``settings`` - eliminates the need for setting default values on each call to ``elgg_get_plugin_setting()``
 * ``user_settings`` - eliminates the need for setting default values on each call to ``elgg_get_plugin_user_setting()``
 * ``views`` - allows plugins to alias vendor assets to a path within the Elgg's view system
 * ``widgets`` - eliminates the need for calling ``elgg_register_widget_type()``
 * ``events`` - eliminates the need for calling ``elgg_register_event_handler()``
 * ``hooks`` - eliminates the need for calling ``elgg_register_plugin_hook_handler()``
 * ``cli_commands`` - an array of ``Elgg/Cli/Command`` classes to extend the feature of ``elgg-cli``
 * ``view_extensions`` - eliminates the need for calling ``elgg_extend_view()`` or ``elgg_unextend_view()``
 * ``theme`` - an array of theme variables
 * ``group_tools`` - an array of available group tool options
 * ``view_options`` - an array of views with extra options
 * ``notifications`` - an array of notification events


.. code-block:: php

	return [
		'plugin' => [
			'name' => 'Plugin Name', // readable plugin name
			'activate_on_install' => true, // only used on a fresh install
			'version' => '1.3.1', // version of the plugin
			'dependencies' => [
				// optional list op plugin dependencies
				'blog' => [],
				'activity' => [
					'position' => 'after',
					'must_be_active' => false,
				],
				'file' => [
					'position' => 'before',
					'version' => '>2', // composer notation of required version constraint
				],
			],
		],
	
		// Bootstrap must implement \Elgg\PluginBootstrapInterface
		'bootstrap' => MyPluginBootstrap::class,

		'entities' => [
			[
				// Register a new object subtype and tell Elgg to use a specific class to instantiate it
				'type' => 'object',
				'subtype' => 'my_object_subtype',
				'class' => MyObjectClass::class,

				// Register this subtype for search
				'searchable' => true,
			],
		],

		'actions' => [
			// Registers an action
			// By default, action is registered with 'logged_in' access
			// By default, Elgg will look for file in plugin's actions/ directory: actions/my_plugin/action.php
			'my_plugin/action/default' => [],

			'my_plugin/action/custom_access' => [
				'access' => 'public', // supports 'public', 'logged_in', 'admin'
			],

			// you can use action controllers instead of action files by setting the controller parameters
			// controller must be a callable that receives \Elgg\Request as the first and only argument
			// in example below, MyActionController::__invoke(\Elgg\Request $request) will be called
			'my_plugin/action/controller' => [
				'controller' => MyActionController::class,
			],
		],

		'routes' => [
			// routes can be associated with resource views or controllers
			'collection:object:my_object_subtype:all' => [
				'path' => '/my_stuff/all',
				'resource' => 'my_stuff/all', // view file is in resources/my_stuff/all
			],

			// similar to actions, routes can be associated with a callable controller that receives an instance of \Elgg\Request
			'collection:object:my_object_subtype:json' => [
				'path' => '/my_stuff/json',
				'controller' => JsonDumpController::class,
			],

			// route definitions support other parameters, such as 'middleware', 'requirements', 'defaults'
			// see elgg_register_route() for all options
		],

		'widgets' => [
			// register a new widget
			// corresponds to a view in widgets/my_stuff/content
			'my_stuff' => [
				'description' => elgg_echo('widgets:my_stuff'),
				'context' => ['profile', 'dashboard'],
			],
		],

		'settings' => [
			'plugin_setting_name' => 'plugin_setting_value',
		],

		'user_settings' => [
			'user_setting_name' => 'user_setting_value',
		],

		'views' => [
			'default' => [
				'cool_lib/' => __DIR__ . '/vendors/cool_lib/dist/',
			],
		],
		
		'hooks' => [
			'register' => [
				'menu:owner_block' => [
					'blog_owner_block_menu' => [
						'priority' => 700,
					],
				],
			],
			'likes:is_likable' => [
				'object:blog' => [
					'Elgg\Values::getTrue' => [],
				],
			],
			'usersettings:save' => [
				'user' => [
					'_elgg_save_notification_user_settings' => ['unregister' => true],
				],
			],
		],
		
		'events' => [
			'delete' => [
				'object' => [
					'file_handle_object_delete' => [
						'priority' => 999,
					],
				],
			],
			'create' => [
				'relationship' => [
					'_elgg_send_friend_notification' => [],
				],
			],
			'log' => [
				'systemlog' => [
					'Elgg\SystemLog\Logger::log' => ['unregister' => true],
				],
			],
		],
		
		'cli_commands' => [
			\My\Plugin\CliCommand::class,
			'\My\Plugin\OtherCliCommand',
		],
		
		'view_extensions' => [
			'elgg.js' => [
				'bookmarks.js' => [],
			],
			'page/components/list' => [
				'list/extension' => [
					'priority' => 600,
				],
			],
			'forms/usersettings/save' => [
				'core/settings/account/password' => [
					'unextend' => true,
				],
			],
		],
		
		'theme' => [
			'body-background-color' => '#000',
		],
		
		'group_tools' => [
			'activity' => [], // just use default behaviour
			'blog', [
				'default_on' => false,
			],
			'forum' => [
				'unregister' => true, // unregisters the group tool option
			],
		],
		
		'view_options' => [
			'likes/popup' => [
				'ajax' => true, // registers the view available via ajax
			],
			'likes/popup' => [
				'ajax' => false, // unregisters the view available via ajax
			],
			'manifest.json' => [
				'simplecache' => true, // register view as usable in the simplecache
			],
		],
		'notifications' => [
			'object' => [
				'blog' => [
					'publish' => true, // registers the event to be notified
				],
				'thewire' => [
					'create' => false, // unregisters the event to be notified
				],
				'page' => [
					'create' => MyPluginPageCreateEventHandler::class, // a custom event handler, needs to be an extension of a NotificationEventHandler
				],
			],
		],
	];

Bootstrap class
===============

As of Elgg 3.0 the recommended way to bootstrap you plugin is to use a bootstrap class. This class must implement 
the ``\Elgg\PluginBootstrapInterface`` interface. You can register you bootstrap class in the ``elgg-plugin.php``.

The bootstrap interface defines several function to be implemented which are called during different events in the system booting process.

.. seealso::

	For more information about the different functions defined in the ``\Elgg\PluginBootstrapInterface`` please read  :doc:`plugins/bootstrap`

elgg-services.php
=================

Plugins can attach their services to Elgg's public DI container by providing PHP-DI definitions in ``elgg-services.php``
in the root of the plugin directory.

This file must return an array of PHP-DI definitions. Services will by available via ``elgg()``.

.. code-block:: php

   return [
      PluginService::class => \DI\object()->constructor(\DI\get(DependencyService::class)),
   ];

Plugins can then use PHP-DI API to autowire and call the service:

.. code-block:: php

   $service = elgg()->get(PluginService::class);

See `PHP-DI documentation <http://php-di.org>`_ for a comprehensive list of definition and invokation possibilities.

composer.json
=============

Since Elgg supports being installed as a `Composer`_ dependency, having your plugins also support Composer makes for easier installation by 
site administrators. In order to make your plugin compatible with Composer you need to at least have a ``composer.json`` file in the root of your 
plugin.

Here is an example of a ``composer.json`` file:

.. include:: /info/composer.json
	:code: json
	
Read more about the ``composer.json`` format on the `Composer`_ website.

Important parts in the ``composer.json`` file are:

- ``name``: the name of your plugin, keep this inline with the name of your plugin folder to ensure correct installation
- ``type``: this will tell Composer where to install your plugin, ALWAYS keep this as ``elgg-plugin``
- ``require``: the ``composer/installers`` requirement is to make sure Composer knows where to install your plugin

As a suggestion, include a ``conflict`` rule with any Elgg version below your mininal required version, this will help prevent the accidental 
installation of your plugin on an incompatible Elgg version.

After adding a ``composer.json`` file to your plugin project, you need to register your project on `Packagist`_ in order for other people to be able to 
install your plugin.

Tests
=====

It's encouraged to create PHPUnit test for your plugin. All tests should be located in ``tests/phpunit/unit`` for unit tests and 
``tests/phpunit/integration`` for integration tests.

An easy example of adding test is the ``ViewStackTest``, this will test that the views in your plugin are registered correctly and have no 
syntax errors. To add this test create a file ``ViewStackTest.php`` in the folder ``tests/phpunit/unit/<YourNameSpace>/<YourPluginName>/``
with the content:

.. code-block:: php
	
	namespace <YourNameSpace>\<YourPluginName>;
	
	/**
	 * @group ViewsService
	 */
	class ViewStackTest extends \Elgg\Plugins\ViewStackTest {
	
	}

.. note::
	
	If you wish to see a better example, look in any of the Elgg core plugins.

.. seealso::
	
	:doc:`/contribute/tests`

Related
=======

.. toctree::
	:maxdepth: 1

	plugins/plugin-skeleton
	plugins/dependencies
	plugins/bootstrap

.. _Composer: https://getcomposer.org/
.. _Packagist: https://packagist.org/
