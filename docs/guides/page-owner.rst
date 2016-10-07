Page ownership
==============

One recurring task of any plugin will be to determine the page ownership in order to decide which actions are allowed or not. Elgg has a number of functions related to page ownership and also offers plugin developers flexibility by letting the plugin handle page ownership requests as well.
Determining the owner of a page can be determined with ``elgg_get_page_owner_guid()``, which will return the GUID of the owner. Alternatively, ``elgg_get_page_owner_entity()`` will retrieve the whole page owner entity.
If the page already knows who the page owner is, but the system doesn't, the page can set the page owner by passing the GUID to ``elgg_set_page_owner_guid($guid)``.

.. note::

	The page owner entity can be any ``ElggEntity``. If you wish to only apply some setting in case of a user or a group make sure you check that you have the correct entity. 

Custom page owner handlers
--------------------------

Plugin developers can create page owner handlers, which could be necessary in certain cases, for example when integrating third party functionality. The handler will be a function which will need to get registered with ``elgg_register_plugin_hook_handler('page_owner', 'system', 'your_page_owner_function_name');`` . The handler will only need to return a value (an integer GUID) when it knows for certain who the page owner is.

By default, the system uses ``default_page_owner_handler()`` to determine the page_owner from the following elements:

- The ``username`` URL parameter
- The ``owner_guid`` URL parameter
- The URL path

It then passes off to any page owner handlers defined using the :ref:`plugin hook <design/events#plugin-hooks>`. If no page owner can be determined, the page owner is set to 0, which is the same as the logged out user.
