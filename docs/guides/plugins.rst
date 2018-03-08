Plugins
#######

Plugins must provide a ``manifest.xml`` file in the plugin root in order to be recognized by Elgg.

.. contents:: Contents
   :local:
   :depth: 1

start.php
=========

The ``start.php`` file bootstraps plugin by registering event listeners and plugin hooks.

It is advised that plugins return an instance of Closure from the ``start.php`` instead of placing registrations in the root of the file.
This allows for consistency in Application bootstrapping, especially for testing purposes.

.. code-block:: php

    function my_plugin_does_something_else() {
        // Some procedural code that you want to run before any events are fired
    }

    function my_plugin_init() {
        // Your plugin's initialization logic
    }

    function my_plugin_rewrite_hook() {
        // Path rewrite hook
    }

    return function() {
        my_plugin_do_something_else();
        elgg_register_event_handler('init', 'system', 'my_plugin_init');
        elgg_register_plugin_hook_handler('route:rewrite', 'proifle', 'my_plugin_rewrite_hook');
    }


elgg-plugin.php
===============

This optional file is read by Elgg to configure various services, and must return an array if present.
It should not be included by plugins and is not guaranteed to run at any particular time. Besides magic
constants like ``__DIR__``, its return value should not change. The currently supported sections are: 

 * ``views``
 * ``actions``
 * ``settings``
 * ``user_settings``
 * ``widgets``

elgg-services.php
=================

Plugins can attach their services to Elgg's public DI container by providing PHP-DI definitions in ``elgg-services.php``
in the root of the plugin directory.

This file must return an array of PHP-DI definitions. Services will by available via ``elgg()``.

.. code-block::php

   return [
      PluginService::class => \DI\object()->constructor(\DI\get(DependencyService::class)),
   ];

Plugins can then use PHP-DI API to autowire and call the service:

.. code-block::php

   $service = elgg()->get(PluginService::class);

See `PHP-DI documentation <http://php-di.org>`_ for a comprehensive list of definition and invokation possibilities.

Syntax
------

Here's a trivial example configuring view locations via the ``views`` key:

.. code-block:: php

	return [
		'views' => [
			'default' => [
				'file/icon/' => __DIR__ . '/graphics/icons',
			],
		],
	];

activate.php, deactivate.php
============================

The ``activate.php`` and ``deactivate.php`` files contain procedural code that will run
upon plugin activation and deactivation. Use these files to perform one-time
events such as registering a persistent admin notice, registering subtypes, or performing
garbage collection when deactivated.

manifest.xml
============

Elgg plugins are required to have a ``manifest.xml`` file in the root of a plugin.

The ``manifest.xml`` file includes information about the plugin itself, requirements to run the plugin, and optional information including 
where to display the plugin in the admin area and what APIs the plugin provides.

Syntax
------

The manifest file is a standard XML file in UTF-8. Everything is a child of the ``<plugin_manifest>`` element.

.. code-block:: xml

	<?xml version="1.0" encoding="UTF-8" ?>
	<plugin_manifest xmlns="http://www.elgg.org/plugin_manifest/1.8">

The manifest syntax is as follows:

.. code-block:: xml

	<name>value</name>

Many elements can contain children attributes:

.. code-block:: xml

	<parent_name>
		<child_name>value</child_name>
		<child_name_2>value_2</child_name_2>
	</parent_name>

Required Elements
-----------------

All plugins are required to define the following elements in their manifest files:

* id - This has the name as the directory that the plugin uses.
* name - The display name of the plugin.
* author - The name of the author who wrote the plugin.
* version - The version of the plugin.
* description - A description of the what the plugin provides, its features, and other relevant information
* requires - Each plugin must specify the release of Elgg it was developed for. See the plugin Dependencies page for more information.

Available Elements
------------------

In addition to the require elements above, the follow elements are available to use:

* blurb - A short description of the plugin.
* category - The category of the plugin. It is recommended to follow the :doc:`guidelines` and use one of the defined categories. There can be 
  multiple entries.
* conflicts - Specifies that the plugin conflicts with a certain system configuration.
* copyright - The plugin's copyright information.
* license - The plugin's license information.
* provides - Specifies that this plugin provides the same functionality as another Elgg plugin or a PHP extension.
* screenshot - Screenshots of the plugin. There can be multiple entries. See the advanced example for syntax.
* suggests - Parallels the requires system, but doesn't affect if the plugin can be enabled. Used to suggest other plugins that interact or build 
  on the plugin.
* website - A link to the website for the plugin.

.. seealso::

	:doc:`plugins/dependencies`

Simple Example
--------------

This manifest file is the bare minimum a plugin must have.

.. code-block:: xml

	<?xml version="1.0" encoding="UTF-8"?>
	<plugin_manifest xmlns="http://www.elgg.org/plugin_manifest/1.8">
		<name>Example Manifest</name>
		<author>Elgg</author>
		<version>1.0</version>
		<description>This is a simple example of a manifest file. In this example, there are not screenshots, dependencies, or additional information about the plugin.</description>

		<requires>
			<type>elgg_release</type>
			<version>1.9</version>
		</requires>
	</plugin_manifest>

Advanced example
----------------

This example uses all of the available elements:

.. code-block:: xml

	<?xml version="1.0" encoding="UTF-8"?>
	<plugin_manifest xmlns="http://www.elgg.org/plugin_manifest/1.8">
		<name>Example Manifest</name>
		<author>Brett Profitt</author>
		<version>1.0</version>
		<blurb>This is an example manifest file.</blurb>
		<description>This is a simple example of a manifest file. In this example, there are many options used, including screenshots, dependencies, and additional information about the plugin.</description>
		<website>http://www.elgg.org/</website>
		<copyright>(C) Brett Profitt 2014</copyright>
		<license>GNU Public License version 2</license>

		<category>3rd_party_integration</category>

		<requires>
			<type>elgg_release</type>
			<version>1.9.1</version>
		</requires>

		<!-- The path is relative to the plugin's root. -->
		<screenshot>
			<description>Elgg profile.</description>
			<path>screenshots/profile.png</path>
		</screenshot>

		<provides>
			<type>plugin</type>
			<name>example_plugin</name>
			<version>1.5</version>
		</provides>

		<suggests>
			<type>plugin</type>
			<name>twitter</name>
			<version>1.0</version>
		</suggests>
	</plugin_manifest>

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
