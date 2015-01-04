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


Page Handler
============

To handle all URLs that begin with a particular identifier, you can register a function to
act as a :doc:`/guides/pagehandler`. When the handler is called, the segments array is
passed in as the first argument.

The following code registers a page handler for "blog" URLs and shows how one might route
the request to a resource view.

.. code:: php

   elgg_register_page_handler('blog', 'blog_page_handler');

   function blog_page_handler(array $segments) {
        // if the URL is http://example.com/elgg/blog/view/123/my-blog-post
        // $segments contains: ['view', '123', 'my-blog-post']

        $subpage = elgg_extract(0, $segments);
        if ($subpage === 'view') {

            // use a view for the page logic to allow other plugins to easily change it
            set_input('guid', (int)elgg_extract(1, $segments));
            echo elgg_view('resources/blog/view');

            // in page handlers, return true says, "we've handled this request"
            return true;
        }

        // ... handle other subpages
   }


The ``route`` Plugin Hook
=========================

The ``route`` plugin hook is triggered earlier, before page handlers are called. The URL
identifier is given as the type of the hook. This hook can be used to modify the identifier
or segments, to take over page rendering completely, or just to add some logic before the
request is handled elsewhere.

Generally devs should use a page handler unless they need to affect a single page or a wider variety of URLs.

The following code intercepts requests to the page handler for ``customblog`` and internally redirects them
to the ``blog`` page handler.

.. code:: php

    function myplugin_customblog_route_handler($hook, $type, $returnvalue, $params) {
        // direct Elgg to use the page handler for 'blog'
        $returnvalue['identifier'] = 'blog';
        return $returnvalue;
    }

    elgg_register_plugin_hook_handler('route', 'customblog', 'myplugin_customblog_route_handler');

The following code results in ``/blog/all`` requests being completely handled by the plugin hook handler.
For these requests the ``blog`` page handler is never called.

.. code:: php

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


Routing overview
================

For regular pages, Elgg's program flow is something like this:

#. A user requests ``http://example.com/blog/owner/jane``.
#. Plugins are initialized.
#. Elgg parses the URL to identifier ``blog`` and segments ``['owner', 'jane']``.
#. Elgg triggers the plugin hook ``route, blog`` (see above).
#. Elgg finds a registered page handler (see above) for ``blog``, and calls the function, passing in
   the segments.
#. The page handler function determines it needs to render a single user's blog. It stores the username
   via ``set_input()`` and calls the view ``resources/blog/owner``.
#. The ``resources/blog/owner`` view gets the username via ``get_input()``, and uses many other views and
   formatting functions like ``elgg_view_layout()`` and ``elgg_view_page()`` to create the entire HTML page.
#. The page handler echos the view HTML and returns ``true`` to indicate it handled the request.
#. PHP invokes Elgg's shutdown sequence.
#. The user receives a fully rendered page.

Elgg's coding standards suggest a particular URL layout, but there is no syntax enforced.
