Development
===========

What should I use to edit php code?
-----------------------------------

There are two main options: text editor or `integrated development environment`_ (IDE).

Text Editor
^^^^^^^^^^^

If you are new to software development or do not have much experience with IDEs, using a text editor will get you up and running the quickest. At a minimum, you will want one that does syntax highlighting to make the code easier to read. If you think you might submit patches to the bug tracker, you will want to make sure that your text editor does not change line endings. If you are using Windows, `Notepad++`_ is a good choice. If you are on a Mac, TextWrangler_ is a popular choice. You could also give TextMate_ a try.
   
Integrated Development Environment
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

An IDE does just what its name implies: it includes a set of tools that you would normally use separately. Most IDEs will include source code control which will allow you to directly commit and update your code from your cvs repository. It may have an FTP client built into it to make the transfer of files to a remote server easier. It will have syntax checking to catch errors before you try to execute the code on a server.

The two most popular free IDEs for PHP developers are Eclipse_ and NetBeans_. Eclipse has two different plugins for working with PHP code: PDT_ and PHPEclipse_.

.. _integrated development environment: http://en.wikipedia.org/wiki/Integrated_development_environment
.. _Notepad++: http://notepad-plus-plus.org/
.. _TextWrangler: http://www.barebones.com/products/textwrangler/index.html
.. _TextMate: http://macromates.com/
.. _Eclipse: http://www.eclipse.org/
.. _NetBeans: http://netbeans.org/
.. _PDT: http://www.eclipse.org/pdt/
.. _PHPEclipse: http://www.phpeclipse.com/

I don't like the wording of something in Elgg. How do I change it?
------------------------------------------------------------------

The best way to do this is with a plugin.

Create the plugin skeleton
^^^^^^^^^^^^^^^^^^^^^^^^^^

:doc:`/guides/plugins/plugin-skeleton`

Locate the string that you want to change
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

All the strings that a user sees should be in the ``/languages`` directory or in a plugin's languages directory (``/mod/<plugin name>/languages``). This is done so that it is easy to change what language Elgg uses. For more information on this see the developer documentation on :doc:`/guides/i18n` .

To find the string use ``grep`` or a text editor that provides searching through files to locate the string. (A good text editor for Windows is `Notepad++`_ ) Let's say we want to change the string "Add friend" to "Make a new friend". The ``grep`` command to find this string would be ``grep -r "Add friend" *``. Using `Notepad++`_ , you would use the "Find in files" command. You would search for the string, set the filter to ``*.php``, set the directory to the base directory of Elgg, and make sure it searches all subdirectories. You might want to set it to be case sensitive also.

You should locate the string "Add friend" in ``/languages/en.php``. You should see something like this in the file:

.. code-block:: php
   
   'friend:add' => "Add friend",

This means every time Elgg sees ``friend:add`` it replaces it with "Add friend". We want to change the definition of ``friend:add``.

Override the string
^^^^^^^^^^^^^^^^^^^

To override this definition, we will add a languages file to the plugin that we built in the first step.

1. Create a new directory: ``/mod/<your plugin name>/languages``
2. Create a file in that directory called ``en.php``
3. Add these lines to that file

.. code-block:: php
   
   <?php
   
   return array(   
      'friend:add' => 'Make a new friend',   
   );

Make sure that you do not have any spaces or newlines before the ``<?php``.

You're done now and should be able to enable the plugin and see the change. If you are override the language of a plugin, make sure your plugin is loaded after the one you are trying to modify. The loading order is determined in the Tools Administration page of the admin section. As you find more things that you'd like to change, you can keep adding them to this plugin.

How do I find the code that does x?
-----------------------------------

The best way to find the code that does something that you would like to change is to use ``grep`` or a similar search tool. If you do not have ``grep`` as a part of your operating system, you will want to install a grep tool or use a text-editor/IDE that has good searching in files. `Notepad++`_ is a good choice for Windows users. `Eclipse`_ with PHP and `NetBeans`_ are good choices for any platform.

String Example
^^^^^^^^^^^^^^

Let's say that you want to find where the *Log In* box code is located. A string from the *Log In* box that should be fairly unique is ``Remember me``. ``Grep`` for that string. You will find that it is only used in the ``en.php`` file in the ``/languages`` directory. There it is used to define the :doc:`/guides/i18n` string ``user:persistent``. ``Grep`` for that string now. You will find it in two places: the same ``en.php`` language file and in ``/views/default/forms/login.php``. The latter defines the html code that makes up the *Log In* box.

Action Example
^^^^^^^^^^^^^^

Let's say that you want to find the code that is run when a user clicks on the *Save* button when arranging widgets on a profile page. View the Profile page for a test user. Use Firebug to drill down through the html of the page until you come to the action of the edit widgets form. You'll see the url from the base is ``action/widgets/move``.

``Grep`` on ``widgets/move`` and two files are returned. One is the JavaScript code for the widgets : ``/js/lib/ui.widgets.js``. The other one, ``/engine/lib/widgets.php``, is where the action is registered using ``elgg_register_action('widgets/reorder')``. You may not be familiar with that function in which case, you should look it up at the API reference. Do a search on the function and it returns the documentation on the function. This tells you that the action is in the default location since a file location was not specified. The default location for actions is ``/actions`` so you will find the file at ``/actions/widgets/move.php``.

