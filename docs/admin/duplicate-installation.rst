Duplicate Installation
######################

.. contents:: Contents
   :local:
   :depth: 2

Introduction
============

Why Duplicate an Elgg Installation?
-----------------------------------

There are many reasons you may want to duplicate an Elgg installation: moving the site to another server, creating a test or development server, and creating functional backups are the most common. To create a successful duplicate of an Elgg site, 3 things need to be copied:

- Database
- Data from the data directory
- Code

Also at least 5 pieces of information must be changed from the copied installation:

- ``elgg-config/.env.php`` file.
- ``.htaccess`` file (Apache) or Nginx configuration depending on server used
- database entry for your site entity
- database entry for the installation path
- database entry for the data path

What Is Not Covered in This Tutorial
------------------------------------

This tutorial expects a basic knowledge of Apache, MySQL, and Linux commands. As such, a few things will not be covered in this tutorial. These include:

- How to backup and restore MySQL databases
- How to configure Apache to work with Elgg
- How to transfer files to and from your production server

Before You Start
----------------

Before you start, make sure the Elgg installation you want to duplicate is fully functional. You will also need the following items:

- A backup of the live Elgg database
- A place to copy the live database
- A server suitable for installing duplicate Elgg site  
   (This can be the same server as your production Elgg installation.)

Backups of the database can be obtained various ways, including phpMyAdmin, the MySQL official GUI, and the command line. Talk to your host for information on how to backup and restore databases or use Google to find information on this.

During this tutorial, we will make these assumptions about the production Elgg site:

- The URL is ``http://www.myelgg.org/``
- The installation path is ``/var/www/elgg/``
- The data directory is ``/var/data/elgg/``
- The database host is ``localhost``
- The database name is ``production_elgg``
- The database user is ``db_user``
- The database password is ``db_password``
- The database prefix is ``elgg``

At the end of the tutorial, our test Elgg installation details will be:

- The URL is ``http://test.myelgg.org/``
- The installation path is ``/var/www/elgg_test/``
- The data directory is ``/var/data/elgg_test/``
- The database host is ``localhost``
- The database name is ``test_elgg``
- The database user is ``db_user``
- The database password is ``db_password``
- The database prefix is ``elgg``

Copy Elgg Code to the Test Server
=================================

The very first step is to duplicate the production Elgg code. In our example, this is as simple as copying ``/var/www/elgg/`` to ``/var/www/elgg_test/``.

.. code::
   
   cp -a /var/www/elgg/ /var/www/elgg_test/

Copy Data to the Test Server
============================

In this example, this is as simple as copying ``/var/data/elgg/`` to ``/var/data/elgg_test/``.

.. code::
   
   cp -a /var/data/elgg/ /var/data/elgg_test/

If you don't have shell access to your server and have to ftp the data, you may need to change ownership and permissions on the files.

.. note::
   
   You also need to delete the views cache on the test server after the copy process. This is a directory called ``views_simplecache`` in your data directory and the directory called ``system_cache`` .

Edit .env.php
=============

The ``elgg-config/.env.php`` file contains the database, ``dataroot``, and ``wwwroot`` configuration details. These need to be adjusted for your new test Elgg installation.

Copy Elgg Database
==================

Now the database must be copied from ``elgg_production`` to ``elgg_test``. See your favorite MySQL manager's documentation for how to make a duplicate database. You will generally export the current database tables to a file, create the new database, and then import the tables that you previously exported.

You have two options on updating the values in the database. You could change the values in the export file or you could import the file and change the values with database queries. One advantage of modifying the dump file is that you can also change links that people have created to content within your site. For example, if people have bookmarked pages using the bookmark plugin, the bookmarks will point to the old site unless your update their URLs.

Database Entries
================

We must now change 4 entries in the database. This is easily accomplished with 4 simple SQL commands:

Check .htaccess
===============

If you have made changes to .htaccess that modify any paths, make sure you update them in the test installation.

Update Webserver Config
=======================

For this example, you must edit the Apache config to enable a subdomain with a document root of ``/var/www/elgg_test/``. If you plan to install into a subdirectory of your document root, this step is unnecessary.

If you're using Nginx, you need to update server config to match new paths based on ``install/config/nginx.dist``.

Run upgrade.php
===============

To regenerate cached data, make sure to run ``http://test.myelgg.org/upgrade.php``

Tips
====

It is a good idea to keep a test server around to experiment with installing new mods and doing development work.

Related
=======

.. seealso::

   :doc:`backup-restore`