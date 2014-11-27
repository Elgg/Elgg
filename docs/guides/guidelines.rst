Plugin coding guidelines
========================

In addition to the Elgg Coding Standards, these are guidelines for creating plugins. Core plugins are being updated to this format and all plugin authors should follow these guidelines in their own plugins.

.. seealso::

   Be sure to follow the :doc:`plugins/plugin-skeleton` for your plugin's layout.

.. warning::

  :doc:`dont-modify-core`

.. contents:: Contents
   :local:
   :depth: 1

Use standardized routing with page handlers
-------------------------------------------

- Example: Bookmarks plugin
- Page handlers should accept the following standard URLs:
   +---------------+-----------------------------------+
   | Purpose       | URL                               |
   +===============+===================================+
   | All           | page_handler/all                  |
   +---------------+-----------------------------------+
   | User          | page_handler/owner/<username>     |
   +---------------+-----------------------------------+
   | User friends’ | page_handler/friends/<username>   |
   +---------------+-----------------------------------+
   | Single entity | page_handler/view/<guid>/<title>  |
   +---------------+-----------------------------------+
   | Add           | page_handler/add/<container_guid> |
   +---------------+-----------------------------------+
   | Edit          | page_handler/edit/<guid>          |
   +---------------+-----------------------------------+
   | Group list    | page_handler/group/<guid>/owner   |
   +---------------+-----------------------------------+
- Include page handler scripts from the page handler. Almost every page handler should have a page handler script. (Example: ``bookmarks/all`` => ``mod/bookmarks/pages/bookmarks/all.php``)
- Call ``set_input()`` for entity guids in the page handler and use ``get_input()`` in the page handler scripts.
- Call ``elgg_gatekeeper()`` and ``elgg_admin_gatekeeper()`` in the page handler function if required.
- The group URL should use the ``pages/<handler>/owner.php`` script.
- Page handlers should not contain HTML.
- If upgrading a 1.7 plugin, update the URLs throughout the plugin. (Don’t forget to remove ``/pg/``!)

Use standardized page handlers and scripts
------------------------------------------

- Example: Bookmarks plugin
- Store page handler scripts in ``mod/<plugin>/pages/<page_handler>/<page_name>``
- Use the content page layout in page handler scripts: ``$content = elgg_view_layout('content', $options);``
- Page handler scripts should not contain HTML
- Call ``elgg_push_breadcrumb()`` in the page handler scripts.
- No need to worry about setting the page owner if the URLs are in the standardized format
- For group content, check the ``container_guid`` by using ``elgg_get_page_owner_entity()``

The object/<subtype> view
-------------------------

- Example: Bookmarks plugin
- Make sure there are views for ``$vars[‘full’] == true`` and ``$vars[‘full’] == false``
- Check for the object in ``$vars[‘entity’]`` . Use ``elgg_instance_of()`` to make sure it’s the type entity you want. Return ``true`` to short circuit the view if the entity is missing or wrong.
- Use the new list body and list metadata views to help format. You should use almost no markup in these views.
- Update action structure - Example: Bookmarks plugin.
- Namespace action files and action names (example: ``mod/blog/actions/blog/save.php`` => ``action/blog/save``)
- Use the following action URLs:
   +---------+----------------------+
   | Purpose | URL                  |
   +=========+======================+
   | Add     | action/plugin/save   |
   +---------+----------------------+
   | Edit    | action/plugin/save   |
   +---------+----------------------+
   | Delete  | action/plugin/delete |
   +---------+----------------------+
- Make the delete action accept ``action/<handler>/delete?guid=<guid>`` so the metadata entity menu has the correct URL by default
- If updating a 1.7 plugin, replace calls to functions deprecated in 1.7 because these will produce visible errors on every load in 1.8

Actions
-------

Actions are transient states to perform an action such as updating the database or sending a notification to a user. Used correctly, actions are secure and prevent against CSRF and XSS attacks.

.. note::

   As of Elgg 1.7 all actions require action tokens.
   
Action best practices
^^^^^^^^^^^^^^^^^^^^^

Never call an action directly by saying:

.. code:: html

   ...href="/mod/mymod/actions/myaction.php"

This circumvents the security systems in Elgg.

There is no need to include the ``engine/start.php`` file in your actions. Actions should never be called directly, so the engine will be started automatically when called correctly.

Because actions are time-sensitive they are not suitable for links in emails or other delayed notifications. An example of this would be invitations to join a group. The clean way to create an invitation link is to create a page handler for invitations and email that link to the user. It is then the page handler's responsibility to create the action links for a user to join or ignore the invitation request.

Directly calling a file
-----------------------

This is an easy one: **Don't do it**. With the exception of 3rd party application integration, there is not a reason to directly call a file in mods directory.

Recommended
-----------

These points are good ideas, but are not yet in the official guidelines. Following these suggestions will help to keep your plugin consistent with Elgg core.

- Update the widget views (see the blog or file widgets)
- Update the group profile “widget” using blog or file plugins as example
- Update the forms
   - Move form bodies to ``/forms/<handler>/<action>`` to use Evan’s new ``elgg_view_form()``
   - Use input views in form bodies rather than html
   - Add a function that prepares the form (see ``mod/file/lib/file.php`` for example)
   - Integrate sticky forms (see the file plugin’s upload action and form prepare function)
- Clean up CSS/HTML
   - Should be able to remove almost all CSS (look for patterns that can be moved into core if you need CSS)
- Use hyphens rather than underscores in classes/ids
- Update the ``manifest.xml`` file to the 1.8 format. Use http://el.gg/manifest17to18 to automate this
- Do not use the ``bundled`` category with your plugins. That is for plugins distributed with Elgg
- Update functions deprecated in 1.8.
   - Many registration functions simply added an ``elgg_`` prefix for consistency
   - See ``/engine/lib/deprecated-1.8.php`` for the full list. You can also set the debug level to warning to get visual reminders of deprecated functions