Basic Widget
============

Create a widget that will display “Hello, World!” and optionally any text the user wants.

In Elgg, widgets are those components that you can drag onto your profile or admin dashboard.

This tutorial assumes you are familiar with basic Elgg concepts such as:

 * :doc:`/guides/views`
 * :doc:`/admin/plugins`

You should review those if you get confused along the way.

.. contents:: Contents
   :local:
   :depth: 1

Registering your plugin
-----------------------

Plugins are always placed in the ``/mod`` directory.
Create a subdirectory there called ``hello``.
This will be the name of your plugin
and will show up in the Plugins Administration section of Elgg by this name.

In ``/mod/hello``, create an empty file called ``start.php``.
If this file exists, Elgg will load your plugin.
Otherwise, you will see a misconfigured plugin error.
Go to the admin section of your Elgg install and enable your plugin.
Click on the “more info” link under your plugin name.
You will notice that nothing happens.

 * Copy the ``manifest.xml`` file from one of the plugins in your elgg install into ``/mod/hello``.
 * Update its values so you are listed as the author and change the description to describe this new plugin.
 * Reload the Tools Administration page in your browser and check “more info” again.
 * It will now display the information that you've entered.

Adding the widget view code
---------------------------

Elgg automatically scans particular directories under plugins looking for particular files.
:doc:`/guides/views` make it easy to add your display code or do other things like override default Elgg behavior.
For now, we will just be adding the view code for your widget.
Create a file at ``/mod/hello/views/default/widgets/helloworld/content.php``.
“helloworld” will be the name of your widget within the hello plugin.
In this file add the code:

.. code:: php

    <?php

    echo "Hello, world!";

This will add these words to the widget canvas when it is drawn.
Elgg takes care of loading the widget.

Registering your widget
-----------------------

Elgg needs to be told explicitly that the plugin contains a widget
so that it will scan the widget views directory.
This is done by calling the elgg\_register\_widget\_type() function.
Edit ``/mod/hello/start.php``. In it add these lines:

.. code:: php

    <?php
    
    function hello_init() {
        elgg_register_widget_type('helloworld', 'Hello, world!', 'The "Hello, world!" widget');
    }
        
    elgg_register_event_handler('init', 'system', 'hello_init');

Now go to your profile page using a web browser and add the “hello, world” widget.
It should display “Hello, world!”.

.. note::

   For real widgets, it is always a good idea to support :doc:`/guides/i18n`.

Allow user customization
------------------------

Click on the edit link on the toolbar of the widget that you've created.
You will notice that the only control it gives you by default is over
access (over who can see the widget).

Suppose you want to allow the user to control what greeting is displayed in the widget.
Just as Elgg automatically loads ``content.php`` when viewing a widget,
it loads ``edit.php`` when a user attempts to edit a widget.
In ``/mod/hello/views/default/widgets/helloworld/``, create a file named ``edit.php``.
In this file, add the following code:

.. code:: php

    <div>
        <label>Message:</label>
        <?php
            //This is an instance of the ElggWidget class that represents our widget.
            $widget = $vars['entity'];
    
            // Give the user a plain text box to input a message
            echo elgg_view('input/text', array(
                'name' => 'params[message]',
                'value' => $widget->message,
                'class' => 'hello-input-text',
            ));
        ?>
    </div>

Notice the relationship between the values passed to the 'name' and the
'value' fields of input/text.
The name of the input text box is ``params[message]``
because Elgg will automatically handle widget variables put in the array ``params``.
The actual php variable name will be ``message``.
If we wanted to use the field ``greeting`` instead of ``message``
we would pass the values ``params[greeting]`` and ``$widget->greeting`` respectively.

The reason we set the 'value' option of the array is so that the edit
view remembers what the user typed in the previous time he changed the
value of his message text.

Now to display the user's message we need to modify content.php to use this *message* variable.
Edit content.php and change it to:

.. code:: php

    <?php
    
    $widget = $vars['entity'];
    
    // Always use the corresponding output/* view for security!
    echo elgg_view('output/text', array('value' => $widget->message));

You should now be able to enter a message in the text box and see it appear in the widget.
