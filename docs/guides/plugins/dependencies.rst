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

The dependency system uses the four verbs above (``requires`` and ``conflicts``) as parent elements to indicate what type of dependency is described by its children. All dependencies have a similar format with similar options:

.. code-block:: xml

   <verb>
      <type>type</type>
      <noun>value</noun>
      <noun2>value2</noun2>
   </verb>
   
.. note::

   ``type`` is always required

Verbs
=====

With the exception of ``provides``, all verbs use the same six types with differing effects, and the type options are the same among the verbs. ``provides`` only supports ``plugin`` and ``php_extension``.

Requires
--------

Using a ``requires`` dependency means that the plugin cannot be enabled unless the dependency is exactly met.

Mandatory requires: elgg_release
-------------------------------------------------

Every plugin must have at least one requires: the version of Elgg the plugin is developed for. This is specified by the Elgg API ``release`` (1.8). The default comparison ``>=``, but you can specify your own by passing the ``<comparison>`` element.

Using elgg_release:

.. code-block:: xml

   <requires>
      <type>elgg_release</type>
      <version>1.8</version>
   </requires>

Conflicts
---------

``conflicts`` dependencies mean the plugin cannot be used under a specific system configuration.

Conflict with any version of the profile plugin:

.. code-block:: xml
   
   <conflicts>
      <type>plugin</type>
      <name>profile</name>
   </conflicts>

Conflict with a specific release of Elgg:

.. code-block:: xml

   <conflicts>
      <type>elgg_release</type>
      <version>1.8</version>
      <comparison>==</comparison>
   </conflicts>

Types
=====

Every dependency verb has a mandatory ``<type>`` element that must be one of the following six values:

1. **elgg_release** - The release version of Elgg (1.8)
2. **plugin** - An Elgg plugin
3. **priority** - A plugin load priority
4. **php_extension** - A PHP extension
5. **php_version** - A PHP version

.. note::

   ``provides`` only supports ``plugin`` and ``php_extension`` types.

Every type is defined with a dependency verb as the parent element. Additional option elements are at the same level as the type element:

.. code-block:: xml

   <verb>
      <type>type</type>
      <option_1>value_1</option_1>
      <option_2>value_2</option_2>
   </verb>

elgg_release
------------

These concern the API and release versions of Elgg and requires the following option element:

- **version** - The API or release version

The following option element is supported, but not required:

- **comparison** - The comparison operator to use. Defaults to >= if not passed

plugin
------

Specifies an Elgg plugin by its ID (directory name). This requires the following option element:

- **name** - The ID of the plugin

The following option elements are supported, but not required:

- **version** - The version of the plugin
- **comparison** - The comparison operator to use. Defaults to >= if not passed

priority
--------

This requires the plugin to be loaded before or after another plugin, if that plugin exists. ``requires`` should be used to require that a plugin exists. The following option elements are required:

- **plugin** - The plugin ID to base the load order on
- **priority** - The load order: 'before' or 'after'

php_extension
-------------

This checks PHP extensions. The follow option element is required:

- **name** - The name of the PHP extension

The following option elements are supported, but not required:

- **version** - The version of the extension
- **comparison** - The comparison operator to use. Defaults to ==

.. note::

   The format of extension versions varies greatly among PHP extensions and is sometimes not even set. This is generally worthless to check.

php_version
-----------

This checks the PHP version. The following option elements are required:

- **version** - The PHP version

The following option element is supported, but not required:

- **comparison** - The comparison operator to use. Defaults to >= if not passed

Comparison Operators
====================

Dependencies that check versions support passing a custom operator via the ``<comparison>`` element.

The follow are valid comparison operators:

- < or lt
- <= or le
- =, ==, or eq
- !=, <>, or ne
- > or gt
- >= or ge

If ``<comparison>`` is not passed, the follow are used as defaults, depending upon the dependency type:

- requires->elgg_release: >=
- requires->plugin: >=
- requires->php_extension: =
- all conflicts: =

.. note::

   You must escape < and > to ``&gt;`` and ``&lt;``. For comparisons that use these values, it is recommended you use the string equivalents instead!

Quick Examples
==============

Requires Elgg 1.8.2 or higher
-----------------------------

.. code-block:: xml

   <requires>
      <type>elgg_release</type>
      <version>1.8.2</version>
   </requires>

Requires the Groups plugin is active
------------------------------------

.. code-block:: xml

   <requires>
      <type>plugin</type>
      <name>groups</name>
   </requires>

Requires to be after the Profile plugin if Profile is active
------------------------------------------------------------

.. code-block:: xml

   <requires>
      <type>priority</type>
      <priority>after</priority>
      <plugin>profile</plugin>
   </requires>

Conflicts with The Wire plugin
------------------------------

.. code-block:: xml

   <conflicts>
      <type>plugin</type>
      <name>thewire</name>
   </conflicts>


Requires at least PHP version 5.3
---------------------------------

.. code-block:: xml

   <requires>
      <type>php_version</type>
      <version>5.3</version>
   </requires>

Suggest the TidyPics plugin is loaded
-------------------------------------

.. code-block:: xml

   <suggests>
      <type>plugin</type>
      <name>tidypics</name>
   </suggests>
