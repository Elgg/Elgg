FAQs and Other Troubleshooting
##############################

Below are some commonly asked questions about Elgg.

.. contents::
   :depth: 3

General
=======

"Plugin cannot start and has been deactivated" or "This plugin is invalid"
--------------------------------------------------------------------------
This error is usually accompanied by more details explaining why the plugin is invalid. This is usually
caused by an incorrectly installed plugin.

If you are installing a plugin called "test", there will be a test directory under mod. In that test directory there needs to be a start.php file: ``/mod/test/start.php`` and a manifest.xml file ``/mod/test/manifest.xml``.

If these files do not exist, it could be caused by:
	* installing a plugin to the wrong directory
	* creating a directory under /mod that does not contain a plugin
	* a bad ftp transfer
	* unzipping a plugin into an extra directory (myplugin.zip unzips to ``myplugin/myplugin``)

If you are on a Unix-based host and the files exist in the correct directory, check the permissions. Elgg must have read access to the files and read + execute access on the directories.

White Page (WSOD)
-----------------

A blank, white page (often called a "white screen of death") means there is a PHP syntax error. There are a few possible causes of this:
	* corrupted file - try transfering the code again to your server
	* a call to a php module that was not loaded - this can happen after you install a plugin that requires a specific module.
	* bad plugin - not all plugins have been written to the same quality so you should be careful which ones you install.

To find where the error is occurring, change the .htaccess file to display errors to the browser. Set display_errors to 1 and load the same page again. You should see a PHP error in your browser. Change the setting back once you've resolved the problem.

	.. note:: If you are using the Developer's Tools plugin, go to its settings page and make sure you have "Display fatal PHP errors" enabled.

If the white screen is due to a bad plugin, remove the latest plugins that you have installed by deleting their directories and then reload the page.

	.. note:: You can temporarily disable all plugins by creating an empty file at ``mod/disabled``. You can then disable the offending module via the administrator tools panel.

If you are getting a WSOD when performing an action, like logging in or posting a blog, but there are no error messages, it's most likely caused by non-printable characters in plugin code. Check the plugin for white spaces/new lines characters after finishing php tag (?>) and remove them.

Login token mismatch
--------------------

If you have to log in twice to your site and the error message after the first attempt says there was a token mismatch error, the URL in Elgg's settings does not match the URL used to access it. The most common cause for this is adding or removing the "www" when accessing the site. For example, www.elgg.org vs elgg.org. This causes a problem with session handling because of the way that web browsers save cookies.

To fix this, you can add rewrite rules. To redirect from www.elgg.org to elgg.org in Apache, the rules might look like::

	RewriteCond %{HTTP_HOST} .
	RewriteCond %{HTTP_HOST} !^elgg\.org
	RewriteRule (.*) http://elgg.org/$1 [R=301,L]

Redirecting from non-www to www could look like this::

	RewriteCond %{HTTP_HOST} ^elgg\.org
	RewriteRule ^(.*)$ http://www.elgg.org/$1 [R=301,L]

If you don't know how to configure rewrite rules, ask your host for more information.

Form is missing __token or __ts fields
--------------------------------------

All Elgg actions require a security token, and this error occurs when that token is missing. This is either a problem with your server configuration or with a 3rd party plugin.

If you experience this on a new installation, make sure that your server is properly configured and your rewrite rules are correct. If you experience this on an upgrade, make sure you have updated your rewrite rules either in .htaccess (Apache) or in the server configuration.

If you are experiencing this, disable all 3rd party plugins and try again. Very old plugins for Elgg don't use security tokens. If the problem goes away when plugins are disabled, it's due to a plugin that should be updated by its author.

Maintenance mode
----------------

To take your site temporarily offline, go to Administration -> Utilities -> Maintenance Mode. Complete the form and hit save to disable your site for everyone except admin users.

Missing email
-------------

If your users are reporting that validation emails are not showing up, have them check their spam folder. It is possible that the emails coming from your server are being marked as spam. This depends on many factors such as whether your hosting provider has a problem with spammers, how your PHP mail configuration is set up, what mail transport agent your server is using, or your host limiting the number of email that you can send in an hour.

If no one gets email at all, it is quite likely your server is not configured properly for email. Your server needs a program to send email (called a Mail Transfer Agent - MTA) and PHP must be configured to use the MTA.

To quickly check if PHP and an MTA are correctly configured, create a file on your server with the following content:

.. code:: php

	<?php
	$address = "your_email@your_host.com";

	$subject = 'Test email.';

	$body = 'If you can read this, your email is working.';

	echo "Attempting to email $address...<br />";

	if (mail($address, $subject, $body)) {
		echo 'SUCCESS!  PHP successfully delivered email to your MTA.  If you don\'t see the email in your inbox in a few minutes, there is a problem with your MTA.';
	} else {
		echo 'ERROR!  PHP could not deliver email to your MTA.  Check that your PHP settings are correct for your MTA and your MTA will deliver email.';
	}

