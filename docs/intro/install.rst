Installation
############

Get your own instance of Elgg running in no time.

.. contents:: Contents
   :local:
   :depth: 1

Requirements
============

- MySQL 5.5.3+ (5.0.0+ if upgrading an existing installation)
- PHP 7.0+ with the following extensions:

   -  GD (for graphics processing)
   -  PDO (for database connection)
   -  JSON (for AJAX responses, etc.)
   -  XML (for reading plugin manifest files, etc.)
   -  `Multibyte String support`_ (for i18n)
   -  Proper configuration and ability to send email through an MTA

- Web server with support for URL rewriting

Official support is provided for the following configurations:

- Apache server
   -  Apache with the `rewrite module`_ enabled
   -  PHP running as an Apache module

- Nginx server
   - Nginx with PHP-FPM using FastCGI

By "official support", we mean that:

-  Most development and testing is performed with these configurations
-  Much of the installation documentation is written assuming Apache or Nginx is used
-  Priority on bug reports is given to Apache and Nginx users if the bug is web server specific
   (but those are rare).

.. _Multibyte String support: http://www.php.net/mbstring
.. _rewrite module: https://httpd.apache.org/docs/2.0/mod/mod_rewrite.html

Browser support policy
----------------------

Feature branches support the latest 2 versions of all major browsers
as were available at the time of the first stable release on that branch.

Bugfix release will not alter browser support,
even if a new version of the browser has since been released.

Major browsers here means all of the following, plus their mobile counterparts:

 * Android Browser
 * Chrome
 * Firefox
 * IE
 * Safari

"Support" may mean that we take advantage of newer, unimplemented technologies
but provide a JavaScript polyfill for the browsers that need it.

You may find that Elgg happens to work on unsupported browsers,
but compatibility may break at any time, even during a bugfix release.

Overview
========

Upload Elgg
-----------

With Composer (recommended if comfortable with CLI):

.. code-block:: sh

    composer self-update
    composer create-project elgg/starter-project:dev-master ./path/to/project/root
    cd ./path/to/project/root
    composer install
    composer install # 2nd call is currently required
    vendor/bin/elgg-cli install # follow the questions to provide installation details


From pre-packaged zip (recommended if not comfortable with CLI):

-  Download the `latest version of Elgg`_
-  Upload the ZIP file with an FTP client to your server
-  Unzip the files in your domain's document root.

.. _latest version of Elgg: https://elgg.org/about/download

Create a data folder
--------------------

Elgg needs a special folder to store uploaded files including profile
icons and photos. You will need to create this directory.

.. attention::
   
   For security reasons, this folder **MUST** be stored outside of your
   document root. If you created it under ``/www/`` or ``/public_html/``, you're
   doing it wrong.

Once this folder has been created, you'll need to make sure the web
server Elgg is running on has permission to write to and create
directories in it. This shouldn't be a problem on Windows-based servers,
but if your server runs Linux, Mac OS X or a UNIX variant, you'll need
to `set the permissions on the directory`_.

.. _set the permissions on the directory: https://en.wikipedia.org/wiki/File_system_permissions#Traditional_Unix_permissions

If you are using a graphical FTP client to upload files, you can
usually set permissions by right clicking on the folder and
selecting 'properties' or 'Get Info'.

.. note::

   Directories must be executable to be read and written to. The 
   suggested permissions depend upon the exact server and user
   configuration. If the data directory is owned by the web server
   user, the recommended permissions are ``750``.

.. warning::

   Setting your data directory to ``777`` will work, but it is insecure
   and is not recommended. If you are unsure how to correctly set
   permissions, contact your host for more information.

Create a MySQL database
-----------------------

Using your database administration tool of choice (if you're unsure
about this, ask your system administrator), create a new MySQL database
for Elgg. You can create a MySQL database with any of the following
tools:

Make sure you add a user to the database with all privileges and record
the database name, username and password. You will need this information
when installing Elgg.

Set up Cron
-----------

Elgg uses timed requests to your site to perform background tasks like
sending notifications or performing database cleanup jobs. You need
to configure the :doc:`cron</admin/cron>` to be able to use those kind of features.

Visit your Elgg site
--------------------

Once you've performed these steps, visit your Elgg site in your web
browser. Elgg will take you through the rest of the installation process
from there. The first account that you create at the end of the
installation process will be an administrator account.


