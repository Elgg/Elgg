Customizing the Home Page
#########################

Overwrite the default index page on your Elgg install.

start.php
=========

Register a function for the plugin hook called ``index, system`` that returns ``true``.
This tells Elgg to assume that another front page has been drawn so it doesn't display the default page.

Inside start.php you will need something like the following:

.. code:: php

    <?php

    function pluginname_init() {
        // Replace the default index page
        elgg_register_plugin_hook_handler('index', 'system', 'new_index');
    }

    function new_index() {
        return !include_once(dirname(__FILE__) . "/pages/index.php");
    }

    // register for the init, system event when our plugin start.php is loaded
    elgg_register_event_handler('init', 'system', 'pluginname_init');

pages/index.php
===============

Then implement the page handler script (/pluginname/pages/index.php) to generate the desired output.
Anything output from this script will become your new home page.