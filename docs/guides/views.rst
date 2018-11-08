Views
#####

.. contents:: Contents
   :local:
   :depth: 2

Introduction
============

Views are responsible for creating output. They handle everything from:

 * the layout of pages
 * chunks of presentation output (like a footer or a toolbar)
 * individual links and form inputs.
 * the images, js, and css needed by your web page

Using views
===========

At their most basic level, the default views are just PHP files with snippets of html:

.. code-block:: html

    <h1>Hello, World!</h1>

Assuming this view is located at ``/views/default/hello.php``, we could output it like so:

.. code-block:: php

    echo elgg_view('hello');

For your convenience, Elgg comes with quite a lot of views by default.
In order to keep things manageable, they are organized into subdirectories.
Elgg handles this situation quite nicely. For example, our simple view might live in
``/views/default/hello/world.php``, in which case it would be called like so:

.. code-block:: php

    echo elgg_view('hello/world');

The name of the view simply reflects the location of the view in the views directory.

Views as templates
==================

You can pass arbitrary data to a view via the ``$vars`` array.
Our ``hello/world`` view might be modified to accept a variable like so:

.. code-block:: html+php

    <h1>Hello, <?= $vars['name']; ?>!</h1>

In this case, we can pass an arbitrary name parameter to the view like so:

.. code-block:: php
    
    echo elgg_view('hello/world', ['name' => 'World']);

which would produce the following output:

.. code-block:: html

    <h1>Hello, World!</h1>

.. warning::

    Views don't do any kind of automatic output sanitization by default.
    You are responsible for doing the correct sanitization yourself
    to prevent XSS attacks and the like.

Views as cacheable assets
=========================

As mentioned before, views can contain JS, CSS, or even images.

Asset views must meet certain requirements:

 * They *must not* take any ``$vars`` parameters
 * They *must not* change their output based on global state like

   * who is logged in
   * the time of day

 * They *must* contain a valid file extension
 
   * Bad: ``my/cool/template``
   * Good: ``my/cool/template.html``

For example, suppose you wanted to load some CSS on a page.
You could define a view ``mystyles.css``, which would look like so:

.. code-block:: css

    /* /views/default/mystyles.css */
    .mystyles-foo {
      background: red;
    }

.. note::

    Leave off the trailing ".php" from the filename and Elgg will automatically
    recognize the view as cacheable.

To get a URL to this file, you would use ``elgg_get_simplecache_url``:

.. code-block:: php

    // Returns "https://mysite.com/.../289124335/default/mystyles.css
    elgg_get_simplecache_url('mystyles.css'); 

Elgg automatically adds the magic numbers you see there for cache-busting and
sets long-term expires headers on the returned file.

.. warning::

    Elgg may decide to change the location or structure of the returned URL in a
    future release for whatever reason, and the cache-busting numbers change
    every time you flush Elgg's caches, so the exact URL is not stable by design.
    
    With that in mind, here's a couple anti-patterns to avoid:
    
     * Don't rely on the exact structure/location of this URL
     * Don't try to generate the URLs yourself
     * Don't store the returned URLs in a database

In your plugin's init function, register the css file:

.. code-block:: php

    elgg_register_css('mystyles', elgg_get_simplecache_url('mystyles.css'));

Then on the page you want to load the css, call:

.. code-block:: php

    elgg_load_css('mystyles');

.. _guides/views#viewtypes:


Views and third-party assets
============================

