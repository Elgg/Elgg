Plugin skeleton
===============

The following is the standard for plugin structure in Elgg as of Elgg 1.8. Plugins written for Elgg 1.7 and down are strongly encouraged to use this structure as well, though some of the benefits are not as apparent as when used in 1.8.

Example Structure
-----------------

The following is an example of a plugin with standard structure. For further explanation of this structure, see the details in the following sections. Your plugin may not need all the files listed

The following files for plugin ``example`` would go in ``/mod/example/``

.. code::

    actions/
        example/
            action.php
        other_action.php
    classes/
        ExampleClass.php
    graphics/
        example.png
    js/
        example.js
    languages/
        en.php
    lib/
        example.php
    pages/
        example/
            all.php
            owner.php
    vendors/
        example_3rd_party_lib/
    views/
        default/
            example/
                css.php
            forms/
                example/
                    action.php
                    other_action.php
            js/
                example.php
            object/
                 example.php
                example/
                    context1.php
                    context2.php
            plugins/
                example/
                    settings.php
                    usersettings.php
            widgets/
                example_widget/
                    content.php
                    edit.php
    activate.php
    deactivate.php
    CHANGES.txt
    COPYRIGHT.txt
    INSTALL.txt
    LICENSE.txt
    manifest.xml
    README.txt
    start.php

Required Files
--------------

Plugins **must** provide a ``start.php`` and ``manifest.xml`` file in the plugin root in order to be recognized by Elgg.

Therefore the following is the minimally compliant structure:

.. code::

    mod/example/
        start.php
        manifest.xml

Actions
-------

Plugins *should* place scripts for actions an ``actions/`` directory, and furthermore *should* use the name of the action to determine the location within that directory.

For example, the action ``my/example/action`` would go in ``my_plugin/actions/my/example/action.php``. This makes it very obvious which script is associated with which action.

Similarly, the body of the form that submits to this action should be located in ``forms/my/example/action.php``. Not only does this make the connection b/w action handler, form code, and action name obvious, but it allows you to use the new (as of Elgg 1.8) ``elgg_view_form()`` function easily.

Text Files
-----------

Plugins *may* provide various \*.txt as additional documentation for the plugin. These files **must** be in Markdown syntax and will generate links on the plugin management sections.

README.txt 
    *should* provide additional information about the plugin of an unspecified nature 

COPYRIGHT.txt 
    If included, **must** provide an explanation of the plugin's copyright, besides what is included in ``manifest.xml`` 

LICENSE.txt 
    If included, **must** provide the text of the license that the plugin is released under. 

INSTALL.txt 
    If included, **must** provide additional instructions for installing the plugin if the process is sufficiently complicated (e.g. if it requires installing third party libraries on the host machine, or requires acquiring an API key from a third party). 

CHANGES.txt 
    If included, **must** provide a list of changes for their plugin, grouped by version number, with the most recent version at the top. 

Plugins *may* include additional \*.txt files besides these, but no interface is given for reading them.

Pages
-----

Plugins *should* put page-generating scripts in a ``pages/`` directory inside their plugin root. Furthermore, plugins *should* put page-generating scripts under a directory named for their handler. For example, the script for page ``yoursite.com/my_handler/view/1234`` *should* be located at ``mod/my_plugin/pages/my_handler/view.php``.

In the past, these scripts were included directly in the plugin root. Plugins *should not* do this anymore, and if any core plugins are found to do this, that is a bug if not present solely for the sake of backwards compatibility.

.. note:: 

    The reason we encourage this structure is
    
    - To form a logical relationship between urls and scripts, so that people examining the code can have an idea of what it does just by examining the structure.
    - To clean up the root plugin directory, which historically has quickly gotten cluttered with the page handling scripts.
    

Classes
-------

All classes that your plugin defines *should* be included in a ``classes/`` directory. This directory has special meaning to Elgg. Classes placed in this directory are autoloaded on demand, and do not need to be included explicitly.

.. warning::

    Each file **must** have exactly one class defined inside it.
    The file name **must** match the name of the one class that the file defines (except for the ".php" suffix). 

.. note::
 
	Files with a ".class.php" extension will **not** be recognized by Elgg.

Vendors
-------

Included third-party libraries of any kind *should* be included in the ``vendors/`` folder in the plugin root. Though this folder has no special significance to the Elgg engine, this has historically been the location where Elgg core stores its third-party libraries, so we encourage the same format for the sake of consistency and familiarity.

Lib
---

Procedural code defined by your plugin *should* be placed in the `lib/` directory. Though this folder has no special significance to the Elgg engine, this has historically been the location where Elgg core stores its procedural code, so we encourage the same format for the sake of consistency and familiarity.

Views
-----

In order to override core views, a plugin's views **must** be placed in a ``views/``. This directory has special meaning to Elgg as views defined here automatically override Elgg core's version of those views. For more info, see :doc:`/guides/views`.

Javascript
----------

Javascript that will be included on every page *should* be put in the ``plugin/js`` view and your plugin *should* extend ``js/elgg`` with this view. Javascript that does not need to be included on every page *should* be put in a static javascript file under the ``js/`` directory. For more information on Javascript in Elgg, see :doc:`/guides/javascript`.

activate.php and deactivate.php
-------------------------------

The ``activate.php`` and ``deactivate.php`` files contain procedural code that will run respectively upon plugin activation or deactivation. Use these files to perform one-time events such as registering a persistent admin notice, registering subtypes, or performing garbage collection when deactivated.
