Walled Garden
=============

Elgg supports a "Walled Garden" mode. In this mode, almost all pages are restricted to logged in users. This is useful for sites that don't allow public registration.

Activating Walled Garden mode
-----------------------------

To activate Walled Garden mode in Elgg 1.8, go to the Administration section. On the right sidebar menu, under the "Configure" section, expand "Settings," then click on "Advanced."

From the Advanced Settings page, find the option labelled "Restrict pages to logged-in users." Enable this option, then click "Save" to switch your site into Walled Garden mode.

Exposing pages through Walled Gardens
-------------------------------------

Many plugins extend Elgg by adding pages. Walled Garden mode will prevent these pages from being viewed by logged out users. Elgg uses :ref:`plugin hook <design/events#plugin-hooks>` to manage which pages are visible through the Walled Garden.

Plugin authors must register pages as public if they should be viewable through Walled Gardens by responding to the ``public_pages``, ``walled_garden`` plugin hook.

The returned value is an array of regexp expressions for public pages.

The following code shows how to expose http://example.org/my_plugin/public_page through a Walled Garden. This assumes the plugin has registered a :doc:`pagehandler` for ``my_plugin``.

.. code:: php

   elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'my_plugin_walled_garden_public_pages');
   
   function my_plugin_walled_garden_public_pages($hook, $type, $pages) {
      $pages[] = 'my_plugin/public_page';
      return $pages;
   }