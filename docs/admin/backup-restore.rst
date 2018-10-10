Backup and Restore
##################

.. contents:: Contents
   :local:
   :depth: 2

Introduction
============

Why
---

Shared hosting providers typically don't provide an automated way to backup your Elgg installation. This article will address a method of accomplishing this task.

In IT there are often many ways to accomplish the same thing. Keep that in mind. This article will explain one method to backup and restore your Elgg installation on a shared hosting provider that uses the CPanel application. However, the ideas presented here can be tailored to other applications as well. The following are typical situations that might require a procedure such as this:

- Disaster Recovery
- Moving your Elgg site to a new host
- Duplicating an installation

What
----

Topics covered:

- Full backups of the Elgg directories and MySQL databases are performed daily (automated)
- The backups are sent to an off-site location via FTP (automated)
- The local backups are deleted after successful transfer to the off-site location (automatic)
- Five days of backups will be maintained (automated)
- Restoration of data to the new host (manual)

This process was composed with assistance from previous articles in the Elgg documentation wiki.

Assumptions
-----------

The following assumptions have been made:

- The Elgg program directory is ``/home/userx/public_html``
- The Elgg data directory is ``/home/userx/elggdata``
- You've created a local directory for your backups at ``/home/userx/sitebackups``
- You have an off-site FTP server to send the backup files to
- The directory that you will be saving the off-site backups to is ``/home/usery/sitebackups/``
- You will be restoring the site to a second shared hosting provider in the ``/home/usery/public_html`` directory

.. important::

   Be sure to replace ``userx``, ``usery``, ``http://mynewdomain.com`` and all passwords with values that reflect your actual installation!

Creating a usable backup - automatically
========================================

Customize the backup script
---------------------------

The script that you will use can be found :doc:`here <backup/ftp-backup-script>` .

Just copy the script to a text file and name the file with a .pl extension. You can use any text editor to update the file.

Change the following to reflect your directory structure:

.. code-block:: perl

   # ENTER THE PATH TO THE DIRECTORY YOU WANT TO BACKUP, NO TRAILING SLASH
   $directory_to_backup = '/home/userx/public_html';
   $directory_to_backup2 = '/home/userx/elggdata';
   # ENTER THE PATH TO THE DIRECTORY YOU WISH TO SAVE THE BACKUP FILE TO, NO TRAILING SLASH
   $backup_dest_dir = '/home/userx/sitebackups';

Change the following to reflect your database parameters:

.. code-block:: perl

   # MYSQL BACKUP PARAMETERS
   $dbhost = 'localhost';
   $dbuser = 'userx_elgg';
   $dbpwd = 'dbpassword';
   # ENTER DATABASE NAME
   $database_names_elgg = 'userx_elgg';

Change the following to reflect your off-site FTP server parameters:

.. code-block:: perl

   # FTP PARAMETERS
   $ftp_host = "FTP HOSTNAME/IP";
   $ftp_user = "ftpuser";
   $ftp_pwd = "ftppassword";
   $ftp_dir = "/";
   
Save the file with the ``.pl`` extension (for the purposes of this article we will name the file: ``elgg-ftp-backup-script.pl``) and upload it to the following directory ``/home/userx/sitebackups``

Be aware that you can turn off FTP and flip a bit in the script so that it does not delete the local backup file in the event that you don't want to use off-site storage for your backups.

Configure the backup Cron job
-----------------------------

Login to your CPanel application and click on the "Cron Jobs" link. In the Common Settings dropdown choose "Once a day" and type the following in the command field ``/usr/bin/perl /home/userx/sitebackups/elgg-ftp-backup-script.pl``

Click on the "Add New Cron Job" button. Daily full backups are now scheduled and will be transferred off-site.

Configure the cleanup Cron job
------------------------------

If you are sending your backups, via FTP, to another shared hosting provider that uses the CPanel application or you've turned off FTP altogether you can configure your data retention as follows.

Login to your CPanel application for your FTP site, or locally if you're not using FTP, and click on the "Cron Jobs" link. In the Common Settings dropdown choose "Once a day" and type the following in the command field ``find /home/usery/sitebackups/full_* -mtime +4 -exec rm {} \;``

The ``-mtime X`` parameter will set the number of days to retain backups. All files older than ``x`` number of days will be deleted. Click on the "Add New Cron Job" button. You have now configured your backup retention time.

Restoring from backup
=====================

Prepare your backup files
-------------------------

The assumption is that you're restoring your site to another shared hosting provider with CPanel.

When the script backed the files up the original directory structure was maintained in the zip file. We need to do a little cleanup. Perform the following:

- Download the backup file that you wish to restore from
- Extract the contents of the backup file
- Drill down and you will find your site backup and SQL backup. Extract both of these. You will then have:
   - a MySQL dump file with a ``.sql`` extension
   - another directory structure with the contents of:
      - ``/home/userx/public_html``
      - ``/home/userx/elggdata``
- Repackage the contents of the ``/home/userx/public_html`` directory as a zip file so that the files are in the root of the zip file
   - The reason for doing this is simple. It's much more efficient to upload one zip file than it is to ftp the contents of the ``/home/userx/public_html`` directory to your new host.
