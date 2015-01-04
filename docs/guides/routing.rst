Routing
#######

Elgg has two mechanisms to respond to HTTP requests that don't already go through the
:doc:`Actions` and :doc:`Simplecache` systems.

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
act as a **page handler**. When the handler is called, the initial segment is removed
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
first URL segment is type of the hook.

more ...