Upgrading Elgg
##############

This document will guide you through steps necessary to upgrade your Elgg installation to the latest version.

If you've written custom plugins, you should also read the developer guides for
:doc:`information on upgrading plugin code </appendix/upgrade-notes>` for the latest version of Elgg.

Advice
======

* **Back up your database, data directory and code**
* Mind any version-specific comments below
* Version below 2.0 are advised to only upgrade **one minor version at a time**
* You can upgrade from any minor version to any higher minor version in the same major (2.0 -> 2.1 or 2.0 -> 2.3)
* You can only upgrade the latest minor version in the previous major version to any minor version in the next version (2.3 -> 3.0 or 2.3 -> 3.2, but not 2.2 -> 3.x).
* From Elgg 2.3.* you can upgrade to any future version of Elgg without having to go through each minor version (e.g. you can upgrade directly from 2.3.8 to 3.2.5, without having to upgrade to 3.0 and 3.1)
* Try out the new version on a test site before doing an upgrade
* Report any problems in plugins to the plugin authors
* If you are a plugin author you can `report any backwards-compatibility issues to GitHub <issues_>`_

.. _issues: https://github.com/Elgg/Elgg/issues

Basic instructions
==================

From 2.3 to 3.0
===============

1. Update ``composer.json``
---------------------------

If you have used Elgg's starter project to install Elgg 2.3, you may need to update your ``composer.json``:

 * change platform requirements to PHP >= 7.0
 * optionally, set autoloader optimization parameters
 * optionally, disable fxp-asset plugin in favor of asset-packagist

Your ``composer.json`` would look something like this (depending what changes you may have introduced yourself):

.. code-block:: json

	{
		"type": "project",
		"name": "elgg/starter-project",
		"require": {
			"elgg/elgg": "3.*"
		},
		"config": {
			"process-timeout": 0,
			"platform": {
				"php": "7.0"
			},
			"fxp-asset": {
				"enabled": false
			},
			"optimize-autoloader": true,
			"apcu-autoloader": true
		},
		"repositories": [
			{
				"type": "composer",
				"url": "https://asset-packagist.org"
		  }
		]
	}


2. Update ``.htaccess``
-----------------------

Find the line:

.. code-block:: apache

	RewriteRule ^(.*)$ index.php?__elgg_uri=$1 [QSA,L]

And replace it with:

.. code-block:: apache

	RewriteRule ^(.*)$ index.php [QSA,L]


3a. Composer Upgrade (recommended)
----------------------------------

If you had your Elgg 2.3 project installed using composer, you can follow this sequence:

**Back up your database, data directory, and code**


.. code-block:: sh

	composer self-update

	cd ./path/to/project/root
	composer require elgg/elgg:~3.0.0
	composer update
	vendor/bin/elgg-cli upgrade async -v


3b. Manual Upgrade (legacy approach)
------------------------------------

Manual upgrades are a major undertaking for site admins. We discourage you from maintaining an Elgg installation using
ZIP dist packages. Save yourself some time by learning how to use ``composer`` and version control systems, such as ``git``.
This task will also be complicated if you have third-party plugins and/or have made any modifications to core files!

#. **Back up your database, data directory, and code**
#. Log in as an admin to your site
#. Download the new version of Elgg from http://elgg.org
#. Update the files
    * If upgrading to a major version, you need to overwrite all core files and remove any files that were removed from Elgg core,
      as they may interfere with proper functioning of your site.
    * If upgrading to a minor version or patching, you need to overwrite all core files.
#. Merge any new changes to the rewrite rules
    * For Apache from ``install/config/htaccess.dist`` into ``.htaccess``
    * For Nginx from ``install/config/nginx.dist`` into your server configuration (usually inside ``/etc/nginx/sites-enabled``)
#. Visit http://your-elgg-site.com/upgrade.php
#. Execute asynchronous upgrades at http://your-elgg-site.com/admin/upgrades

.. note::

   Any modifications should have been written within plugins, so that they are not lost on overwriting.
   If this is not the case, take care to maintain your modifications.

.. note::

   If you are unable to access ``upgrade.php`` script and receive an error, add ``$CONFIG->security_protect_upgrade = false;``
   to your ``settings.php`` and remove it after you have completed all of the upgrade steps.

.. note::

   If you encounter issues with plugins during the upgrade, add an empty file called ``disabled`` in your ``/mod/`` directory.
   This will disable the plugins, so that you can finish the core upgrade. You can then deal with issues on per-plugin basis.


If you have installed Elgg using a dist package but would now like to switch to composer:

 * Upgrade your current installation using Manual Upgrade method
 * Move your codebase to a temporary location
 * Create a new composer project using Elgg's starter project following :doc:`installation instructions </intro/install>` in the root directory of your current installation
 * Copy third-party plugins from your old installation into ``/mod`` directory
 * Run Elgg's installer using your browser or ``elgg-cli`` tool
 * When you reach the database step, provide the same credentials you have used for manual installation, Elgg will understand that is's an existing installation and will not override any database values
 * Optionally commit your new project to version control


Earlier versions
================

Check Elgg documentation that corresponds to the Elgg version you want to upgrade to, by switching the documentation
version in the lower left corner of `Upgrading docs <upgrading-docs_>`

.. _upgrading-docs: http://learn.elgg.org/en/stable/admin/upgrading.html