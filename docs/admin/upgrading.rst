Upgrading Elgg
##############

Switch a live site to a new version of Elgg.

If you've written custom plugins, you should also read the developer guides for
:doc:`information on upgrading plugin code </guides/upgrading>` for the latest version of Elgg.

Advice:

* **Back up your database** and code.
* Mind any version-specific comments below.
* Upgrade only one minor version at a time (1.6 => 1.7, then 1.7 => 1.8).
* Try out the new version on a test site before doing an upgrade
* Report any problems in plugins to the plugin authors.
* If you are a plugin author you can report any backwards-compatibility issues to `github <https://github.com/Elgg/Elgg/issues>`_.

Basic instructions:

* **Back up your database** and code.
* Download the new version of Elgg from elgg.org.
* Overwrite your existing Elgg files.
* Visit http://your-elgg-site-URL/upgrade.php
* Copy htaccess_dist over .htaccess.


.. note::

   Any modifications should have been written within plugins, so that they are not lost on overwriting.
   If this is not the case, take care to maintain your modifications. 

.. note::

   If you modified the default .htaccess, be sure to port your modifications over to the new one.

From 1.8 to 1.9
===============
TODO


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


From 1.6 to 1.7
===============



 a User authentication and administration
