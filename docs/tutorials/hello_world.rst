Hello world
###########

This tutorial shows you how to create a new plugin that consists of a new page with the text "Hello world" on it.

Before anything else, you need to :doc:`install Elgg</intro/install>`.

In this tutorial we will pretend your site's URL is ``https://elgg.example.com``.

First, create a directory that will contain the plugin's files. It should be located under the ``mod/`` directory which is located in your Elgg installation directory. So in this case, create ``mod/hello/``.

Manifest file
=============

Elgg requires that your plugin has a manifest file that contains information about the plugin. Therefore, in the directory you just created, create a file called ``manifest.xml`` and copy this code into it:

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <plugin_manifest xmlns="http://www.elgg.org/plugin_manifest/1.8">
        <name>Hello world</name>
        <id>hello</id>
        <author>Your Name Here</author>
        <version>0.1</version>
        <description>Hello world, testing.</description>
        <requires>
            <type>elgg_release</type>
            <version>2.0</version>
        </requires>
    </plugin_manifest>

This is the minimum amount of information in a manifest file:

- ``<name>`` is the display name of the plugin
- ``<id>`` must be the same as the directory you just created
- ``<requires>`` must include which version of Elgg your plugin requires
- ``<author>``, ``<version>`` and ``<description>`` should have some appropriate values but can be filled freely

Initializer
===========

Next, create ``start.php`` in the ``mod/hello/`` directory and copy this code into it:

.. code-block:: php

    <?php

    elgg_register_event_handler('init', 'system', 'hello_world_init');
    
    function hello_world_init() {
    
    }

The above code tells Elgg that it should call the function
``hello_world_init()`` once the Elgg core system is initiated.

Registering a route
===================

The next step is to register a route which has the purpose of handling
request that users make to the URL ``https://elgg.example.com/hello``.

Update ``elgg-plugin.php`` to look like this:

.. code-block:: php

	<?php

	return [
	    'routes' => [
			'default:hello' => [
				'path' => '/hello',
				'resource' => 'hello',
			],
		],
	];

This registration tells Elgg that it should call the resource view ``hello`` when a user navigates to 
``https://elgg.example.com/hello``.

View file
=========

Create ``mod/hello/views/default/resources/hello.php`` with this content:

.. code-block:: php

    <?php

    echo elgg_view_page('Hello', [
    	'title' => 'Hello world!',
        'content' => 'My first page!',
    ]);


The code creates an array of parameters to be given to the ``elgg_view_layout()`` function, including:

 - The title of the page
 - The contents of the page
 - Filter which is left empty because there's currently nothing to filter
 
This creates the basic layout for the page. The layout is then run through
``elgg_view_page()`` which assembles and outputs the full page.

Last step
=========

Finally, activate the plugin through your Elgg administrator page: ``https://elgg.example.com/admin/plugins`` (the new plugin appears at the bottom).

You can now go to the address ``https://elgg.example.com/hello/`` and you should see your new page!
