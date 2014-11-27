Views
#####

.. contents:: Contents
   :local:
   :depth: 1

Introduction
============

Elgg follows a MVC pattern and Views are the V in MVC. Views are responsible for creating the output. Generally, this will be HTML sent to a web browser, but it could also be RSS, JSON or any number of other data formats.

The Views system handles everything from the layout of pages and chunks of presentation output (like a footer or a toolbar) down to individual links and form inputs. It also allows for advanced features like automatic RSS generation, a swift-to-develop mobile interface, and the alternative interfaces suggested below.

Using views
===========

At their most basic level, the default views are just PHP files with snippets of html. For example:

.. code-block:: html

    <h1>Hello, World!</h1>

Assuming this view is located at ``/views/default/hello.php``, we could output it like so:

.. code-block:: php

    echo elgg_view('hello');

For your convenience, Elgg comes with quite a lot of views by default. In order to keep things manageable, they are organized into subdirectories. Elgg handles this situation quite nicely. For example, our simple view might live in ``/views/default/hello/world.php``, in which case it would be called like so:

.. code-block:: php

    echo elgg_view('hello/world');

Well that's easy enough to remember! The name of the view simply reflects the location of the view in the views directory.

Views as templates
==================

Views would be pretty useless if they could only contain static information. Fortunately, you can pass arbitrary data to a view via the ``$vars`` array. Our ``hello/world`` view might be modified to accept a variable like so:

.. code-block:: php

    <h1>Hello, <?php echo $vars['name']; ?>!</h1>

In this case, we can pass an arbitrary name parameter to the view like so:

.. code-block:: php

    echo elgg_view('hello/world', array('name' => 'World'));

which would produce the following output:

.. code-block:: html

    <h1>Hello, World!</h1>

Overriding views in plugins
===========================

You may want to change the output or rendering strategy of a view that Elgg provides by default. Fortunately, Elgg's plugin system makes this easy. Each plugin may have its own ``/views`` directory, with its own viewtypes. Views in plugin directories always override views in the core directory, so this allows you to customize the behavior of any number of views without touching Elgg core.

For example, if we wanted to customize the ``hello/world`` view to use an ``h2`` instead of an ``h1``, we could create a file at ``/mod/example/views/default/hello/world.php`` like this:

.. code-block:: php

	<h2>Hello, <?php echo $vars['name']; ?></h2>

While it is **not recommended**, one *could* alternatively force the location of a view using the ``set_view_location`` function:

.. code-block:: php

	set_view_location($view_name, $full_path_to_view_file);

Again, the best way to override views is to place them in the appropriate place in the views hierarchy.

.. note::

	When considering long-term maintenance, overriding views in the core and bundled plugins has a cost: Upgrades may bring changes in views, and if you have overridden them, you will not get those changes. You may want to use :ref:`post processing <guides/views#post-processing-views>` if the change you're making can be easily made with string replacement methods.

.. note::

	Elgg caches view locations. This means that you should disable the system cache while working with views. When you install the changes to a production environment you mush flush the caches.

.. _guides/views#viewtypes:

Viewtypes
=========

You might be wondering, "what's with the 'default' in the directory structure? Why don't we just put the ``hello/world`` view at ``/views/hello/world.php``?".

Great question.

This subdirectory (the one under ``/views``) determines the *viewtype* of the views below it. It's possible that you might want your Elgg site to have several sets of interface pages. For example:

* Standard HTML for desktop browsing (This is the default view)
* HTML optimized for Mobile devices (iPhone, Android, Blackberry, etc.)
* HTML optimized Tablet devices (iPad, etc.)
* RSS
* Atom
* JSON
* etc...

In Elgg, one set of these interface pages is called a *viewtype*. You can force Elgg to use a particular viewtype to render the page simply by setting the ``$view`` input variable. For example, to get an RSS version of the home page, you would access ``http://localhost/elgg/?view=rss``.

You could also write a plugin to set this automatically using the ``set_input()`` function. For example, your plugin might detect that the page was accessed with an iPhone's browser string, and set the viewtype to *handheld* by calling:

.. code-block:: php

	set_input('view', 'handheld');

The plugin would presumably also supply a set of views optimized for handheld devices.

Extending views
===============

There may be other situations in which you don't want to override the whole view, you just want to add some more content to the end of it. In Elgg this is called *extending* a view.

For example, instead of overriding the ``hello/world`` view, we could extend it like so:

.. code-block:: php

	elgg_extend_view('hello/world', 'hello/greeting');

If the contents of ``/views/default/hello/greeting.php`` is:

.. code-block:: php

	<h2>How are you today?</h2>

Then every time we call ``elgg_view('hello/world');``, we'll get:

