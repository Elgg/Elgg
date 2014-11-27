Installation
############

Get your own instance of Elgg running in no time.

.. contents:: Contents
   :local:
   :depth: 1

Requirements
============

-  MySQL 5+
-  PHP 5.2+ with the following extensions:

   -  GD (for graphics processing)
   -  `Multibyte String support`_ (for i18n)
   -  Proper configuration and ability to send email through an MTA

-  Web server with support for URL rewriting

Official support is provided for the following configuration:

-  Apache with the `rewrite module`_ enabled
-  PHP running as an Apache module

By "official support", we mean that:

-  Most development and testing is performed with this configuration
-  Much of the installation documentation is written assuming Apache is used
-  Priority on bug reports is given to Apache users if the bug is web server specific
   (but those are rare).

Overview
========

Upload Elgg
-----------

-  Download the `latest version of Elgg`_
-  Upload the ZIP file with an FTP client to your server
-  Unzip the files in your domain's document root (/home/username/www).

.. _latest version of Elgg: http://elgg.org/download.php

Create a data folder
--------------------

Elgg needs a special folder to store uploaded files including profile
icons and photos. You will need to create this directory.

.. warning::
   
   For security reasons, this folder MUST be stored outside of your
   document root. If you created it under /www/ or /public_html/, you're
   doing it wrong.

Once this folder has been created, you'll need to make sure the web
server Elgg is running on has permission to write to and create
directories in it. This shouldn't be a problem on Windows-based servers,
but if your server runs Linux, Mac OS X or a UNIX variant, you'll need
to `set the permissions on the directory`_.

.. _set the permissions on the directory: http://en.wikipedia.org/wiki/Filesystem_permissions#Traditional_Unix_permissions

If you are using a graphical FTP client to upload files, you can
usually set permissions by right clicking on the folder and
selecting 'properties' or 'Get Info'.

.. note::

   Directories must be executable to be read and written to. The
   suggested permissions depend upon the exact server and user
   configuration. If the data directory is owned by the web server
   user, the recommended permissions are 770.

   Setting your data directory to 777 will work, but it is insecure
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

Visit your Elgg site
--------------------

Once you've performed these steps, visit your Elgg site in your web
browser. Elgg will take you through the rest of the installation process
from there. The first account that you create at the end of the
installation process will be an administrator account.


A note on settings.php and .htaccess
------------------------------------

The Elgg installer will try to create two files for you:

-  engine/settings.php, which contains the database settings for your
   installation
-  .htaccess, which allows Elgg to generate dynamic URLs

If these files can't be automatically generated, for example because the
web server doesn't have write permissions in the directories, Elgg will
tell you how to create them. You could also temporarily change the
permissions on the root directory and the engine directory. Set the
permissions on those two directories so that the web server can write
those two files, complete the install process, and them change the
permissions back to their original settings. If, for some reason, this
won't work, you will need to:

-  Copy engine/settings.example.php to engine/settings.php, open it up
   in a text editor and fill in your database details
-  Copy /htaccess\_dist to /.htaccess

Other Configurations
====================

Lighttpd
--------
Have you installed Elgg on a server running lighttpd?
We are looking for someone to share any configuration
and installation steps involved in setting this up.

Nginx
-----
To run Elgg on Nginx, you will need to:

-  configure Nginx to talk to a PHP process in either CGI or FPM mode
-  Port the rewrite rules

TODO: Add the rewrite rules from the community site.

IIS
---

When installing on IIS, the problem is that the Apache mod\_rewrite
rules will not be recognized, and this breaks the application. You need
to convert the mod\_rewrite rules to the `IIS URL Rewrite`_ module
format.

You can do this using the IIS 7+ management console, and the "Import
Rules" feature that will do the conversion, as describe in the tutorial
"`importing Apache mod\_rewrite rules`_\ ".

.. _IIS URL Rewrite: http://www.iis.net/download/URLRewrite
.. _importing Apache mod\_rewrite rules: http://learn.iis.net/page.aspx/470/importing-apache-modrewrite-rules/

