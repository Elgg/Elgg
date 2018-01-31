Routing
#######

Elgg has two mechanisms to respond to HTTP requests that don't already go through the
:doc:`/design/actions` and :doc:`/guides/views/simplecache` systems.

URL Identifier and Segments
===========================

After removing the site URL, Elgg splits the URL path by ``/`` into an array. The first
element, the **identifier**, is shifted off, and the remaining elements are called the
**segments**. For example, if the site URL is ``http://example.com/elgg/``, the URL
``http://example.com/elgg/blog/owner/jane?foo=123`` produces:

Identifier: ``'blog'``. Segments: ``['owner', 'jane']``. (the query string parameters are
available via ``get_input()``)

The site URL (home page) is a special case that produces an empty string identifier and
an empty segments array.

.. warning:: URL identifier/segments should be considered potentially dangerous user input. Elgg uses ``htmlspecialchars`` to escapes HTML entities in them.

Page Handling
=============

Elgg offers a facility to manage your plugin pages via custom routes, enabling URLs like ``http://yoursite/my_plugin/section``.
You can register a new route using ``elgg_register_route()``, or via ``routes`` config in ``elgg-plugin.php``.
Routes map to resource views, where you can render page contents.

.. code-block:: php

	// in your 'init', 'system' handler
	elgg_register_route('my_plugin:section' [
		'path' => '/my_plugin/section/{guid}/{subsection?}',
		'resource' => 'my_plugin/section',
		'requirements' => [
			'guid' => '\d+',
			'subsection' => '\w+',
		],
	]);

	// in my_plugin/views/default/resources/my_plugin/section.php
	$guid = elgg_extract('guid', $vars);
	$subsection = elgg_extract('subsection', $vars);

	// render content

In the example above, we have registered a new route that is accessible via ``http://yoursite/my_plugin/section/<guid>/<subsection>``.
Whenever that route is accessed with a required ``guid`` segment and an optional ``subsection`` segment, the router
will render the specified ``my_plugin/section`` resource view and pass the parameters extracted from the URL to your
resource view with ``$vars``.


Routes names
------------

Route names can then be used to generate a URL:

.. code-block:: php

	$url = elgg_generate_url('my_plugin:section', [
		'guid' => $entity->guid,
		'subsection' => 'assets',
	]);


The route names are unique across all plugins and core, so another plugin can override the route by registering different
parameters to the same route name.

Route names follow a certain convention and in certain cases will be used to automatically resolve URLs, e.g. to display an entity.

The following conventions are used in core and recommended for plugins:

**view:<entity_type>:<entity_subtype>**
	Maps to the entity profile page, e.g. ``view:user:user`` or ``view:object:blog``
	The path must contain a ``guid``, or ``username`` for users

**edit:<entity_type>:<entity_subtype>**
	Maps to the form to edit the entity, e.g. ``edit:user:user`` or ``edit:object:blog``
	The path must contain a ``guid``, or ``username`` for users
	If you need to add subresources, use suffixes, e.g. ``edit:object:blog:images``, keeping at least one subresource as a default without suffix.

**add:<entity_type>:<entity_subtype>**
	Maps to the form to add a new entity of a given type, e.g. ``add:object:blog``
	The path, as a rule, contains ``container_guid`` parameter

**collection:<entity_type>:<entity_subtype>:<collection_type>**
	Maps to listing pages. Common route names used in core are, as follows:

		- ``collection:object:blog:all``: list all blogs
		- ``collection:object:blog:owner``: list blogs owned by a user with a given username
		- ``collection:object:blog:friends``: list blogs owned by friends of the logged in user (or user with a given username)
		- ``collection:object:blog:group``: list blogs in a group

**default:<entity_type>:<entity_subtype>**
	Maps to the default page for a resource, e.g. the path ``/blog``. Elgg happens to use the "all" collection for these routes.

		- ``default:object:blog``: handle the generic path ``/blog``.

``<entity_subtype>`` can be omitted from route names to register global routes applicable to all entities of a given type.
URL generator will first try to generate a URL using the subtype, and will then fallback to a route name without a subtype.
For example, user profiles are routed to the same resource view regardless of user subtype.

