Themes
######

Customize the look and feel of Elgg.

A theme is a type of :doc:`plugin </admin/plugins>` that overrides display aspects of Elgg.

This guide assumes you are familiar with:
 * :doc:`/admin/plugins`
 * :doc:`views`

.. contents:: Contents
   :local:
   :depth: 2

Create your plugin
==================

Create your plugin as described in the :doc:`developer guide </guides/index>`.

-  Create a new directory under mod/
-  Create a new start.php
-  Create a manifest.xml file describing your theme.

Customize the CSS
=================

As of Elgg 1.8, the css is split into several files based on what
aspects of the site you're theming. This allows you to tackle them one
at a time, giving you a chance to make real progress without getting
overwhelmed.

Here is a list of the existing CSS views:

 * elements/buttons.css: Provides a way to style all the different kinds of buttons your site will use. There are 5 kinds of buttons that plugins will expect to be available: action, cancel, delete, submit, and special.
 * elements/chrome.css: This file has some miscellaneous look-and-feel classes.
 * elements/components.css: This file contains many “css objects” that are used all over the site: media block, list, gallery, table, owner block, system messages, river, tags, photo, and comments.
 * elements/forms.css: This file determines what your forms and input elements will look like.
 * elements/icons.css: Contains styles for the icons and avatars used on your site.
 * elements/layout.css: Determines what your page layout will look like: sidebars, page wrapper, main body, header, footer, etc.
 * elements/modules.css: Lots of content in Elgg is displayed in boxes with a title and a content body. We called these modules. There are a few kinds: info, aside, featured, dropdown, popup, widget. Widget styles are included in this file too, since they are a subset of modules.
 * elements/navigation.css: This file determines what all your menus will look like.
 * elements/typography.css: This file determines what the content and headings of your site will look like.
 * rtl.css: Custom rules for users viewing your site in a right-to-left language.
 * admin.css: A completely separate theme for the admin area (usually not overridden).
 * elgg.css: Compiles all the core elements/\* files into one file (DO NOT OVERRIDE).
 * elements/core.css: Contains base styles for the more complicated “css objects”. If you find yourself wanting to override this, you probably need to report a bug to Elgg core instead (DO NOT OVERRIDE).
 * elements/reset.css: Contains a reset stylesheet that forces elements to have the same default


View extension
--------------

There are two ways you can modify views:

The first way is to add extra stuff to an existing view via the extend
view function from within your start.php’s initialization function.

For example, the following start.php will add mytheme/css to Elgg's core
css file:

.. code:: php

    <?php

        function mytheme_init() {
            elgg_extend_view('elgg.css', 'mytheme/css');
        }

        elgg_register_event_handler('init', 'system', 'mytheme_init');
    ?>

View overloading
----------------

Plugins can have a view hierarchy, any file that exists here will
replace any files in the existing core view hierarchy... so for example,
if my plugin has a file:

``/mod/myplugin/views/default/elements/typography.css``

it will replace:

``/views/default/elements/typography.css``

But only when the plugin is active.

This gives you total control over the way Elgg looks and behaves. It
gives you the option to either slightly modify or totally replace
existing views.

Icons
-----

As of Elgg 2.0 the default Elgg icons come from the FontAwesome_ library. 
You can use any of these icons by calling:  

``elgg_view_icon('icon-name');``

``icon-name`` can be any of the `FontAwesome icons`_ without the ``fa-``-prefix.

.. _FontAwesome: http://fontawesome.io/
.. _FontAwesome icons: http://fontawesome.io/icons/

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