MariaDB
-------

This DBMS should be a drop-in replacement for MySQL, if you prefer it.

http://community.elgg.org/discussion/view/1455994/alternative-dbmss

Virtual host (e.g. Rackspace, Amazon EC2)
-----------------------------------------

For installation to proceed successfully, modify the .htaccess file in the
root, and uncomment::

    #RewriteBase /

To be::

    RewriteBase /

MAMP
----

On certain versions of MAMP, Elgg will either fail to install or have
intermittent problems while running.

This is a known issue with MAMP and is related to the Zend Optimizer.
Until Zend/MAMP have resolved this issue it is recommended that you turn
off the Zend Optimizer in your PHP settings.

XAMPP
-----

These intructions are provided in case you want to test your Elgg
installation on your local computer running Windows.

-  Download and install XAMPP to your computer from
   http://www.apachefriends.org/en/xampp.html
-  Once the installation is completed, it will prompt you to start the
   XAMPP controller panel. Leave it for now.
-  Open ``C:\xampp\apache\conf\httpd.conf`` file with notepad and uncomment
   these lines::

     #LoadModule rewrite_module modules/mod_rewrite.so
     #LoadModule filter_module modules/mod_filter.so

-  Edit the php.ini file and change
   ``arg_separator.output = &amp;amp;`` to ``arg_separator.output = &``
-  Go to ``C:\xampp`` and double click on the xampp_start application
-  Go to http://localhost/
-  Change your server's password in the security option
-  Go to http://localhost/phpmyadmin and login with the username and the
   password of your server
-  Create a database called "elgg" in your phpmyadmin panel
-  Now download Elgg. Unzip it and extract to ``C:\xampp\htdocs\sites\elgg``
-  Create the Elgg data folder as ``C:\xampp\htdocs\sites\data``
-  Go to http://localhost/sites/elgg
-  You will be taken to the Elgg installation steps. Install it and enjoy.

**A note on XAMPP 1.7.4 and eAccelerator**

Elgg is compatible with opcode caches and it is highly recommended that
you enable a PHP opcode caching tool for a faster experience.  XAMPP comes
with support for eAccelerator out of the box, but unfortunately, the 1.7.4
build of XAMPP leaves out the DLL that's required.  To get eAccelerator
working, follow these steps:

-  Download the DLL from http://eac.qme.nl/eAccelerator_v1_0_svn427_for_v5_3_5-VC6.zip
-  Copy eAccelerator_ts.dll to ``C:\xampp\php\ext\php_eaccelerator.dll``
-  Uncomment this line in ``C:\xampp\php\php.ini``::
   
     ;zend_extension = "C:\xampp\php\ext\php_eaccelerator.dll"
   
-  Restart apache

To verify that it is on:

-  Go to localhost/xampp
-  Click on phpinfo() from the left sidebar
-  Ctrl+F for eaccelerator.  If you get no results, eAccelerator is not active


EasyPHP
-------

-  Assuming no MySQL, PHP or Apache installations exist already.
-  Best run as a development/test server

1. Stop IIS running if installed

2. Download and install the latest Easy PHP from http://www.easyphp.org (16MB download)

3. Set up the database and point the web server to your Elgg folder (all done from the EasyPHP tray icon)
   -  Right click EasyPHP tray icon, select "Administration"
   -  A new tab is created in your browser for managing Easy PHP
   -  Add your Elgg folder to Apache in "Alias" section
   -  Click "Manage MySQL with PhpMyAdmin", create a database and account for Elgg

4. (Ignore this step for v5.3 or later) From the tray icon go Configuration/Apache
   and uncomment this line::
   
     #LoadModule rewrite_module modules/mod_rewrite.so

5. (Ignore this step for v5.3 or later) Change ``AllowOverride None`` to ``AllowOverride All``
   in the relevant directory entry in Configuration/Apache

