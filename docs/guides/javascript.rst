Javascript
##########

This guide assumes basic familiarity with:

 * AMD (http://requirejs.org/docs/whyamd.html)

Register third-party libraries with with ``elgg_register_js``:

.. code:: php

   elgg_register_js(‘jquery’, $cdnjs_url);

This will override any URLs previously registered under this name.

If the library does not natively support AMD, use the ‘exports’ and ‘deps’ arguments to add support:

.. code:: php

   elgg_register_js(‘backbone’, array(
     ‘src’ => $cdnjs_url,
     ‘exports’ => ‘Backbone’,
     ‘deps’ => array(‘jquery’, ‘underscore’),
   ));

Load a library on the current page with ``elgg_require_js``:

   elgg_require_js(‘jquery’);

This will asynchronously include and execute the linked code.

For inline script, use the async ``require`` function:

.. code:: html

   <script>
     require([‘jquery’], function(jquery) {
       $(‘#example’).fadeIn();
     });
   </script>

.. warning::

   Using inline scripts is strongly discouraged because:
    * They are not testable (maintainability)
    * They are not cacheable (performance)
    * Doing so forces some scripts to be loaded in ``<head>`` (performance)

   Inline scripts in core or bundled plugins are considered legacy bugs.


Defining modules
================

To define your own reusable AMD module, place the code in the ``js/{module/name}.js`` view. For example:

.. code:: js

   // mod/example/views/default/js/example/module.js
   define(function(require) {
     var elgg = require("elgg");
     var $ = require("jquery");

     return {
       doSomething: function() {
         // Some logic in here
       }
     };
   });

You can then depend on the module like so:

.. code:: js

   define(function(require) {
     var exampleModule = require(‘example/module’);

     exampleModule.doSomething();
   });


Core functions
==============

``elgg.echo``

Translate interface text

.. code:: js

   elgg.echo(‘example:text’, [‘arg1’]);


``elgg.system_message(message)``

Display a status message to the user.

.. code:: js

   elgg.system_message(elgg.echo(‘success’));
   

``elgg.register_error(message)``

Display an error message to the user.

.. code:: js

   elgg.register_error(elgg.echo(‘error’));


``elgg.forward()``

``elgg.normalize_url()``

Normalize a URL relative to the elgg root:

.. code:: js

   elgg.normalize_url(‘/blog’); // “http://localhost/elgg/blog”


Redirect to a new page.

.. code:: js

   elgg.forward(‘/blog’);

This function automatically normalizes the URL.


``elgg.parse_url()``

Parse a URL into its component parts:

.. code:: js

   // returns an object with the properties
   // fragment: "fragment"
   // host: "community.elgg.org"
   // path: "/file.php"
   // query: "arg=val"
   elgg.parse_url(
     'http://community.elgg.org/file.php?arg=val#fragment');


``elgg.get_page_owner_guid()``

Get the GUID of the current page’s owner.


``elgg.security.refreshToken()``

Force a refresh of all XSRF tokens on the page.

This is automatically called every 5 minutes by default.

This requires a valid security token in 1.8, but not in 1.9.

The user will be warned if their session has expired.


``elgg.security.addToken()``

Add a security token to an object, URL, or query string:

.. code:: js

   // returns an object:
   // __elgg_token: "1468dc44c5b437f34423e2d55acfdd87"
   // __elgg_ts: 1328143779
   // other: "data"
   elgg.security.addToken({'other': 'data'});
 
   // returns: "action/add?__elgg_ts=1328144079&__elgg_token=55fd9c2d7f5075d11e722358afd5fde2"
   elgg.security.addToken("action/add");
 
   // returns "?arg=val&__elgg_ts=1328144079&__elgg_token=55fd9c2d7f5075d11e722358afd5fde2"
   elgg.security.addToken("?arg=val");


``elgg.get_logged_in_user_entity()``

Returns the logged in user as an JS ElggUser object.


``elgg.get_logged_in_user_guid()``

Returns the logged in user's guid.


``elgg.is_logged_in()``

True if the user is logged in.


``elgg.is_admin_logged_in()``

True if the user is logged in and is an admin.


``elgg.config.get_language()``

Get the current page’s language.


There are a number of configuration values set in the elgg object:

.. code:: js

   elgg.config.wwwroot; // The root of the website.
   elgg.config.language; // The default site language.
   elgg.config.viewtype; // The current page’s viewtype
   elgg.config.version; // The Elgg version (YYYYMMDDXX).
   elgg.config.release; // The Elgg release (X.Y.Z).


