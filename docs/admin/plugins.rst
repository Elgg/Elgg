Plugins
#######

Plugins can modify the behavior of and add new features to Elgg.

Where to get plugins
====================

Plugins can be obtained from:

 * `The Elgg Community <http://community.elgg.org/plugins>`_
 * `Github <https://github.com/Elgg>`_
 * Third-party sites (typically for a price)

If no existing plugins meet your needs, you can `hire a developer`__ or :doc:`create your own </guides/index>`.

__ http://community.elgg.org/groups/profile/75603/professional-services

Types of plugins
================

Themes
------

Themes are plugins that modify the look-and-feel of your site. They generally
include stylesheets, client-side scripts and views that alter the default
presentation and behavior of Elgg.

Language Packs
--------------

Language packs are plugins that provide support for other languages.

Language packs can extend and include translations for language strings 
found in the core, core plugins and/or third-party plugins.

Some of the language packs are already included in the core, and can be found in
``languages`` directory off Elgg's root directory. Individual plugins tend to
include their translations under the ``languages`` directory within the plugin's
root.

This structure makes it easy to create new language packs that supercede existing
language strings or add support for new languages.

Installation
============

All plugins reside in the ``mod`` directory of your Elgg installation.

To install a new plugin:
 * extract (unzip) contents of the plugin distribution package
 * copy/FTP the extracted folder into the ``mod`` directory of your Elgg
   installation, making sure that ``manifest.xml`` and ``start.php`` are
   directly under the plugin directory (e.g. if you were to install a plugin called
   ``my_elgg_plugin``, plugin's manifest would need to be found at
   ``mod/my_elgg_plugin/manifest.xml``)
 * activate the plugin from your admin panel

To activate a plugin:
 * Log in to your Elgg site with your administrator account
 * Go to Administration -> Configure -> Plugins
 * Find your plugin in the list of installed plugins and click on the
   'enable' button.

Pre-1.8 notes
=============

In Elgg 1.7 and below, the interface for managing installed plugins is located at
Administration -> Tool Administration.
