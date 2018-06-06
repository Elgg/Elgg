Plugin skeleton
===============

The following is the standard for plugin structure in Elgg as of Elgg 2.0.

Example Structure
-----------------

The following is an example of a plugin with standard structure. For further explanation of this structure, see the details in the following sections. Your plugin may not need all the files listed

The following files for plugin ``example`` would go in ``/mod/example/``

.. code-block:: text

    actions/
        example/
            action.php
            other_action.php
    classes/
        VendorNamespace/
            ExampleClass.php
    languages/
        en.php
    vendors/
        example_3rd_party_lib/
    views/
        default/
            example/
              component.css
              component.js
              component.png
            forms/
                example/
                    action.php
                    other_action.php
            object/
                example.php
                example/
                    context1.php
                    context2.php
            plugins/
                example/
                    settings.php
                    usersettings.php
            resources/
                example/
                    all.css
                    all.js
                    all.php
                    owner.css
                    owner.js
                    owner.php
            widgets/
                example_widget/
                    content.php
                    edit.php
    activate.php
    deactivate.php
    elgg-plugin.php
    CHANGES.txt
    COPYRIGHT.txt
    INSTALL.txt
    LICENSE.txt
    manifest.xml
    README.txt
    start.php
    composer.json

Required Files
--------------

Plugins **must** provide a ``manifest.xml`` file in the plugin root in order to be recognized by Elgg.

Therefore the following is the minimally compliant structure:

.. code-block:: text

    mod/example/
        manifest.xml

Actions
-------

Plugins *should* place scripts for actions an ``actions/`` directory, and furthermore *should* use the name of the action to determine the location within that directory.

For example, the action ``my/example/action`` would go in ``my_plugin/actions/my/example/action.php``. This makes it very obvious which script is associated with which action.

Similarly, the body of the form that submits to this action should be located in ``forms/my/example/action.php``. Not only does this make the connection b/w action handler, form code, and action name obvious, but it allows you to use the ``elgg_view_form()`` function easily.

Text Files
----------

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

To render full pages, plugins should use **resource views** (which have names beginning with ``resources/``). This allows other plugins
to easily replace functionality via the view system.

.. note::

    The reason we encourage this structure is
    
    - To form a logical relationship between urls and scripts, so that people examining the code can have an idea of what it does just by examining the structure.
    - To clean up the root plugin directory, which historically has quickly gotten cluttered with the page handling scripts.

Classes
-------

Elgg provides `PSR-0 <http://www.php-fig.org/psr/psr-0/>`_ autoloading out of every active plugin's ``classes/`` directory.

You're encouraged to follow the `PHP-FIG <http://www.php-fig.org/>`_ standards when writing your classes.

.. note::
 
	Files with a ".class.php" extension will **not** be recognized by Elgg.

Vendors
-------

Included third-party libraries of any kind *should* be included in the ``vendors/`` folder in the plugin root. Though this folder has no special significance to the Elgg engine, this has historically been the location where Elgg core stores its third-party libraries, so we encourage the same format for the sake of consistency and familiarity.

Views
-----

In order to override core views, a plugin's views can be placed in ``views/``, or an ``elgg-plugin.php`` config file can be used for more detailed file/path mapping. See :doc:`/guides/views`.

Javascript and CSS will live in the views system. See :doc:`/guides/javascript`.

activate.php and deactivate.php
-------------------------------

The ``activate.php`` and ``deactivate.php`` files contain procedural code that will run respectively upon plugin activation or deactivation. Use these files to perform one-time events such as registering a persistent admin notice, registering subtypes, or performing garbage collection when deactivated.
