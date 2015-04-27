Upgrading Elgg
##############

Switch a live site to a new version of Elgg.

If you've written custom plugins, you should also read the developer guides for
:doc:`information on upgrading plugin code </guides/upgrading>` for the latest version of Elgg.

Advice
======

* **Back up your database** and code
* Mind any version-specific comments below
* Upgrade only one minor version at a time (1.6 => 1.7, then 1.7 => 1.8)
* Try out the new version on a test site before doing an upgrade
* Report any problems in plugins to the plugin authors
* If you are a plugin author you can `report any backwards-compatibility issues to GitHub <issues_>`_

.. _issues: https://github.com/Elgg/Elgg/issues

Basic instructions
==================

#. **Back up your database, data directory, and code**
#. Download the new version of Elgg from http://elgg.org
#. Update the files
    * If doing a patch upgrade (1.9.x), overwrite your existing files with the new version of Elgg
    * If doing a minor upgrade (1.x), replace the existing core files completely
#. Merge any new changes to the rewrite rules
    * For Apache from ``install/config/htaccess.dist`` into ``.htaccess``
    * For Nginx from ``install/config/nginx.dist`` into your server configuration (usually inside ``/etc/nginx/sites-enabled``)
#. Merge any new changes from ``settings.example.php`` into ``settings.php``
#. Visit http://your-elgg-site.com/upgrade.php

.. note::

   Any modifications should have been written within plugins, so that they are not lost on overwriting.
   If this is not the case, take care to maintain your modifications. 

From 1.10 to 1.11
========================

Breaking changes
----------------
In versions 1.9 and 1.10, names and values for metadata and annotations were not correctly trimmed
for whitespace. Elgg 1.11 correctly trims these strings and updates the database to correct
existing strings. If your plugin uses metadata or annotations with leading or trailing whitespace,
you will need to update the plugin to trim the names and values. This is especially important if
you are using custom SQL clauses or have hard-coded metastring IDs, since the update might change
metastring IDs.

From 1.8 to 1.9
===============
Elgg 1.9 is a much lighter upgrade than 1.8 was.

Breaking changes
----------------
Plugins and themes written for 1.8 are expected to be compatible with 1.9
except as it pertains to comments, discussion replies, and notifications.
Please `report any backwards compatibility issues <issues_>`_ besides those just listed.

Upgrade steps
-------------
There are several data migrations involved, so it is especially important that you
**back up your database and data directory** before performing the upgrade.

Download the new version and copy these files from the existing 1.8 site:

 * ``.htaccess``
 * ``engine/settings.php``
 * any 3rd-party plugin folders in the ``mod`` directory

Then replace the old installation directory with the new one. This way you are
guaranteed to get rid of obsolete files which might cause problems if left behind.

Follow the basic instructions listed above.

After you've visited ``upgrade.php``, go to the admin area of your site.
You should see a notification that you have pending upgrades.
Click the link in the notification bar to view and run the upgrades.

The new notifications system delivers messages via a minutely cron handler.
If you haven't done so yet, you will need to :doc:`install and configure crontab </admin/cron>`
on your server. If cron jobs are already configured, note that the scope of
available cron periods may have changed and you may need to update your current crontab
to reflect these changes.

Time commitment
---------------
Running all of the listed upgrades `took about 1 hour and 15 minutes`__
on the Elgg community site which at the time had to migrate:

 * ~75,000 discussion replies
 * ~75,000 comments
 * ~75,000 data directories
 
__ https://community.elgg.org/discussion/view/1819798/community-site-upgraded

You should take this only as a ballpark estimate for your own upgrade.
How long it takes will depend on how large your site is and how powerful your servers are.

From 1.7 to 1.8
===============
Elgg 1.8 is the biggest leap forward in the development of Elgg since version 1.0.
As such, there is more work to update core and plugins than with previous upgrades.

Updating core
-------------
Delete the following core directories (same level as _graphics and engine):

* _css
* account
* admin
* dashboard
* entities
* friends
* search
* settings
* simplecache
* views

.. warning::

   If you do not delete these directories before an upgrade, you will have problems!