A note on settings.php and .htaccess
------------------------------------

The Elgg installer will try to create two files for you:

-  ``elgg-config/settings.php``, which contains local environment configuration for your installation
-  ``.htaccess``, which allows Elgg to generate dynamic URLs

If these files can't be automatically generated, for example because the
web server doesn't have write permissions in the directories, Elgg will
tell you how to create them. You could also temporarily change the
permissions on the root directory and the engine directory. Set the
permissions on those two directories so that the web server can write
those two files, complete the install process, and them change the
permissions back to their original settings. If, for some reason, this
won't work, you will need to:

-  In ``elgg-config/``, copy ``settings.example.php`` to ``settings.php``, open it up
   in a text editor and fill in your database details
-  On Apache server, copy ``install/config/htaccess.dist`` to ``.htaccess``
-  On Nginx server copy ``install/config/nginx.dist`` to ``/etc/nginx/sites-enabled`` and adjust it's contents

Other Configurations
====================

 * :doc:`Cloud9 <./install/cloud9>`
 * :doc:`Homestead <./install/homestead>`
 * :doc:`EasyPHP <./install/easyphp>`
 * :doc:`IIS <./install/iis>`
 * :doc:`MAMP <./install/mamp>`
 * :doc:`MariaDB <./install/mariadb>`
 * :doc:`Nginx <./install/nginx>`
 * :doc:`Ubuntu <./install/ubuntu>`
 * :doc:`Virtual hosts <./install/virtual>`
 * :doc:`XAMPP <./install/xampp>`

Troubleshooting
===============

Help! I'm having trouble installing Elgg
----------------------------------------

First:

-  Recheck that your server meets the technical requirements for Elgg.
-  Follow the environment-specific instructions if need be
-  Have you verified that ``mod_rewrite`` is being loaded?
-  Is the mysql apache being loaded?

Keep notes on steps that you take to fix the install. Sometimes changing
some setting or file to try to fix a problem may cause some other
problem later on. If you need to start over, just delete all the files,
drop your database, and begin again.

I can't save my settings on installation (I get a 404 error when saving settings)
---------------------------------------------------------------------------------

Elgg relies on the ``mod_rewrite`` Apache extension in order to simulate
certain URLs. For example, whenever you perform an action in Elgg, or
when you visit a user's profile, the URL is translated by the server
into something Elgg understands internally. This is done using rules
defined in an ``.htaccess`` file, which is Apache's standard way of
defining extra configuration for a site.

This error suggests that the ``mod_rewrite`` rules aren't being picked
up correctly. This may be for several reasons. If you're not comfortable
implementing the solutions provided below, we strongly recommend that
you contact your system administrator or technical support and forward
this page to them.

The ``.htaccess``, if not generated automatically (that happens when you
have problem with ``mod_rewrite``), you can create it by renaming
``install/config/htaccess.dist`` file you find with elgg package to ``.htaccess``. Also
if you find a ``.htaccess`` file inside the installation path, but you 
are still getting 404 error, make sure the contents of ``.htaccess`` are
same as that of ``install/config/htaccess.dist``.

**``mod_rewrite`` isn't installed.**

Check your ``httpd.conf`` to make sure that this module is being loaded
by Apache. You may have to restart Apache to get it to pick up any
changes in configuration. You can also use `PHP info`_ to check to see
if the module is being loaded.

**The rules in ``.htaccess`` aren't being obeyed.**

.. _PHP info: https://secure.php.net/manual/en/function.phpinfo.php

In your virtual host configuration settings (which may be contained
within ``httpd.conf``), change the AllowOverride setting so that it
reads:

``AllowOverride all``

This will tell Apache to pick up the ``mod_rewrite`` rules from 
``.htaccess``.

