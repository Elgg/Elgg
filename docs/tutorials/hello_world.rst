Hello world
###########

This tutorial shows you how to add a new page and print the text "Hello world" on it.

In this tutorial we will pretend your site's URL is ``https://elgg.example.com``.

First, you need to:

 * :doc:`Install Elgg</intro/install>`
 * Create a file called ``start.php`` at the root of your app.

Copy this code into ``start.php``:

.. code-block:: php

    <?php

    elgg_register_event_handler('init', 'system', 'hello_world_init');
    
    function hello_world_init() {
    
    }

This piece of code tells Elgg that it should call the function
``hello_world_init()`` once the Elgg core system is initiated.

Registering a page handler
==========================

The next step is to register a page handler which has the purpose of handling
request that users make to the URL ``https://elgg.example.com/hello``.

Update the ``start.php`` to look like this:

.. code-block:: php

    <?php

    elgg_register_event_handler('init', 'system', 'hello_world_init');
    
    function hello_world_init() {
        elgg_register_page_handler('hello', 'hello_world_page_handler');
    }
    
    function hello_world_page_handler() {
    	echo elgg_view_resource('hello');
    }

The call to ``elgg_register_page_handler()`` tells Elgg that it should
call the function ``hello_world_page_handler()`` when user goes navigates to 
``https://elgg.example.com/hello/*``.

The ``hello_world_page_handler()`` passes off rendering the actual page to the
``resources/hello`` view. 

Create ``views/default/resources/hello.php`` with this content:

.. code-block:: php

    <?php

    $params = array(
        'title' => 'Hello world!',
        'content' => 'My first page!',
        'filter' => '',
    );

    $body = elgg_view_layout('content', $params);

    echo elgg_view_page('Hello', $body);


We give an array of parameters to the ``elgg_view_layout()`` function, including:

 - The title of the page
 - The contents of the page
 - Filter which is left empty because there's currently nothing to filter
 
This creates the basic layout for the page. The layout is then run through
``elgg_view_page()`` which assembles and outputs the full page.

You can now go to the address https://elgg.example.com/hello/ and you should see your new page!