Debug mode
----------

During the installation process you might have noticed a checkbox that controlled whether debug mode was turned on or off. This setting can also be changed on the Site Administration page. Debug mode writes a lot of extra data to your php log. For example, when running in this mode every query to the database is written to your logs. It may be useful for debugging a problem though it can produce an overwhelming amount of data that may not be related to the problem at all. You may want to experiment with this mode to understand what it does, but make sure you run Elgg in normal mode on a production server.

.. warning::

   Because of the amount of data being logged, don't enable this on a production server as it can fill up the log files really quick.

What goes into the log in debug mode?
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- All database queries
- Database query profiling
- Page generation time
- Number of queries per page
- List of plugin language files
- Additional errors/warnings compared to normal mode (it's very rare for these types of errors to be related to any problem that you might be having)

What does the data look like?
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: text

   [07-Mar-2009 14:27:20] Query cache invalidated
   [07-Mar-2009 14:27:20] ** GUID:1 loaded from DB
   [07-Mar-2009 14:27:20] SELECT * from elggentities where guid=1 and ( (1 = 1)  and enabled='yes') results cached
   [07-Mar-2009 14:27:20] SELECT guid from elggsites_entity where guid = 1 results cached
   [07-Mar-2009 14:27:20] Query cache invalidated
   [07-Mar-2009 14:27:20] ** GUID:1 loaded from DB
   [07-Mar-2009 14:27:20] SELECT * from elggentities where guid=1 and ( (1 = 1)  and enabled='yes') results cached
   [07-Mar-2009 14:27:20] ** GUID:1 loaded from DB
   [07-Mar-2009 14:27:20] SELECT * from elggentities where guid=1 and ( (1 = 1)  and enabled='yes') results returned from cache
   [07-Mar-2009 14:27:20] ** Sub part of GUID:1 loaded from DB
   [07-Mar-2009 14:27:20] SELECT * from elggsites_entity where guid=1 results cached
   [07-Mar-2009 14:27:20] Query cache invalidated
   [07-Mar-2009 14:27:20] DEBUG: 2009-03-07 14:27:20 (MST): "Undefined index:  user" in file /var/www/elgg/engine/lib/elgglib.php (line 62)
   [07-Mar-2009 14:27:20] DEBUG: 2009-03-07 14:27:20 (MST): "Undefined index:  pass" in file /var/www/elgg/engine/lib/elgglib.php (line 62)
   [07-Mar-2009 14:27:20] ***************** DB PROFILING ********************
   [07-Mar-2009 14:27:20] 1 times: 'SELECT * from elggentities where guid=1 and (  (access_id in (2) or (owner_guid = -1) or (access_id = 0 and owner_guid = -1)) and enabled='yes')' 
   ...
   [07-Mar-2009 14:27:20] 2 times: 'update elggmetadata set access_id = 2 where entity_guid = 1' 
   [07-Mar-2009 14:27:20] 1 times: 'UPDATE elggentities set owner_guid='0', access_id='2', container_guid='0', time_updated='1236461868' WHERE guid=1' 
   [07-Mar-2009 14:27:20] 1 times: 'SELECT guid from elggsites_entity where guid = 1' 
   [07-Mar-2009 14:27:20] 1 times: 'UPDATE elggsites_entity set name='3124/944', description='', url='http://example.org/' where guid=1' 
   [07-Mar-2009 14:27:20] 1 times: 'UPDATE elggusers_entity set prev_last_action = last_action, last_action = 1236461868 where guid = 2' 
   [07-Mar-2009 14:27:20] DB Queries for this page: 56
   [07-Mar-2009 14:27:20] ***************************************************
   [07-Mar-2009 14:27:20] Page /action/admin/site/update_basic generated in 0.36997294426 seconds

What events are triggered on every page load?
---------------------------------------------

There are 4 :doc:`Elgg events </design/events>` that are triggered on every page load:

#. plugins_boot, system
#. init, system
#. ready, system
#. shutdown, system

The first three are triggered in ``Elgg\Application::bootCore``. **shutdown, system** is triggered in ``\Elgg\Application\ShutdownHandler`` after
the response has been sent to the client. They are all :doc:`documented </guides/events-list>`.

There are other events triggered by Elgg occasionally (such as when a user logs in).

Copy a plugin
-------------

There are many questions asked about how to copy a plugin. Let's say you want to copy the ``blog`` plugin in order to run one plugin called ``blog`` and another called ``poetry``. This is not difficult but it does require a lot of work. You would need to

- change the directory name
- change the names of every function (having two functions causes PHP to crash)
- change the name of every view (so as not to override the views on the original plugin)
- change any data model subtypes
- change the language file
- change anything else that was specific to the original plugin

.. note::

   If you are trying to clone the ``groups`` plugin, you will have the additional difficulty that the group plugin does not set a subtype.
