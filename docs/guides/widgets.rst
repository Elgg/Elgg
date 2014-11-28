Widgets
=======

Widgets are content areas that users can drag around their page to customize the layout. They can typically be customized by their owner to show more/less content and determine who sees the widget. By default Elgg provides plugins for customizing the profile page and dashboard via widgets.

TODO: Screenshot

.. contents:: Contents
   :local:
   :depth: 2

Structure
---------

To create a widget, create two views:

* ``widgets/widget/edit``
* ``widgets/widget/content``

``content.php`` is responsible for all the content that will output within the widget. The ``edit.php`` file contains any extra edit functions you wish to present to the user. You do not need to add access level as this comes as part of the widget framework.

.. note::
   
   Using HTML checkboxes to set widget flags is problematic because if unchecked, the checkbox input is omitted from form submission. The effect is that you can only set and not clear flags. The "input/checkboxes" view will not work properly in a widget's edit panel.

Initialise the widget
---------------------

Once you have created your edit and view pages, you need to initialize the plugin widget. This is done within the plugins ``init()`` function.

.. code:: php

    // Add generic new file widget
    add_widget_type('filerepo', elgg_echo("file:widget"), elgg_echo("file:widget:description"));

.. note::

   It is possible to add multiple widgets for a plugin. You just initialize as many widget directories as you need.

.. code:: php

    // Add generic new file widget
    add_widget_type('filerepo', elgg_echo("file:widget"), elgg_echo("file:widget:description"));

    // Add a second file widget
    add_widget_type('filerepo2', elgg_echo("file:widget2"), elgg_echo("file:widget:description2"));

    // Add a third file widget
    add_widget_type('filerepo3', elgg_echo("file:widget3"), elgg_echo("file:widget:description3"));

Multiple widgets
----------------

Make sure you have the corrosponding directories within your plugin
views structure:

.. code::

    'Plugin'
        /views
            /default
                /widgets
                   /filerepo
                      /edit.php
                      /contents.php
                   /filerepo2
                      /edit.php
                      /contents.php
                   /filerepo3
                      /edit.php
                      /contents.php

Elgg 1.8: Default widgets
-------------------------

If your plugin uses the widget canvas, you can register default widget support with Elgg core, which will handle everything else.

To announce default widget support in your plugin, register for the ``get_list, default_widgets`` plugin hook:

.. code:: php

    elgg_register_plugin_hook_handler('get_list', 'default_widgets', 'my_plugin_default_widgets');

In the plugin hook handler, push an array into the return value defining your default widget support and when to create default widgets. Arrays require the following keys to be defined:

-  name - The name of the widgets page. This is displayed on the tab in the admin interface.
-  widget\_context - The context the widgets page is called from. (If not explicitly set, this is your plugin's id.)
-  widget\_columns - How many columns the widgets page will use.
-  event - The Elgg event to create new widgets for. This is usually ``create``.
-  entity\_type - The entity type to create new widgets for.
-  entity\_subtype - The entity subtype to create new widgets for. The can be ELGG\_ENTITIES\_ANY\_VALUE to create for all entity types.

When an object triggers an event that matches the event, entity\_type, and entity\_subtype parameters passed, Elgg core will look for default widgets that match the widget\_context and will copy them to that object's owner\_guid and container\_guid. All widget settings will also be copied.

.. code:: php

    function my_plugin_default_widgets_hook($hook, $type, $return, $params) {
        $return[] = array(
            'name' => elgg_echo('my_plugin'),
            'widget_context' => 'my_plugin',
            'widget_columns' => 3,

            'event' => 'create',
            'entity_type' => 'user',
            'entity_subtype' => ELGG_ENTITIES_ANY_VALUE,
        );

        return $return;
    }

Simple Example
--------------

Here is a simple Flickr widget that uses Flickr's JSON output.

Widget edit page:

.. code:: php

        <p>
        <?php echo elgg_echo("flickr:id"); ?>
            <input type="text" name="params[title]" value="<?php echo htmlentities($vars['entity']->title); ?>" />
        </p>
        
        <p><?php echo elgg_echo("flickr:whatisid"); ?></p>

Widget view page:

.. code:: php

    <?php

        //some required params
        $flickr_id = $vars['entity']->title;
         
        // if the flickr id is empty, then do not show any photos
        if($flickr_id){
         
    ?>
    <!-- this script uses the jquery cycle plugin -->
    <script type="text/javascript" src="<?php echo $vars['url']; ?>mod/flickr/views/default/flickr/js/cycle.js"></script>

    <!-- the Flickr JSON script -->
    <script>
        $.getJSON("http://api.flickr.com/services/feeds/photos_public.gne?id=
    <?php echo $flickr_id;?>&lang=en-us&format=json&jsoncallback=?", function(data){
            $.each(data.items, function(i,item){
                $("<img/>").attr("src", item.media.m).appendTo("#images")
                .wrap("<a href='" + item.link + "'></a>");
        });
      
        $('#images').cycle({
            fx:     'fade',
            speed:    'slow',
            timeout:  0,
            next:   '#next',
            prev:   '#prev'
        });
      
    });

    </script>

    <!-- some css for display -->
    <style type="text/css">
        #images {
            height: 180px;
            width: 100%;
            padding:0;
            margin:0 0 10px 0;
            overflow: hidden;
         }
          #images img {
              border:none;
          }
    </style>

    <!-- div where the images will display -->
    <div id="title"></div>
    <div id="images" align="center"></div>

    <!-- next and prev nav -->
    <div class="flickrNav" align="center">
        <a id="prev" href="#">&laquo; Prev</a> <a id="next" href="#">Next &raquo;</a>
    </div>

    <?php

        }else{
            
            //this should go through elgg_echo() - it was taken out for this demo
            echo "You have not yet entered your Flickr ID which is required to display your photos.";
            
        }
    ?>

How to restrict where widgets can be used
-----------------------------------------

Any plugin that has a widget must register that widget with Elgg. The widget can specify the context that it can be used in (all, just profile, just dashboard, etc.). If you want to change where your users can use a widget, you can make a quick edit to the plugin's source.

Find where the plugin registers the widget
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The function you are looking for is ``add_widget_type()``. It is typically used in an init function in ``start.php``. You should be able to go to ``/mod/<plugin name>/``, open ``start.php`` in a text editor, and find the string ``add_widget_type``.

Changing the function's parameters
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Let's use the friends plugin as an example. We want to restrict it so that it can only be used on a user's profile. Currently, the function call looks like this:

.. warning::

   Keep in mind :doc:`dont-modify-core`

.. code:: php

   add_widget_type('friends',elgg_echo("friends"),elgg_echo('friends:widget:description'));

To restrict it to the profile, change it to this:

.. code:: php

   add_widget_type('friends',elgg_echo("friends"),elgg_echo('friends:widget:description'), "profile");
   
Notice that the context was not specified originally (there were only 3 parameters and we added a 4th). That means it defaulted to the "all" context. Besides "all" and "profile", the only other context available in default Elgg is "dashboard".
