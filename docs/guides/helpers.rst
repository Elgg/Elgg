Helper functions
================

.. contents:: Contents
   :local:
   :depth: 1

Input and output
----------------

- ``get_input($name)`` Grabs information from a form field (or any variable passed using GET or POST). Also sanitises input, stripping Javascript etc.
- ``set_input($name, $value)`` Forces a value to a particular variable for subsequent retrieval by ``get_input()``

Entity methods
--------------

- ``$entity->getURL()`` Returns the URL of any entity in the system
- ``$entity->getGUID()`` Returns the GUID of any entity in the system
- ``$entity->canEdit()`` Returns whether or not the current user can edit the entity
- ``$entity->getOwnerEntity()`` Returns the ElggUser owner of a particular entity

Entity and context retrieval
----------------------------

- ``elgg_get_logged_in_user_entity()`` Returns the ElggUser for the current user
- ``elgg_get_logged_in_user_guid()`` Returns the GUID of the current user
- ``elgg_is_logged_in()`` Is the viewer logged in
- ``elgg_is_admin_logged_in()`` Is the view an admin and logged in
- ``elgg_gatekeeper()`` Shorthand for checking if a user is logged in. Forwards user to front page if not
- ``elgg_admin_gatekeeper()`` Shorthand for checking the user is logged in and is an admin. Forwards user to front page if not
- ``get_user($user_guid)`` Given a GUID, returns a full ElggUser entity
- ``elgg_get_page_owner_guid()`` Returns the GUID of the current page owner, if there is one
- ``elgg_get_page_owner_entity()`` Like elgg_get_page_owner_guid() but returns the full entity
- ``elgg_get_context()`` Returns the current page's context - eg "blog" for the blog plugin, "thewire" for the wire, etc. Returns "main" as default
- ``elgg_set_context($context)`` Forces the context to be a particular value
- ``elgg_push_context($context)`` Adds a context to the stack
- ``elgg_pop_context()`` Removes the top context from the stack
- ``elgg_in_context($context)`` Checks if you're in a context (this checks the complete stack, eg. 'widget' in 'groups')

Plugins
-------

- ``elgg_is_active_plugin($plugin_id)`` Check if a plugin is installed and enabled

Interface and annotations
-------------------------

- ``elgg_view_image_block($icon, $info)`` Return the result in a formatted list
- ``elgg_view_comments($entity)`` Returns any comments associated with the given entity
- ``elgg_get_friendly_time($unix_timestamp)`` Returns a date formatted in a friendlier way - "18 minutes ago", "2 days ago", etc.

Messages
--------

- ``system_message($message)`` Registers a success message
- ``register_error($message)`` Registers an error message
- ``elgg_view_message($type, $message)`` Outputs a message