- Repackage the contents of the /home/userx/elggdata directory as a zip file so that the files are in the root of the zip file

You should now have the following files:

- the ``.sql`` file
- the zip file with the contents of ``/home/userx/public_html`` in the root
- the zip file with the contents of ``/home/userx/elggdata`` in the root

Restore the files
-----------------

This is written with the assumption that you're restoring to a different host but maintaining the original directory structure. Perform the following:

- Login to the CPanel application on the host that you wish to restore the site to and open the File Manager.
- Navigate to ``/home/usery/public_html``
   - Upload the zip file that contains the ``/home/userx/public_html`` files
   - Extract the zip file
      You should now see all of the files in ``/home/usery/public_html``
   - Delete the zip file
- Navigate to ``/home/usery/elggdata``
   - Upload the zip file that contains the ``/home/userx/elggdata`` files
   - Extract the zip file
      You should now see all of the files in /home/usery/elggdata
   - Delete the zip file

Program and data file restoration is complete

Restore the MySQL Database
--------------------------

.. note::

   Again, the assumption here is that you're restoring your Elgg installation to a second shared hosting provider. Each shared hosting provider prepends the account holder's name to the databases associated with that account. For example, the username for our primary host is ``userx`` so the host will prepend ``userx_`` to give us a database name of ``userx_elgg``. When we restore to our second shared hosting provider we're doing so with a username of ``usery`` so our database name will be ``usery_elgg``. The hosting providers don't allow you to modify this behavior. So the process here isn't as simple as just restoring the database from backup to the usery account. However, having said that, it's not terribly difficult either.

Edit the MySQL backup
---------------------

Open the ``.sql`` file that you extracted from your backup in your favorite text editor. Comment out the following lines with a hash mark:

.. code-block:: sql

   #CREATE DATABASE /*!32312 IF NOT EXISTS*/ `userx_elgg` /*!40100 DEFAULT CHARACTER SET latin1 */;
   #USE `userx_elgg`;

Save the file.

Create the new database
-----------------------

Perform the following:

- Login to the CPanel application on the new host and click on the "MySQL Databases" icon
   - Fill in the database name and click the "create" button. For our example we are going to stick with ``elgg`` which will give us a database name of ``usery_elgg``
   - You can associate an existing user with the new database, but to create a new user you will need to:
      - Go to the "Add New User" section of the "MySQL Databases" page
      - Enter the username and password. For our example we're going to keep it simple and use ``elgg`` once again. This will give us a username of ``usery_elgg``
   - Associate the new user with the new database
      - Go to the "Add User To Database" section of the "MySQL Databases" page. Add the ``usery_elgg`` user to the ``usery_elgg`` database
      - Select "All Privileges" and click the "Make Changes" button

Restore the production database
-------------------------------

Now it's time to restore the MySQL backup file by importing it into our new database named "usery_elgg".

- Login to the CPanel application on the new host and click on the "phpMyAdmin icon
   - Choose the ``usery_elgg`` database in the left hand column
   - Click on the "import" tab at the top of the page
   - Browse to the ``.sql`` backup on your local computer and select it
   - Click the "Go" button on the bottom right side of the page

You should now see a message stating that the operation was successful

Bringing it all together
------------------------

The restored elgg installation knows **nothing** about the new database name, database username, directory structure, etc. That's what we're going to address here.

Edit ``/public_html/elgg-config/settings.php`` on the new hosting provider to reflect the database information for the database that you just created.

.. code-block:: php

   // Database username
   $CONFIG->dbuser = 'usery_elgg';
   
   // Database password
   $CONFIG->dbpass = 'dbpassword';
   
   // Database name
   $CONFIG->dbname = 'usery_elgg';
   
   // Database server
   // (For most configurations, you can leave this as 'localhost')
   $CONFIG->dbhost = 'localhost';
   
   $CONFIG->wwwroot = 'http://your.website.com/'

Upload the ``settings.php`` file back to the new host - overwriting the existing file.

Open the phpMyAdmin tool on the new host from the CPanel. Select the ``usery_elgg`` database on the left and click the SQL tab on the top of the page. Run the following SQL queries against the ``usery_elgg`` database:

Change the installation path

.. code-block:: sql

   UPDATE `elgg_config` SET `value` = REPLACE(`value`, "/home/userx/public_html/grid/", "/home/usery/public_html/grid/") WHERE `name` = "path";
   
Change the data directory

.. code-block:: sql

   UPDATE `elgg_config` SET `value` = REPLACE(`value`, "/home/userx/elggdata/", "/home/usery/elggdata/") WHERE `name` = "dataroot";

Change the filestore data directory

.. code-block:: sql

   UPDATE elgg_metadata set value = '/home/usery/elggdata/' WHERE name = 'filestore::dir_root';

Finalizing the new installation
-------------------------------

Run the upgrade script by visiting the following URL: ``http://mynewdomain.com/upgrade.php`` . Do this step twice - back to back.

Update your DNS records so that your host name resolves to the new host's IP address if this is a permanent move.

Congratulations!
================

If you followed the steps outlined here you should now have a fully functional copy of your primary Elgg installation.

Related
=======

.. toctree::

   backup/ftp-backup-script
   duplicate-installation