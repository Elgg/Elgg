Simplecache
===========

.. seealso::

   - :doc:`/admin/performance`
   - :doc:`/guides/views`
   
The Simplecache is a mechanism designed to alleviate the need for certain views to be regenerated dynamically.
Instead, they are generated once, saved as a static file, and served in a way that entirely bypasses the Elgg engine.

If Simplecache is turned off (which can be done from the administration panel),
these views will be served as normal, with the exception of site CSS.

The criteria for whether a view is suitable for the Simplecache is as follows:

- The view must not change depending on who or when it is being looked at
- The view must not depend on variables fed to it (except for global variables like site URL that never change)

Regenerating the Simplecache
----------------------------

You can regenerate the Simplecache at any time by:

- Loading ``/upgrade.php``, even if you have nothing to upgrade
- In the admin panel click on 'Flush the caches'
- Enabling or disabling a plugin
- Reordering your plugins

Using the Simplecache in your plugins
-------------------------------------

**Registering views with the Simplecache**

You can register a view with the Simplecache with the following function at init-time:

.. code-block:: php

   elgg_register_simplecache_view($viewname);

**Accessing the cached view**

If you registered a JavaScript or CSS file with Simplecache and put in the view folder as
``your_view.js`` or ``your_view.css`` you can very easily get the url to this cached view by calling
``elgg_get_simplecache_url($view)``. For example:

.. code-block:: php

   $js = elgg_get_simplecache_url('your_view.js');
   $css = elgg_get_simplecache_url('your_view.css');
