Moving a plugin to its own repository
#####################################

.. contents:: Contents
   :local:
   :depth: 2

Plugin extraction steps
=======================

Move the code to its own repository
-----------------------------------

Follow the GitHub guide `Splitting a subfolder out into a new repository`_.
This will make sure that the commit history is preserved.

Dependencies
------------

If the plugin has dependencies on any external libraries, make sure these dependencies are managed. 
For example if a PHP library is required which comes bundled with Elgg core, make sure to add it to the ``composer.json`` of this plugin 
as you can't rely on Elgg core keeping the library.  

Commit the code
---------------

During the GitHub guide a new repository is created for the plugin you're trying to move.

Since an attemp was already made to extract some of the plugins to their own repository maybe the repository already exists.

If the repository didn't exist for the plugin, make sure you create it under the `Elgg organisation`_.

If the repository already exists, the best way to update the code would be by a Pull Request. This will however probably fail because of a 
difference in how the repository was first created (as discussed in this `GitHub issue`_).

The initial repositories where created with

.. code-block:: sh

	git subtree split

and the guide calls for

.. code-block:: sh

	git filter-branch --prune-empty --subdirectory-filter

This will leave a difference in the commits which GitHub is unable to resolve. In that case you'll have to force push the changes to 
the existing Elgg plugin repository.

.. warning::

	Since this will override all the history in the plugin repository, make sure you know this is what you want to do.

Packagist
---------

Make sure the ``composer.json`` of the plugin contains all the relevant information. Here is an example:

.. code-block:: json
	
	{
		"name": "elgg/<name of the repository>",
		"description": "<a description of the plugin>",
		"type": "elgg-plugin",
		"keywords": ["elgg", "plugin"],
		"license": "GPL-2.0-only",
		"support": {
			"source": "https://github.com/elgg/<name of the repository>",
			"issues": "https://github.com/elgg/<name of the repository>/issues"
		},
		"require": {
			"composer/installers": ">=1.0.8"
		},
		"conflict": {
			"elgg/elgg": "< <minimal Elgg required version>"
		}
	}

The ``conflict`` rule is there to help prevent the installation of this plugin in an unsupported Elgg version.

Add the repository to `Packagist`_, for the existing repositories this was already done. Make sure `Packagist`_ is updated correctly with 
all the commits.

Tag a release
-------------

In order for Composer to be able to cache the plugin for faster installation, a release has to be made on the repository. 
Probably the first version that needs to be tagged is the same version as mentioned in the ``elgg-plugin.php`` or ``composer.json``. 
After this development can begin, following the `Semver`_ versioning scheme.

Translations
------------

If the translations for the plugin need to be managed by `Transifex`_, add the plugin to `Transifex`_.

Elgg core cleanup
=================

Now that the plugin has been moved to it's own repository, it's time to make a Pull Request on Elgg core to remove the original code.

Remove the plugin
-----------------

* Delete the ``mod`` folder for the plugin
* Search for the plugin name in core to find any references which also need to be removed (eg. old docs, special tests, etc.)

Translations
------------

Since the plugin no longer is part of Elgg core, make sure the configuration of `Transifex`_ no longer contains the plugin.

Bundled
-------

If the plugin still comes bundled with the release of a new Elgg version, make sure to add the plugin to the ``composer.json``.

Composer
--------

Check the core composer dependencies if requirements that were specific for the removed plugin can also be removed in the core dependencies.

Documentation
-------------

Add a mention in the :doc:`/appendix/upgrade-notes` documentation that the plugin was removed from Elgg core.

.. _Splitting a subfolder out into a new repository: https://help.github.com/articles/splitting-a-subfolder-out-into-a-new-repository/
.. _GitHub issue: https://github.com/Elgg/Elgg/issues/9419#issuecomment-237864270
.. _Elgg organisation: https://github.com/Elgg
.. _Packagist: https://packagist.org/
.. _Semver: http://semver.org/
.. _Transifex: https://www.transifex.com/elgg/