**Elgg is not installed in the root of your web directory (ex:
http://example.org/elgg/ instead of http://example.org/)**

The install script redirects me to "action" when it should be "actions"
-----------------------------------------------------------------------

This is a problem with your ``mod_rewrite`` setup.

.. attention::

	DO NOT, REPEAT, DO NOT change any directory names!

I installed in a subdirectory and my install action isn't working!
------------------------------------------------------------------

If you installed Elgg so that it is reached with an address like
http://example.org/mysite/ rather than http://example.org/, there is a
small chance that the rewrite rules in .htaccess will not be processed
correctly. This is usually due to using an alias with Apache. You may
need to give mod\_rewrite a pointer to where your Elgg installation is.

-  Open up .htaccess in a text editor

-  Where prompted, add a line like
   ``RewriteBase /path/to/your/elgg/installation/`` (Don't forget the
   trailing slash)
-  Save the file and refresh your browser.

Please note that the path you are using is the **web** path, minus the
host.

For example, if you reach your elgg install at http://example.org/elgg/,
you would set the base like this:

``RewriteBase /elgg/``

Please note that installing in a subdirectory does not require using
RewriteBase. There are only some rare circumstances when it is needed
due to the set up of the server.

I did everything! mod\_rewrite is working fine, but still the 404 error
-----------------------------------------------------------------------

Maybe there is a problem with the file .htaccess. Sometimes the elgg
install routine is unable to create one and unable to tell you that. If
you are on this point and tried everything that is written above:

-  check if it is really the elgg-created .htaccess (not only a dummy
   provided from the server provider)

-  if it is not the elgg provided htaccess file, use the htaccess\_dist
   (rename it to .htaccess)

I get an error message that the rewrite test failed after the requirements check page
-------------------------------------------------------------------------------------

I get the following messages after the requirements check step (step 2) of the install:

    We think your server is running the Apache web server.

    The rewrite test failed and the most likely cause is that AllowOverride is not set to All for Elgg's directory. This prevents
    Apache from processing the .htaccess file which contains the rewrite rules.

    A less likely cause is Apache is configured with an alias for your Elgg directory and you need to set the RewriteBase in
    your .htaccess. There are further instructions in the .htaccess file in your Elgg directory.
    
After this error, everinteraction with the web interface results in a error 500 (Internal Server Error)

This is likely caused by not loading the "filter module by un-commenting the

     #LoadModule filter_module modules/mod_filter.so
     
line in the "httpd.conf" file.

the Apache "error.log" file will contain an entry similar to:

     ... .htaccess: Invalid command 'AddOutputFilterByType', perhaps misspelled or defined by a module not included in the server configuration

There is a white page after I submit my database settings
---------------------------------------------------------

Check that the Apache mysql module is installed and is being loaded.

I'm getting a 404 error with a really long url
----------------------------------------------

If you see a 404 error during the install or on the creation of the
first user with a url like:
``http://example.com/homepages/26/d147515119/htdocs/elgg/action/register``
that means your site url is incorrect in your sites\_entity table in
your database. This was set by you on the second page of the install.
Elgg tries to guess the correct value but has difficulty with shared
hosting sites. Use phpMyAdmin to edit this value to the correct base
url.

I am having trouble setting my data path
----------------------------------------

This is highly server specific so it is difficult to give specific
advice. If you have created a directory for uploading data, make sure
your http server can access it. The easiest (but least secure) way to do
this is give it permissions ``777``. It is better to give the web server
ownership of the directory and limit the permissions.

.. warning::

	Setting directory permissions to ``777`` allows the **ENTIRE** internet to place 
	files in your directory structure an possibly infect you webserver with malware.
	Setting permissions to ``750`` should be more than enough. 

The top cause of this issue is PHP configured to prevent access to most
directories using `open\_basedir`_. You may want to check with your
hosting provider on this.

Make sure the path is correct and ends with a /. You can check the path
in your database in the config table.

If you only have ftp access to your server and created a directory but
do not know the path of it, you might be able to figure it out from the
www file path set in your config database table. Asking for help from
your hosting help team is recommended at this stage.

.. _open\_basedir: https://secure.php.net/manual/en/ini.core.php#ini.open-basedir

I can't validate my admin account because I don't have an email server!
-----------------------------------------------------------------------

While it's true that normal accounts (aside from those created from the
admin panel) require their email address to be authenticated before they
can log in, the admin account does not.

Once you have registered your first account you will be able to log in
using the credentials you have provided!

I have tried all of these suggestions and I still cannot install Elgg
---------------------------------------------------------------------

It is possible that during the process of debugging your install you
have broken something else. Try doing a clean install:

-  drop your elgg database
-  delete your data directory
-  delete the Elgg source files
-  start over

If that fails, seek the help of the `Elgg community`_. 
Be sure to mention what version of Elgg you are installing, details of
your server platform, and any error messages that you may have received
including ones in the error log of your server.

.. _Elgg community: https://elgg.org/