The best way to serve third-party assets is through views. However, instead of manually copy/pasting
the assets into the right location in ``/views/*``, you can map the assets into the views system via
the ``"views"`` key in your plugin's ``elgg-plugin.php`` config file.

The views value must be a 2 dimensional array. The first level maps a viewtype to a list of view
mappings. The secondary lists map view names to file paths, either absolute or relative to the Elgg root directory.

If you check your assets into source control, point to them like this:

.. code-block:: php

    <?php // mod/example/elgg-plugin.php
    return [
        // view mappings
        'views' => [
            // viewtype
            'default' => [
                // view => /path/from/filesystem/root
                'js/jquery-ui.js' => __DIR__ . '/bower_components/jquery-ui/jquery-ui.min.js',
            ],
        ],
    ];

To point to assets installed with composer, use install-root-relative paths by leaving off the leading slash:

.. code-block:: php

    <?php // mod/example/elgg-plugin.php
    return [
        'views' => [
            'default' => [
                // view => path/from/install/root
                'js/jquery-ui.js' => 'vendor/bower-asset/jquery-ui/jquery-ui.min.js',
            ],
        ],
    ];
    
Elgg core uses this feature extensively, though the value is returned directly from ``/engine/views.php``.

.. note::

    You don't have to use Bower, Composer Asset Plugin, or any other script for
    managing your plugin's assets, but we highly recommend using a package manager
    of some kind because it makes upgrading so much easier.

Specifying additional views directories
---------------------------------------

In ``elgg-plugin.php`` you can also specify directories to be scanned for views. Just provide
a view name prefix ending with ``/`` and a directory path (like above).

.. code-block:: php

    <?php // mod/file/elgg-plugin.php
    return [
        'views' => [
            'default' => [
                'file/icon/' => __DIR__ . '/graphics/icons',
            ],
        ],
    ];

With the above, files found within the ``icons`` folder will be interpreted as views. E.g. the view
``file/icon/general.gif`` will be created and mapped to ``mod/file/graphics/icons/general.gif``.

.. note::

    This is a fully recursive scan. All files found will be brought into the views system.

Multiple paths can share the same prefix, just give an array of paths:

.. code-block:: php

    <?php // mod/file/elgg-plugin.php
    return [
        'views' => [
            'default' => [
                'file/icon/' => [
                    __DIR__ . '/graphics/icons',
                    __DIR__ . '/more_icons', // processed 2nd (may override)
                ],
            ],
        ],
    ];

Viewtypes
=========

You might be wondering: "Why ``/views/default/hello/world.php`` instead of just ``/views/hello/world.php``?".

The subdirectory under ``/views`` determines the *viewtype* of the views below it.
A viewtype generally corresponds to the output format of the views.

The default viewtype is assumed to be HTML and other static assets necessary to
render a responsive web page in a desktop or mobile browser, but it could also be:

 * RSS
 * ATOM
 * JSON
 * Mobile-optimized HTML
 * TV-optimized HTML
 * Any number of other data formats

You can force Elgg to use a particular viewtype to render the page
by setting the ``view`` input variable like so: ``https://mysite.com/?view=rss``.

You could also write a plugin to set this automatically using the ``elgg_set_viewtype()`` function.
For example, your plugin might detect that the page was accessed with an iPhone's browser string,
and set the viewtype to ``iphone`` by calling:

.. code-block:: php

	elgg_set_viewtype('iphone');

The plugin would presumably also supply a set of views optimized for those devices.

.. _guides/views#altering-views-via-plugin:

Altering views via plugins
==========================

Without modifying Elgg's core, Elgg provides several ways to customize almost all output:

* You can `override a view <#overriding-views>`_, completely changing the file used to render it.
* You can `extend a view <#extending-views>`_ by prepending or appending the output of another view to it.
* You can `alter a view's inputs <#altering-view-input>`_ by plugin hook.
* You can `alter a view's output <#altering-view-output>`_ by plugin hook.

Overriding views
----------------

Views in plugin directories always override views in the core directory;
however, when plugins override the views of other plugins,
:ref:`later plugins take precedent <admin/plugins#plugin-order>`.

For example, if we wanted to customize the ``hello/world`` view to use an ``h2``
instead of an ``h1``, we could create a file at ``/mod/example/views/default/hello/world.php`` like this:

.. code-block:: html+php

	<h2>Hello, <?= $vars['name']; ?></h2>

.. note::

	When considering long-term maintenance, overriding views in the core and bundled plugins has a cost:
	Upgrades may bring changes in views, and if you have overridden them, you will not get those changes.
	
	You may instead want to alter :ref:`the input <guides/views#altering-view-input>`
	or :ref:`the output <guides/views#altering-view-output>` of the view via plugin hooks.

.. note::

	Elgg caches view locations. This means that you should disable the system cache while developing with views.
	When you install the changes to a production environment you must flush the caches.

Extending views
---------------

There may be other situations in which you don't want to override the whole view,
you just want to prepend or append some more content to it. In Elgg this is called *extending a view*.

For example, instead of overriding the ``hello/world`` view, we could extend it like so:

.. code-block:: php

	elgg_extend_view('hello/world', 'hello/greeting');

If the contents of ``/views/default/hello/greeting.php`` is:

.. code-block:: html

	<h2>How are you today?</h2>

Then every time we call ``elgg_view('hello/world');``, we'll get:

.. code-block:: html

	<h1>Hello, World!</h1>
	<h2>How are you today?</h2>

You can prepend views by passing a value to the 3rd parameter that is less than 500:

.. code-block:: php

	// appends 'hello/greeting' to every occurrence of 'hello/world'
	elgg_extend_view('hello/world', 'hello/greeting');

	// prepends 'hello/greeting' to every occurrence of 'hello/world'
	elgg_extend_view('hello/world', 'hello/greeting', 450);

All view extensions should be registered in your plugin's ``init,system`` event handler in ``start.php``.

.. _guides/views#altering-view-input:

Altering view input
-------------------

It may be useful to alter a view's ``$vars`` array before the view is rendered.

Before each view rendering the ``$vars`` array is filtered by the
:ref:`plugin hook <guides/hooks-list#views>` ``["view_vars", $view_name]``.
Each registered handler function is passed these arguments:

* ``$hook`` - the string ``"view_vars"``
* ``$view_name`` - the view name being rendered (the first argument passed to ``elgg_view()``)
* ``$returnvalue`` - the modified ``$vars`` array
* ``$params`` - an array containing:

  * ``vars`` - the original ``$vars`` array, unaltered
  * ``view`` - the view name
  * ``viewtype`` - The :ref:`viewtype <guides/views#viewtypes>` being rendered

Altering view input example
^^^^^^^^^^^^^^^^^^^^^^^^^^^

Here we'll alter the default pagination limit for the comments view:

.. code-block:: php

	elgg_register_plugin_hook_handler('view_vars', 'page/elements/comments', 'myplugin_alter_comments_limit');

	function myplugin_alter_comments_limit($hook, $type, $vars, $params) {
	    // only 10 comments per page
	    $vars['limit'] = elgg_extract('limit', $vars, 10);
	    return $vars;
	}

.. _guides/views#altering-view-output:

Altering view output
--------------------

Sometimes it is preferable to alter the output of a view instead of overriding it.

The output of each view is run through the :ref:`plugin hook <guides/hooks-list#views>`
``["view", $view_name]`` before being returned by ``elgg_view()``.
Each registered handler function is passed these arguments:

* ``$hook`` - the string ``"view"``
* ``$view_name`` - the view name being rendered (the first argument passed to ``elgg_view()``)
* ``$result`` - the modified output of the view
* ``$params`` - an array containing:

  * ``viewtype`` - The :ref:`viewtype <guides/views#viewtypes>` being rendered

To alter the view output, the handler just needs to alter ``$returnvalue`` and return a new string.

Altering view output example
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Here we'll eliminate breadcrumbs that don't have at least one link.

.. code-block:: php

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
	    
	    // returning nothing means "don't alter the returnvalue"
	}

Replacing view output completely
--------------------------------

You can pre-set the view output by setting ``$vars['__view_output']``. The value will be returned as a
string. View extensions will not be used and the ``view`` hook will not be triggered.

.. code-block:: php

    elgg_register_plugin_hook_handler('view_vars', 'navigation/breadcrumbs', 'myplugin_no_page_breadcrumbs');

    function myplugin_no_page_breadcrumbs($hook, $type, $vars, $params) {
        if (elgg_in_context('pages')) {
            return ['__view_output' => ""];
        }
    }
    
.. note::

	For ease of use you can also use a already existing default hook callback to prevent output ``\Elgg\Values::preventViewOutput``

Displaying entities
===================

If you don't know what an entity is, :doc:`check this page out first </design/database>`.

The following code will automatically display the entity in ``$entity``:

.. code-block:: php

	echo elgg_view_entity($entity);

As you'll know from the data model introduction, all entities have a *type*
(object, site, user or group), and optionally a subtype
(which could be anything - 'blog', 'forumpost', 'banana').

``elgg_view_entity`` will automatically look for a view called ``type/subtype``;
if there's no subtype, it will look for ``type/type``. Failing that, before it
gives up completely it tries ``type/default``.

RSS feeds in Elgg generally work by outputting the ``object/default`` view in the 'rss' viewtype.

For example, the view to display a blog post might be ``object/blog``.
The view to display a user is ``user/default``.

Full and partial entity views
-----------------------------

``elgg_view_entity`` actually has a number of parameters,
although only the very first one is required. The first three are:

* ``$entity`` - The entity to display
* ``$viewtype`` - The viewtype to display in (defaults to the one we're currently in,
  but it can be forced - eg to display a snippet of RSS within an HTML page)
* ``$full_view`` - Whether to display a *full* version of the entity. (Defaults to ``true``.)

This last parameter is passed to the view as ``$vars['full_view']``.
It's up to you what you do with it; the usual behaviour is to only display comments
and similar information if this is set to true.

.. _guides/views#listing-entities:

Listing entities
================

This is then used in the provided listing functions.
To automatically display a list of blog posts (:doc:`see the full tutorial </tutorials/blog>`), you can call:

.. code-block:: php

	echo elgg_list_entities([
	    'type' => 'object',
	    'subtype' => 'blog',
	]);

This function checks to see if there are any entities; if there are, it first
displays the ``navigation/pagination`` view in order to display a way to move
from page to page. It then repeatedly calls ``elgg_view_entity`` on each entity
before returning the result.

Note that ``elgg_list_entities`` allows the URL to set its ``limit`` and ``offset`` options,
so set those explicitly if you need particular values (e.g. if you're not using it for pagination).

Elgg knows that it can automatically supply an RSS feed on pages that use ``elgg_list_entities``.
It initializes the ``["head","page"]`` plugin hook (which is used by the header)
in order to provide RSS autodiscovery, which is why you can see the orange RSS
icon on those pages in some browsers.

Entity listings will default try to load entity owners and container owners. If you want to prevent this you can turn this off.

.. code-block:: php

	echo elgg_list_entities([
	    'type' => 'object',
	    'subtype' => 'blog',

	    // disable owner preloading
	    'preload_owners' => false,
	]);

See also :doc:`this background information on Elgg's database </design/database>`.

If you want to show a message when the list does not contain items to list, you can pass
a ``no_results`` message or ``true`` for the default message. If you want even more controle over the ``no_results`` message you
can also pass a Closure (an anonymous function).

.. code-block:: php

	echo elgg_list_entities([
	    'type' => 'object',
	    'subtype' => 'blog',

	    'no_results' => elgg_echo('notfound'),
	]);

Rendering a list with an alternate view
---------------------------------------

You can define an alternative view to render list items using ``'item_view'`` parameter.

In some cases, default entity views may be unsuitable for your needs.
Using ``item_view`` allows you to customize the look, while preserving pagination, list's HTML markup etc.

Consider these two examples:

.. code-block:: php

	echo elgg_list_entities([
	    'type' => 'group',
	    'relationship' => 'member',
	    'relationship_guid' => elgg_get_logged_in_user_guid(),
	    'inverse_relationship' => false,
	    'full_view' => false,
	]);

.. code-block:: php

	echo elgg_list_entities([
	    'type' => 'group',
	    'relationship' => 'invited',
	    'relationship_guid' => (int) $user_guid,
	    'inverse_relationship' => true,
	    'item_view' => 'group/format/invitationrequest',
	]);

In the first example, we are displaying a list of groups a user is a member of using the default group view.
In the second example, we want to display a list of groups the user was invited to.

Since invitations are not entities, they do not have their own views and can not be listed using ``elgg_list_*``.
We are providing an alternative item view, that will use the group entity to display
an invitation that contains a group name and buttons to access or reject the invitation.

Rendering a list as a table
---------------------------

Since 2.3 you can render lists as tables. Set ``$options['list_type'] = 'table'`` and provide an array of
TableColumn objects as ``$options['columns']``. The service ``elgg()->table_columns`` provides several
methods to create column objects based around existing views (like ``page/components/column/*``), properties,
or methods.

In this example, we list the latest ``my_plugin`` objects in a table of 3 columns: entity icon, the display
name, and a friendly format of the time.

.. code-block:: php

    echo elgg_list_entities([
        'type' => 'object',
        'subtype' => 'my_plugin',

        'list_type' => 'table',
        'columns' => [
            elgg()->table_columns->icon(),
            elgg()->table_columns->getDisplayName(),
            elgg()->table_columns->time_created(null, [
                'format' => 'friendly',
            ]),
        ],
    ]);

See the ``Elgg\Views\TableColumn\ColumnFactory`` class for more details on how columns are specified and
rendered. You can add or override methods of ``elgg()->table_columns`` in a variety of ways, based on views,
properties/methods on the items, or given functions.

Icons
=====

Elgg has support for two kind of icons: generic icons to help with styling (eg. show delete icon) and Entity icons (eg. user avatar).

Generic icons
-------------

As of Elgg 2.0 the generic icons are based on the FontAwesome_ library. You can get any of the supported icons by calling 
``elgg_view_icon($icon_name, $vars);`` where:

* ``$icon_name`` is the FontAwesome name (without ``fa-``) for example ``user``
* ``$vars`` is optional, for example you can set an additional class

``elgg_view_icon()`` calls the view ``output/icon`` with the given icon name and outputs all the correct classes to render the FontAwesome icon.
If you wish to replace an icon with another icon you can write a ``view_vars``, ``output/icon`` hook to replace the icon name with your replacement.

For backwards compatibility some older Elgg icon names are translated to a corresponding FontAwesome icon.

Entity icons
------------

To view an icon belowing to an Entity call ``elgg_view_entity_icon($entity, $size, $vars);`` where:

* ``$entity`` is the ``ElggEntity`` you wish to show the icon for
* ``$size`` is the requestes size. Default Elgg supports ``large``, ``medium``, ``small``, ``tiny`` and ``topbar`` (``master`` is also 
  available, but don't use it)
* ``$vars`` in order to pass additional information to the icon view

``elgg_view_entity_icon()`` calls a view in the order:

* ``icon/<type>/<subtype>``
* ``icon/<type>/default``
* ``icon/default``

So if you wish to customize the layout of the icon you can overrule the corresponding view.

An example of displaying a user avatar is

.. code-block:: php
	
	// get the user
	$user = elgg_get_logged_in_user_entity();
	
	// show the small icon
	echo elgg_view_entity_icon($user, 'small');
	
	// don't add the user_hover menu to the icon
	echo elgg_view_entity_icon($user, 'small', [
		'use_hover' => false,
	]);

Related
=======

.. toctree::
   :maxdepth: 1
   
   views/page-structure
   views/simplecache
   views/foot-vs-footer
	
.. _FontAwesome: http://fontawesome.io/icons/