.. code-block:: html

	<h1>Hello, World!</h1>
	<h2>How are you today?</h2>

You can also optionally prepend views as well by passing a value to the 3rd parameter that is less than 500:

.. code-block:: php

	//appends 'hello/greeting' to every occurrence of 'hello/world'
	elgg_extend_view('hello/world', 'hello/greeting');

	//prepends 'hello/greeting' to every occurrence of 'hello/world'
	elgg_extend_view('hello/world', 'hello/greeting', 450);

Note that if you extend the core css view like this:

.. code-block:: php

	elgg_extend_view('css', 'custom/css');

You **must** do so within code that is executed by engine/start.php (normally this would mean your plugin's init code).  Because the core css view is loaded separately via a ``<link>`` tag, any extensions you add will not have the same context as the rest of your page.

.. _guides/views#post-processing-views:

Post processing views
=====================

Sometimes it is preferable to process or rewrite the output of a view instead of overriding it.

The output of each view is run through the :ref:`plugin hook <guides/hooks-list#views>` ``[view, view_name]`` before being returned by ``elgg_view()``. Each registered handler function is passed these arguments:

* ``$hook`` - the string ``"view"``
* ``$type`` - the view name being rendered (the first argument passed to ``elgg_view()``)
* ``$returnvalue`` - the rendered output of the view (or the return value of the last handler)
* ``$params`` - an array containing the key ``viewtype`` with value being the :ref:`viewtype <guides/views#viewtypes>` being rendered

To alter the view output, the handler just needs to alter ``$returnvalue`` and return a new string.

Post pocessing view example
===========================

Here we'll eliminate breadcrumbs that don't have at least one link.

.. code-block:: php

	// inside myplugin_init()
	elgg_register_plugin_hook_handler('view', 'navigation/breadcrumbs', 'myplugin_alter_breadcrumb');

	function myplugin_alter_breadcrumb($hook, $type, $returnvalue, $params) {
	    // we only want to alter when viewtype is "default"
	    if ($params['viewtype'] !== 'default') {
	        return $returnvalue;
	    }
	    // output nothing if the content doesn't have a single link
	    if (false === strpos($returnvalue, '<a ')) {
	        return '';
	    }
	}

Displaying entities
===================

If you don't know what an entity is, :doc:`check this page out first </design/database>`.

The following code will automatically display the entity in ``$entity``:

.. code-block:: php

	echo elgg_view_entity($entity);

As you'll know from the data model introduction, all entities have a *type* (object, site, user or group), and optionally a subtype (which could be anything - 'blog', 'forumpost', 'banana'). ``elgg_view_entity`` will automatically look for a view called ``type/subtype``; if there's no subtype, it will look for ``type/type``. Failing that, before it gives up completely it tries ``type/default``. (RSS feeds in Elgg generally work by outputting the ``object/default`` view in the 'rss' viewtype.)

So for example, the view to display a blogpost might be ``object/blog``. The view to display a user is ``user/user``.

Full and partial entity views
=============================

``elgg_view_entity`` actually has a number of parameters, although only the very first one is required. The first three are:

* ``$entity`` - The entity to display
* ``$viewtype`` - The viewtype to display in (defaults to the one we're currently in, but it can be forced - eg to display a snippet of RSS within an HTML page)
* ``$full_view`` - Whether to display a *full* version of the entity. (Defaults to false.)

This last parameter is passed to the view as ``$vars['full_view']``. It's up to you what you do with it; the usual behaviour is to only display comments and similar information if this is set to true.

.. _guides/views#listing-entities:

Listing entities
================

This is then used in the provided listing functions. To automatically display a list of blog posts (:doc:`see the full tutorial </tutorials/blog>`), you can call:

.. code-block:: php

	echo elgg_list_entities(array(
	    'type' => 'object',
	    'subtype' => 'blog',
	));

This function checks to see if there are any entities; if there are, it first displays the ``navigation/pagination`` view in order to display a way to move from page to page. It then repeatedly calls ``elgg_view_entity`` on each entity, before returning the result.

Because it does this, Elgg knows that it can automatically supply an RSS feed - it extends the ``metatags`` view (which is called by the header) in order to provide RSS autodiscovery, which is why you can see the orange RSS icon on those pages.

See also :doc:`check this page out first </design/database>`.

Using a different templating system
===================================

You can write your own templating system if you want to.

Before going through the motions of drawing views, Elgg checks the ``$CONFIG->template_handler`` variable to see if it contains the name of a callable function. If it does, the function will be passed the view name and template vars, and the return value of this function will be returned instead of the standard output:

.. code-block:: php

	return $template_handler($view, $vars);

Related
=======

.. toctree::
   :maxdepth: 1
   
   views/page-structure
   views/simplecache
	