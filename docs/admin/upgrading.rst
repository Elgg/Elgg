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

* **Back up your database, data directory and code**
* Download the new version of Elgg from http://elgg.org
* Overwrite your existing files with the new version of Elgg
* Merge any new changes from ``htaccess_dist`` into ``.htaccess``
* Merge any new changes from ``settings.example.php`` into ``settings.php``
* Visit http://your-elgg-site.com/upgrade.php

.. note::

   Any modifications should have been written within plugins, so that they are not lost on overwriting.
   If this is not the case, take care to maintain your modifications. 

.. note::

   If you modified the default .htaccess, be sure to port your modifications over to the new one.

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

You may also consider getting rid of obsolete files from formerly installed Elgg versions
by deleting everyting from the Elgg installation directory except for:

 * ``.htaccess``
 * ``engine/settings.php``
 * any 3rd-party plugin folders in the ``mod`` directory

Follow the basic instructions listed above.

After you've visited ``upgrade.php``, go to the admin area of your site.
You should see a notification that you have pending upgrades.
Click the link in the notification bar to view and run the upgrades.


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
