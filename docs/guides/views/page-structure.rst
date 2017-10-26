Page structure best practice
============================

Elgg pages have an overall pageshell and a main content area. In Elgg 1.0+, we've marked out a space \"the canvas\" for items to write to the page. This means the user always has a very consistent experience, while giving maximum flexibility to plugin authors for laying out their functionality.

Think of the canvas area as a big rectangle that you can do what you like in. We've created a couple of standard canvases for you: 

- one column
- two column
- content
- widgets

are the main ones. You can access these with the function:

.. code-block:: php

   $canvas_area = elgg_view_layout($canvas_name, array(
     'content' => $content,
     'section' => $section
   ));

The content sections are passed as an ``array`` in the second parameter. The array keys correspond to sections in the layout, the choice of layout will determine which sections to pass. The array values contain the html that should be displayed in those areas. Examples of two common layouts:

.. code-block:: php

   $canvas_area = elgg_view_layout('one_column', array(
     'content' => $content
   ));
   
.. code-block:: php

   $canvas_area = elgg_view_layout('one_sidebar', array(
     'content' => $content, 
     'sidebar' => $sidebar
   ));

You can then, ultimately, pass this into the ``elgg_view_page`` function:

.. code-block:: php

   echo elgg_view_page($title, $canvas_area);

You may also have noticed that we've started including a standard title area at the top of each plugin page (or at least, most plugin pages). This is created using the following wrapper function, and should usually be included at the top of the plugin content:

.. code-block:: php

   $start_of_plugin_content = elgg_view_title($title_text);

This will also display any submenu items that exist (unless you set the second, optional parameter to false). So how do you add submenu items?

In your plugin_init function, include the following call:

.. code-block:: php

   if (elgg_get_context() == "your_plugin") {
      // add a site navigation item
      $item = new ElggMenuItem('identifier', elgg_echo('your_plugin:link'), $url);
      elgg_register_menu_item('page', $item);
   }

The submenu will then automatically display when your page is rendered. The 'identifier' is a machine name for the link, it should be unique per menu.