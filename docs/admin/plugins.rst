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

Themes are just plugins that modify the look-and-feel of your site, so you'll
typically find them wherever Elgg plugins are available.

Language Packs
--------------

Language packs are just plugins that provide support for another language.
There are language packs for the core and they are usually installed in the languages directory off the elgg root directory.
Other language packs are provided for various plugins. 
Generally, the authors make it easy to copy those files into the languages directory of each plugin under the mod directory.

Installation
============

To install a plugin, unzip the archive and copy the plugin's main folder
to the “mod” directory in your Elgg installation.

You must then activate it from the admin panel:

-  Log in to your Elgg site with your administrator account
-  Go to Administration -> Configure -> Plugins
-  Find your plugin in the list of installed plugins and click on the
   'enable' button.

Pre-1.8 notes
=============

In Elgg 1.7 and below, the interface for managing installed plugins is located at
Administration -> Tool Administration.