.. code::php

	elgg_register_route('view:object:attachments', [
		'path' => '/attachments/{guid}',
		'resource' => 'attachments',
	]);

	elgg_register_route('view:object:blog:attachments', [
		'path' => '/blog/view/{guid}/attachments',
		'resource' => 'blog/attachments',
	]);

	$blog = get_entity($blog_guid);
	$url = elgg_generate_entity_url($blog, 'view', 'attachments'); // /blog/view/$blog_guid/attachments

	$other = get_entity($other_guid);
	$url = elgg_generate_entity_url($other, 'view', 'attachments'); // /attachments/$other_guid


Route configuration
-------------------

Segments can be defined using wildcards, e.g. ``profile/{username}``, which will match all URLs that contain ``profile/`` followed by
and arbitrary username.

To make a segment optional you can add a ``?`` (question mark) to the wildcard name, e.g. ``profile/{username}/{section?}``.
In this case the URL will be matched even if the ``section`` segment is not provided.

You can further constrain segments using regex requirements:

.. code-block::php

	// elgg-plugin.php
	return [
		'routes' => [
			'profile' => [
				'path' => '/profile/{username}/{section?}',
				'resource' => 'profile',
				'requirements' => [
					'username' => '[\p{L}\p{Nd}._-]+', // only allow valid usernames
					'section' => '\w+', // can only contain alphanumeric characters
				],
				'defaults' => [
					'section' => 'index',
				],
			],
		]
	];

By default, Elgg will set the following requirements for named URL segments:

.. code-block::php

	$patterns = [
		'guid' => '\d+', // only digits
		'group_guid' => '\d+', // only digits
		'container_guid' => '\d+', // only digits
		'owner_guid' => '\d+', // only digits
		'username' => '[\p{L}\p{Nd}._-]+', // letters, digits, underscores, dashes
	];


The ``route`` Plugin Hook
=========================

The ``route`` plugin hook is triggered before page handlers are called. The URL
identifier is given as the type of the hook. This hook can be used to add some logic before the
request is handled elsewhere, or take over page rendering completely.

Generally devs should instead use a page handler unless they need to affect a single page or a wider
variety of URLs.

The following code results in ``/blog/all`` requests being completely handled by the plugin hook handler.
For these requests the ``blog`` page handler is never called.

.. code-block:: php

    function myplugin_blog_all_handler($hook, $type, $returnvalue, $params) {
        $segments = elgg_extract('segments', $returnvalue, array());

        if (isset($segments[0]) && $segments[0] === 'all') {
            $title = "We're taking over!";
            $content = elgg_view_layout('one_column', array(
                'title' => $title,
                'content' => "We can take over page rendering completely"
            ));
            echo elgg_view_page($title, $content);

            // in the route hook, return false says, "stop rendering, we've handled this request"
            return false;
        }
    }

    elgg_register_plugin_hook_handler('route', 'blog', 'myplugin_blog_all_handler');

.. note:: As of 2.1, route modification should be done in the ``route:rewrite`` hook.

The ``route:rewrite`` Plugin Hook
=================================

For URL rewriting, the ``route:rewrite`` hook (with similar arguments as ``route``) is triggered very early,
and allows modifying the request URL path (relative to the Elgg site).

Here we rewrite requests for ``news/*`` to ``blog/*``:

.. code-block:: php

    function myplugin_rewrite_handler($hook, $type, $value, $params) {
        $value['identifier'] = 'blog';
        return $value;
    }

    elgg_register_plugin_hook_handler('route:rewrite', 'news', 'myplugin_rewrite_handler');

.. warning::

	The hook must be registered directly in your plugin ``start.php`` (the ``[init, system]`` event
	is too late).

Routing overview
================

For regular pages, Elgg's program flow is something like this:

#. A user requests ``http://example.com/news/owner/jane``.
#. Plugins are initialized.
#. Elgg parses the URL to identifier ``news`` and segments ``['owner', 'jane']``.
#. Elgg triggers the plugin hook ``route:rewrite, news`` (see above).
#. Elgg triggers the plugin hook ``route, blog`` (was rewritten in the rewrite hook).
#. Elgg finds a registered route that matches the final route path, and renders a resource view associated with it.
   It calls ``elgg_view_resource('blog/owner', $vars)`` where ``$vars`` contains the username.
#. The ``resources/blog/owner`` view gets the username via ``$vars['username']``, and uses many other views and
   formatting functions like ``elgg_view_layout()`` and ``elgg_view_page()`` to create the entire HTML page.
#. PHP invokes Elgg's shutdown sequence.
#. The user receives a fully rendered page.

Elgg's coding standards suggest a particular URL layout, but there is no syntax enforced.
