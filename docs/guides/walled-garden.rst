Walled Garden
=============

Elgg supports a "Walled Garden" mode. In this mode, almost all pages are restricted to logged in users. This is useful for sites that don't allow public registration.

Activating Walled Garden mode
-----------------------------

To activate Walled Garden mode in Elgg, go to the Administration section. On the right sidebar menu, under the "Configure" section, expand "Settings," then click on "Advanced."

From the Advanced Settings page, find the option labelled "Restrict pages to logged-in users." Enable this option, then click "Save" to switch your site into Walled Garden mode.

.. _guides/walled-garden#expose:

Exposing pages through Walled Gardens
-------------------------------------

Many plugins extend Elgg by adding pages. Walled Garden mode will prevent these pages from being viewed by logged out users.
Elgg uses :ref:`plugin hook <design/events#plugin-hooks>` to manage which pages are visible through the Walled Garden.

Plugin authors must register pages as public if they should be viewable through Walled Gardens:

 * by setting ``'walled' => false`` in route configuration
 * by responding to the ``public_pages``, ``walled_garden`` plugin hook. The returned value is an array of regexp expressions for public pages.

The following code shows how to expose http://example.org/my_plugin/public_page through a Walled Garden.
This assumes the plugin has registered a :doc:`route </guides/routing>` for ``my_plugin/public_page``.

.. code-block:: php

   // Preferred way
   elgg_register_route('my_plugin:public_page', [
       'path' => '/my_plugin/public_page',
       'resource' => 'my_plugin/public_page',
       'walled' => false,
   ]);

   // Legacy approach
   elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'my_plugin_walled_garden_public_pages');
   
   function my_plugin_walled_garden_public_pages(\Elgg\Hook $hook) {
      $pages = $hook->getValue();
      
      $pages[] = 'my_plugin/public_page';
      
      return $pages;
   }
