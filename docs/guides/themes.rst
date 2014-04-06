Themes
######

The look and feel of Elgg can be easily changed through the use of themes. A theme is a type of :doc:`plugin <plugins>` that overrides display aspects of Elgg.

Installation
============

Free themes can be obtained from `the Elgg community`_.

.. _the Elgg community: http://community.elgg.org/mod/community_plugins/search.php?category=themes

A theme is installed just as all plugins are installed. Simply copy the
theme's folder to the “mod” directory in your Elgg install and then
activate it from the Elgg admin panel.

To activate the theme:

-  Log in to your Elgg site with your administrator account
-  Go to Administration -> Tool Administration
-  Find your theme in the list of installed plugins and click on the
   'enable' button.

Often you will want the theme to be the last plugin to run. The order of
the plugins in the Tool Administration page determines their loading
order; to make the theme run last, click the *bottom* link.

Building your own theme
=======================

Themes for Elgg are both extremely easy to develop and incredibly
powerful. Using themes you can completely change how an Elgg install
looks and feels (and even behaves).

By far the easiest and most flexible way to make a theme for Elgg is to
build it as a plugin. This makes it easy to distribute (since they are
self contained) and lets you turn the theme on and off from the admin
panel (making the theming process far less invasive!).

Before you jump right in, it is advisable to familiarise yourself with
:doc:`plugins` and :doc:`the views system <views>`.

Create your plugin
------------------

Step one is to create your plugin as described in the :doc:`plugin development guide <plugins>`.

-  Create a new directory under mod/
-  Create a new start.php
-  Create a manifest file describing your theme.

Customize the CSS
-----------------

As of Elgg 1.8, the css is split into several files based on what
aspects of the site you're theming. This allows you to tackle them one
at a time, giving you a chance to make real progress without getting
overwhelmed.

Here is a list of the existing CSS views:

 * css/elements/buttons: Provides a way to style all the different kinds of buttons your site will use. There are 5 kinds of buttons that plugins will expect to be available: action, cancel, delete, submit, and special.
 * css/elements/chrome: This file has some miscellaneous look-and-feel classes.
 * css/elements/components: This file contains many “css objects” that are used all over the site: media block, list, gallery, table, owner block, system messages, river, tags, photo, and comments.
 * css/elements/forms: This file determines what your forms and input elements will look like.
 * css/elements/icons: Contains styles for the sprite icons and avatars used on your site.
 * css/elements/layout: Determines what your page layout will look like: sidebars, page wrapper, main body, header, footer, etc.
 * css/elements/modules: Lots of content in Elgg is displayed in boxes with a title and a content body. We called these modules. There are a few kinds: info, aside, featured, dropdown, popup, widget. Widget styles are included in this file too, since they are a subset of modules.
 * css/elements/navigation: This file determines what all your menus will look like.
 * css/elements/typography: This file determines what the content and headings of your site will look like.
 * css/ie6: Custom rules for ie6 and below.
 * css/ie7: Custom rules for ie7 and below.
 * css/rtl: Custom rules for users viewing your site in a right-to-left language.
 * css/admin: A completely separate theme for the admin area (usually not overridden).
 * css/elgg: Compiles all the core css/elements/\* files into one file (DO NOT OVERRIDE).
 * css/elements/core: Contains base styles for the more complicated “css objects”. If you find yourself wanting to override this, you probably need to report a bug to Elgg core instead (DO NOT OVERRIDE).
 * css/elements/reset: Contains a reset stylesheet that forces elements to have the same default


View extension
~~~~~~~~~~~~~~

There are two ways you can modify views:

The first way is to add extra stuff to an existing view via the extend
view function from within your start.php’s initialization function.

For example, the following start.php will add mytheme/css to Elgg's core
css file:

.. code:: php

    <?php

        function mytheme_init() {
            elgg_extend_view('css/elgg', 'mytheme/css');
        }

        elgg_register_event_handler('init', 'system', 'mytheme_init');
    ?>

View overloading
~~~~~~~~~~~~~~~~

Plugins can have a view hierarchy, any file that exists here will
replace any files in the existing core view hierarchy... so for example,
if my plugin has a file:

``/mod/myplugin/views/default/css/elements/typography.php``

it will replace:

``/views/default/css/elements/typography.php``

But only when the plugin is active.

This gives you total control over the way elgg looks and behaves. It
gives you the option to either slightly modify or totally replace
existing views.

Tools
=====

Starting in Elgg 1.8, we've provided you with some development tools to help you
with theming: Turn on the “Developers” plugin and go to the “Theme
Preview” page to start tracking your theme's progress.

Customizing the front page
==========================
The main Elgg index page runs a plugin hook called 'index,system'. If this
returns true, it assumes that another front page has been drawn and
doesn't display the default page.

Therefore, you can override it by registering a function to the
'index,system' plugin hook and then returning true from that function.

Here's a quick overview:

-  Create your new plugin

-  In the start.php you will need something like the following:

.. code:: php

    <?php

    function pluginname_init() {
        // Replace the default index page
        elgg_register_plugin_hook_handler('index', 'system', 'new_index');
    }

    function new_index() {
        if (!include_once(dirname(dirname(__FILE__)) . "/pluginname/pages/index.php"))
            return false;
        
        return true;
    }

    // register for the init, system event when our plugin start.php is loaded
    elgg_register_event_handler('init', 'system', 'pluginname_init');
    ?>

-  Then, create an index page (/pluginname/pages/index.php) and use that
   to put the content you would like on the front page of your Elgg
   site.


