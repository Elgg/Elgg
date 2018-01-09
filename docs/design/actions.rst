Actions
#######

Actions are the primary way users interact with an Elgg site.

.. toctree::
   :maxdepth: 2

Overview
========

An action in Elgg is the code that runs to make changes to the database when a user does something. For example, logging in, posting a comment, and making a blog post are actions. The action script processes input, makes the appropriate modifications to the database, and provides feedback to the user about the action.

Action Handler
==============

Actions are registered during the boot process by calling ``elgg_register_action()``. All actions URLs start with ``action/`` and are served by Elgg's front end controller through the action service. This approach is different from traditional PHP applications that send information to a specific file. The action service performs :doc:`CSRF security checks </design/security>`, and calls the registered action script file, then optionally forwards the user to a new page. By using the action service instead of a single script file, Elgg automatically provides increased security and extensibility.

See :doc:`/guides/actions` for details on how to register and construct an action. To look at the core actions, check out the directory /actions.
