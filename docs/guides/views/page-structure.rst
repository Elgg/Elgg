Page structure best practice
============================

Elgg pages have an overall pageshell, a main layout and several page elements. It's recommended to always use the ``default`` layout as 
all page elements can be controlled using that layout. 

If you're not using the ``default`` layout you can call
 
.. code-block:: php

	$layout_area = elgg_view_layout($layout_name, [
		'content' => $content,
		'section' => $section,
	]);

The different page elements are passed as an ``array`` in the second parameter. The array keys correspond to elements in the layout. 
The array values contain the html that should be displayed in those areas:

.. code-block:: php

	$layout_area = elgg_view_layout('default', [
		'content' => $content,
	]);
   
.. code-block:: php

	$layout_area = elgg_view_layout('default', [
		'content' => $content, 
		'sidebar' => $sidebar,
	]);

You can then, ultimately, pass this into the ``elgg_view_page`` function:

.. code-block:: php

	echo elgg_view_page($title, $layout_area);

If you're using the ``default`` layout you can also pass the array with page elements directly to ``elgg_view_page``:

.. code-block:: php

	echo elgg_view_page($title, [
   		'content' => $content,
   		'sidebar' => $sidebar,
	]);

You can control many of the page elements:

.. code-block:: php

	echo elgg_view_page('This is the browser title', [
		'title' => 'This is the page title',
		'content' => $content,
		'sidebar' => false, // no default sidebar
		'sidebar_alt' => $sidebar_alt, // show an alternate sidebar
	]);

.. seealso::

	Have a look at the ``page/layouts/default`` view to find out more information about the supported page elements
 