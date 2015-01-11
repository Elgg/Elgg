Routing
#######

Elgg has two mechanisms to respond to HTTP requests that don't already go through the
:doc:`/design/actions` and :doc:`/guides/views/simplecache` systems.

URL Segments
============

Elgg splits URL paths into an array of "segments" (separated by ``/``) after removing
the site URL. For example, if the site URL is ``http://example.com/elgg/``, the URL
``http://example.com/elgg/foo/bar?bing=123`` produces the segments:

``['foo', 'bar']`` (the query string is available via ``get_input()``)

The site URL (home page) is a special case that produces A single empty segment: ``['']``


Page Handler
============

To handle all URLs that begin with a particular segment, you can register a function to
act as a :doc:`/guides/pagehandler`. When the handler is called, the initial segment is removed
and the remaining segments array is passed in as the first argument.

The following code registers a page handler for "blog" URLs and shows how one might route
the request to a resource view.

.. code:: php

   elgg_register_page_handler('blog', 'blog_page_handler');

   function blog_page_handler(array $segments) {
        // if the URL is http://example.com/elgg/blog/view/123/my-blog-post
        // $segments will contain:
        // ['view', '123', 'my-blog-post']

        $subpage = elgg_extract(0, $segments);

        if ($subpage === 'view') {

            // use a view for the page logic to allow other plugins to easily change it
            set_input('guid', (int)elgg_extract(1, $segments));
            echo elgg_view('resources/blog/view');

            // indicate the URL was handled
            return true;
        }

        // ... handle other subpages
   }


The ``route`` Plugin Hook
=========================

The ``route`` plugin hook is triggered earlier, before page handlers are called. The
first URL segment is type of the hook (called identifier). This hook can be used to either modify
identifier and segments or to take over page rendering completely.

The following code intercepts requests to the page handler for ``customblog`` and internally redirects them
to the ``blog`` page handler.

.. code:: php

    function myplugin_customblog_route_handler($hook, $type, $returnvalue, $params) {
        // replaces 'customblog' with 'blog' to use ie. blog/all page under customblog/all path
        $returnvalue['identifier'] = 'blog';
        return $returnvalue;
    }

    elgg_register_plugin_hook_handler('route', 'customblog', 'myplugin_customblog_route_handler');

The following code replaces part of ``blog`` page handler with custom implementation. That's usually good idea when
changing only single pages, instead of whole page handler.

.. code:: php

    function myplugin_blog_all_handler($hook, $type, $returnvalue, $params) {
        $segments = elgg_extract('segments', $returnvalue, array());

        if (isset($segments[0]) && $segments[0] === 'all') {
            $title = "Not a blog anymore";
            $content = elgg_view_layout('one_column', array(
                'title' => $title,
                'content' => "We can take over page rendering completely"
            ));
            echo elgg_view_page($title, $content);
            // tell Elgg by returing false, that we handled this page already, to prevent rendering original one
            return false;
        }
    }

    elgg_register_plugin_hook_handler('route', 'blog', 'myplugin_blog_all_handler');
