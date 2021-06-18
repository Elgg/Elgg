Plugin Dependencies
###################

In Elgg the plugin dependencies system is there to prevent plugins from being used on incompatible systems.

.. contents:: Contents
   :local:
   :depth: 2

Overview
========

The dependencies system is controlled through a plugin's ``elgg-plugin.php`` file or ``composer.json``. Plugin authors can specify that a plugin:

- Requires certain Elgg plugins, PHP version or PHP extensions.
- Conflicts with certain Elgg versions or plugins.

PHP version or extension
========================

Add a section in your ``composer.json`` as described in de `Composer JSON reference <https://getcomposer.org/doc/04-schema.md#package-links>`_

.. code-block:: json

	{
		"require": {
			"php": ">7.4",
			"ext-json": "*"
		}
	}

Require an Elgg plugin
======================

Add a section to the ``elgg-plugin.php``, also see :doc:`/guides/plugins`

.. code-block:: php

	return [
		'plugin' => [
			'dependencies' => [
				// optional list op plugin dependencies
				'blog' => [], // blog needs to be active
				'activity' => [
					'position' => 'after', // in the plugin order this plugin must be after the activity plugin
					'must_be_active' => false, // but the plugin isn't required to be active, but if active order will be checked
				],
				'file' => [
					'position' => 'before', // file must be active and this plugin needs to be before the file plugin in the plugin order
					'version' => '>2', // composer notation of required version constraint
				],
			],
		],
	];

Conflicts
=========

Add a section in your ``composer.json`` as described in de `Composer JSON reference <https://getcomposer.org/doc/04-schema.md#package-links>`_

.. code-block:: json

	{
		"conflict": {
			"elgg/elgg": "<4.0",
			"elgg/dataviews": "<1.0 || >= 1.5"
		}
	}
