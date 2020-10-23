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

Email address change requires confirmation
==========================================

When a user wishes to change their email address associated with their account, they need to confirm the new email address. This is 
done by sending an email to the new address with a validation link. After clicking this link the new email address will be used.

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

Minimal username length
=======================

You can configure the minimal length the username should have upon registration of a user.

Minimal password requirements
=============================

You can configure several requirements for new passwords of the users

- **length**: the password should be at least x characters long
- **lower case**: minimal number of lower case (a-z) characters in the password
- **upper case**: minimal number of upper case (A-Z) characters in the password
- **numbers**: minimal number of numbers (0-9) characters in the password
- **specials**: minimal number of special (like !@#$%^&*(), etc.) characters in the password

.htaccess file access hardening
===============================

In the .htaccess file a set of file access hardening rules have been added to prevent direct access to files in certain folders.
Enabling these rules `shouldn't` cause any issues when all the plugins you use follow the Elgg coding guidelines.

Examples of the rules are:

- the ``vendor`` folder. This folder only contains helper libraries that Elgg uses and there is no need for direct access to this folder. All required dependecies are loaded from within Elgg
- the ``languages`` folder. This folder contains the main Elgg language files. These files are loaded from within Elgg 
