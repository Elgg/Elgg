Page handler
============

Elgg offers a facility to manage your plugin pages via a page handler, enabling custom urls like ``http://yoursite/your_plugin/section``. To add a page handler to a plugin, a handler function needs to be registered in the plugin's ``start.php`` file with ``elgg_register_page_handler()``:

.. code:: php
   
   elgg_register_page_handler('your_plugin', 'your_plugin_page_handler');
   
The plugin's page handler is passed two parameters: 

- an array containing the sections of the URL exploded by '/'. With this information the handler will be able to apply any logic necessary, for example loading the appropriate view and returning its contents.
- the handler, this is the handler that is currently used (in our example ``your_plugin``). If you don't register multiple page handlers to the same function you'll never need this.

Code flow
---------

Pages in plugins should be rendered via page handlers (not by using ``Elgg\Application``). Generally the rendering is done by views with names starting with ``resources/``. The program flow is something like this:

1. A user requests ``/plugin_name/section/entity``
2. Elgg checks if ``plugin_name`` is registered to a page handler and calls that function, passing ``array('section', 'entity')`` as the first argument
3. The page handler function determines which resource view will display the page.
4. The handler uses ``elgg_view_resource()`` to render the page, also passing in any relevant info to the view via the ``$vars`` argument.
5. The resource view combines many separate views, calls formatting functions like ``elgg_view_layout()`` and ``elgg_view_page()``, and then echos the final output
6. The user sees a fully rendered page

There is no syntax enforced on the URLs, but Elgg's coding standards suggests a certain format.