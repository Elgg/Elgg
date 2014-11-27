Security
========

Is upgrade.php a security concern?
----------------------------------

Upgrade.php is a file used to run code and database upgrades. It is in the root of the directory and doesn't require a logged in account to access. On a fully upgraded site, running the file will only reset the caches and exit, so this is not a security concern.

If you are still concerned, you can either delete, move, or change permissions on the file until you need to upgrade.

Should I delete install.php?
----------------------------

This file is used to install Elgg and doesn't need to be deleted. The file checks if Elgg is already installed and forwards the user to the front page if it is.

Filtering
---------

Filtering is used in Elgg to make `XSS`_ attacks more difficult. The purpose of the filtering is to remove Javascript and other dangerous input from users.

Filtering is performed through the function ``filter_tags()``. This function takes in a string and returns a filtered string. It triggers a *validate*, *input* :ref:`plugin hook <design/events#plugin-hooks>`. By default Elgg comes with the htmLawed filtering code as a plugin. Developers can drop in any additional or replacement filtering code as a plugin.

The ``filter_tags()`` function is called on any user input as long as the input is obtained through a call to ``get_input()``. If for some reason a developer did not want to perform the default filtering on some user input, the ``get_input()`` function has a parameter for turning off filtering.

.. _XSS: http://en.wikipedia.org/wiki/Cross-site_scripting