Be sure to replace "your_email@your_host.com" with your actual email address.  Take care to keep quotes around it!  When you access this page through your web browser, it will attempt to send a test email.  This test will let you know that PHP and your MTA are correctly configured.  If it fails--either you get an error or you never receive the email--you will need to do more investigating and possibly contact your service provider.

Fully configuring an MTA and PHP's email functionality is beyond the scope of this FAQ and you should search the Internet for more resources on this. Some basic information on php parameters can be found on `PHP's site`__

__ http://php.net/manual/en/mail.configuration.php


Server logs
-----------

Most likely you are using Apache as your web server. Warnings and errors are written to a log by the web server and can be useful for debugging problems. You will commonly see two types of log files: access logs and error logs. Information from PHP and Elgg is written to the server error log.

	* Linux -- The error log is probably in /var/log/httpd or /var/log/apache2.
	* Windows - It is probably inside your Apache directory.
	* Mac OS - The error log is probably in /var/log/apache2/error_log

If you are using shared hosting without ssh access, your hosting provider may provide a mechanism for obtaining access to your server logs. You will need to ask them about this.

User validation
---------------

By default, all users who self-register must validate their accounts through email. If a user has
problems validating an account, you can validate users manually by going to Administration -> Users -> Unvalidated.

You can remove this requirement by deactivating the User Validation by Email plugin.

	.. note:: Removing validation has some consequences: There is no way to know that a user registered with a working email address, and it may leave you system open to spammers.

I'm making or just installed a new theme, but graphics or other elements aren't working
---------------------------------------------------------------------------------------

Make sure the theme is at the bottom of the plugin list.

Clear your browser cache and reload the page. To lighten the load on the server, Elgg instructs the browser to rarely load the CSS file. A new theme will completely change the CSS file and a refresh should cause the browser to request the CSS file again.

If you're building or modifying a theme, make sure you have disabled the simple and system caches. This can be done by
enabling the Developer Tools plugin, then browsing to Administration -> Develop -> Settings. Once you're satisfied with the changes, enable the caches or performance will suffer.

Changing profile fields
-----------------------

Within the Administration settings of Elgg is a page for replacing the default profile fields. Elgg by default gives the administrator two choices:

- Use the default profile fields
- Replace the default with a set of custom profile fields

You cannot add new profile fields to the default ones. Adding a new profile field through the replace profile fields option clears the default ones. Before letting in users, it is best to determine what profile fields you want, what field types they should be, and the order they should appear. You cannot change the field type or order or delete fields after they have been created without wiping the entire profile blank.

More flexibility can be gained through plugins. There is at least two plugins on the community site that enable you to have more control over profile fields. The `Profile Manager`_ plugin has become quite popular in the Elgg community. It lets you add new profile fields whenever you want, change the order, group profile fields, and add them to registration.

.. _Profile Manager: https://community.elgg.org/plugins/385114

Changing registration
---------------------

The registration process can be changed through a plugin. Everything about registration can be changed: the look and feel, different registration fields, additional validation of the fields, additional steps and so on. These types of changes require some basic knowledge of HTML, CSS, PHP.

Another option is to use the `Profile Manager`_ plugin that lets you add fields to both user profiles and the registration form.

Create the plugin skeleton
  :doc:`/guides/plugins/plugin-skeleton`

Changing registration display
   Override the ``account/forms/register`` view

Changing the registration action handler
   You can write your own action to create the user's account

Development
===========

What should I use to edit php code
----------------------------------

There are two main options: text editor or `integrated development environment`_ (IDE).

Text Editor
^^^^^^^^^^^

If you are new to software development or do not have much experience with IDEs, using a text editor will get you up and running the quickest. At a minimum, you will want one that does syntax highlighting to make the code easier to read. If you think you might submit patches to the bug tracker, you will want to make sure that your text editor does not change line endings. If you are using Windows, `Notepad++`_ is a good choice. If you are on a Mac, TextWrangler_ is a popular choice. You could also give TextMate_ a try.
   
Integrated Development Environment
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

An IDE does just what it's name implies: it includes a set of tools that you would normally use separately. Most IDEs will include source code control which will allow you to directly commit and update your code from your cvs repository. It may have an FTP client built into it to make the transfer of files to a remote server easier. It will have syntax checking to catch errors before you try to execute the code on a server.

The two most popular free IDEs for PHP developers are Eclipse_ and NetBeans_. Eclipse has two different plugins for working with PHP code: PDT_ and PHPEclipse_.

.. _integrated development environment: http://en.wikipedia.org/wiki/Integrated_development_environment
.. _Notepad++: http://notepad-plus-plus.org/
.. _TextWrangler: http://www.barebones.com/products/textwrangler/index.html
.. _TextMate: http://macromates.com/
.. _Eclipse: http://www.eclipse.org/
.. _NetBeans: http://netbeans.org/
.. _PDT: http://www.eclipse.org/pdt/
.. _PHPEclipse: http://www.phpeclipse.com/