6. (Ignore this step for v5.3 or later) From the tray icon fo Configuration/PHP
   and uncomment this line::
   
     ;extension=php_curl.dll

7. A reboot is best Elgg should run via http://127.0.0.1


Ubuntu Linux
------------

-  Install the dependencies::

     sudo apt-get install apache2
     sudo apt-get install mysql-server
     sudo apt-get install php5 libapache2-mod-php5 php5-mysql
     sudo apt-get install phpmyadmin
     sudo a2enmod rewrite

-  Edit ``/etc/apache2/sites_available/default`` to enable .htaccess processing (set AllowOverride to All)
-  Restart Apache: ``sudo /etc/init.d/apache2 restart``
-  Follow the standard installation instructions above

Cloud9IDE
---------

**1. Create a c9 workspace**

-  Go to http://c9.io
-  Login with GitHub
-  On the Dashboard, click "Create new workspace" => "Create a new
   workspace"
-  Choose a project name (e.g. "elgg")
-  Choose "PHP" for project type
-  Click "Create"
-  Wait... (~1 min for c9 workspace to be ready)
-  Click "Start editing" for the workspace

**2. Set up the workspace for Elgg**

Run the following in cloud9's terminal:

.. code:: sh

    rm -rf * # Clear out the c9 hello-world stuff
    git clone https://github.com/Elgg/Elgg . # the hotness
    cp htaccess_dist .htaccess
    cp engine/settings.example.php engine/settings.php
    mysql-ctl start # start c9's local mysql server
    mkdir ../elgg-data # setup data dir for Elgg

Configure ``engine/settings.php`` to be like so:

.. code:: php

    // Must set timezone explicitly!
    date_default_timezone_set('America/Los_Angeles');
    $CONFIG->dbuser = 'your_username'; // Your c9 username
    $CONFIG->dbpass = '';
    $CONFIG->dbname = 'c9';
    $CONFIG->dbhost = $_SERVER['SERVER_ADDR'];
    $CONFIG->dbprefix = 'elgg_';

**3. Complete the install process from Elgg's UI**

-  Hit "Run" at the top of the page to start Apache.
-  Go to ``http://your-workspace.your-username.c9.io/install.php?step=database``
-  Change Site URL to ``http://your-workspace.your-username.c9.io/``
-  Put in the data directory path. Should be something like
   ``/var/..../app-root/data/elgg-data/``.
-  Click "Next"
-  Create the admin account
-  Click "Go to site"
-  You may have to manually visit http://your-workspace.your-username.c9.io/
   and login with the admin credentials you just configured.

.. _Multibyte String support: http://www.php.net/mbstring
.. _rewrite module: http://httpd.apache.org/docs/2.0/mod/mod_rewrite.html

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
``htaccess_dist`` file you find with elgg package to ``.htaccess``. Also
if you find a ``.htaccess`` file inside the installation path, but you
are still getting 404 error, make sure the contents of ``.htaccess`` are
same as that of ``htaccess_dist``.

**``mod_rewrite`` isn't installed.**

Check your ``httpd.conf`` to make sure that this module is being loaded
by Apache. You may have to restart Apache to get it to pick up any
changes in configuration. You can also use `PHP info`_ to check to see
if the module is being loaded.

**The rules in ``.htaccess`` aren't being obeyed.**

.. _PHP info: http://uk.php.net/manual/en/function.phpinfo.php

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
this is give it permissions 777. It is better to give the web server
ownership of the directory and limit the permissions.

The top cause of this issue is PHP configured to prevent access to most
directories using `open\_basedir`_. You may want to check with your
hosting provider on this.

Make sure the path is correct and ends with a /. You can check the path
in your database in the datalists table.

If you only have ftp access to your server and created a directory but
do not know the path of it, you might be able to figure it out from the
www file path set in your datalists database table. Asking for help from
your hosting help team is recommended at this stage.

.. _open\_basedir: http://www.php.net/manual/en/ini.core.php#ini.open-basedir


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

.. _Elgg community: http://community.elgg.org/
