Security
########

As of Elgg 3.0 several hardening settings have been added to Elgg. You can enable/disable these settings as you like.

.. contents:: Contents
   :depth: 2
   :local:

Upgrade protection
==================

The URL of http://your-elgg-site.com/upgrade.php can be protected by a unique token. This will prevent random users from being able to run this file. 
The token is not needed for logged in site administrators.

Cron protection
===============

The URLs of the :doc:`cron </admin/cron>` can be protected by a unique token. This will prevent random users from being able to run the cron.
The token is not needed when running the cron from the commandline of the server.

Disable password autocomplete
=============================

Data entered in these fields will be cached by the browser. An attacker who can access the victim's browser could steal this information. 
This is especially important if the application is commonly used in shared computers such as cyber cafes or airport terminals. 
If you disable this, password management tools can no longer autofill these fields. The support for the autocomplete attribute can be browser specific.

Email address change requires password
======================================

When a user wishes to change their email address associated with their account, they need to also supply their current password.

Session bound icons
===================

Entity icons can be session bound by default. This means the URLs generated also contain information about the current session. 
Having icons session bound makes icon urls not shareable between sessions. The side effect is that caching of these urls will only help the active session. 

Notification to site administrators
===================================

When a new site administrator is added or when a site administrator is removed all the site administrators get a notification about this action.

Notifications to user
=====================

Site administrator
------------------

When the site administrator role is added to or removed from the account, send a notification to the user whos account this is affecting.

(Un)ban
-------

When the account of a user gets banned or unbanned, let the affected user know about this action.
