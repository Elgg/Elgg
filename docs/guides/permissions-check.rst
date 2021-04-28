Permissions Check
=================

.. warning::

   As stated in the page, this method works **only** for granting **write** access to entities. You **cannot** use this method to retrieve or view entities for which the user does not have read access.

Elgg provides a mechanism of overriding write permissions check through the :ref:`permissions_check plugin hook <guides/hooks-list#permission-hooks>` . This is useful for allowing plugin write to all accessible entities regardless of access settings. Entities that are hidden, however, will still be unavailable to the plugin.

Hooking permissions_check
-------------------------

In your plugin, you must register the plugin hook for ``permissions_check``.

.. code-block:: php

   elgg_register_plugin_hook_handler('permissions_check', 'all', 'myplugin_permissions_check');

The override function
---------------------

Now create the function that will be called by the permissions check hook. In this function we determine if the entity (in parameters) has write access. Since it is important to keep Elgg secure, write access should be given only after checking a variety of situations including page context, logged in user, etc.
Note that this function can return 3 values: true if the entity has write access, false if the entity does not, and null if this plugin doesn't care and the security system should consult other plugins.

.. code-block:: php

   function myplugin_permissions_check(\Elgg\Hook $hook) {
      $has_access = determine_access_somehow();

      if ($has_access === true) {
         return true;
      } else if ($has_access === false) {
         return false;
      }

      return null;
   }

Full Example
------------

This is a full example using the context to determine if the entity has write access.

.. code-block:: php

   <?php

   function myaccess_init() {
      // override permissions for the myaccess context
      elgg_register_plugin_hook_handler('permissions_check', 'all', 'myaccess_permissions_check');

      // Register cron hook
      elgg_register_plugin_hook_handler('cron', elgg_get_plugin_setting('period', 'myaccess', 'fiveminute'), 'myaccess_cron');
   }

   /**
    * Hook for cron event. 
    */
   function myaccess_cron(\Elgg\Hook $hook) {

      elgg_push_context('myaccess_cron');

      // returns all entities regardless of access permissions.
      // will NOT return hidden entities.
      $entities = get_entities();

      elgg_pop_context();
   }

   /**
    * Overrides default permissions for the myaccess context
    */
   function myaccess_permissions_check(\Elgg\Hook $hook) {	
      if (elgg_in_context('myaccess_cron')) {
         return true;
      }

      return null;
   }

   // Initialise plugin
   register_elgg_event_handler('init', 'system', 'myaccess_init');
