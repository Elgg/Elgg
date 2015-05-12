JavaScript
##########

As of Elgg 1.9, we encourage all developers to adopt the `AMD (Asynchronous Module
Definition) <http://requirejs.org/docs/whyamd.html>`_ standard for writing JavaScript code in Elgg.
The 1.8 version is still functional and is :ref:`described below<1.8-js>`.

.. contents:: Contents
   :local:
   :depth: 2

AMD
===

Here we'll describe making and executing AMD modules. The RequireJS documentation for
`defining modules <http://requirejs.org/docs/api.html#define>`_ may also be of use.

Executing a module in the current page
======================================

Telling Elgg to load an existing module in the current page is easy:

.. code-block:: php

    <?php
    elgg_require_js("myplugin/say_hello");

On the client-side, this will asynchronously load the module, load any dependencies, and
execute the module's definition function, if it has one.

Defining the Module
===================

Here we define a basic module that alters the page, by passing a "definition function" to ``define()``:

.. code-block:: javascript

    // in views/default/js/myplugin/say_hello.js

    define(function(require) {
        var elgg = require("elgg");
        var $ = require("jquery");

        $('body').append(elgg.echo('hello_world'));
    });

The module's name is determined by the view name, which here is ``js/myplugin/say_hello.js``.
We strip the leading ``js/`` and the ``.js`` extension, leaving ``myplugin/say_hello``.

.. warning::

    The definition function **must** have one argument named ``require``.

Making modules dependent on other modules
-----------------------------------------

Below we refactor a bit so that the module depends on a new ``myplugin/hello`` module to provide
the greeting:

.. code-block:: javascript

    // in views/default/js/myplugin/hello.js

    define(function(require) {
        var elgg = require("elgg");

        return elgg.echo('hello_world');
    });

.. code-block:: javascript

    // in views/default/js/myplugin/say_hello.js

    define(function(require) {
        var $ = require("jquery");
        var hello = require("myplugin/hello");

        $('body').append(hello);
    });

Passing plugin/Elgg settings to modules
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

You can use a PHP-based module to pass values from the server. To make the module ``myplugin/settings``,
create the view file ``views/default/js/myplugin/settings.js.php`` (note the double extension
``.js.php``).

.. code-block:: php

    <?php

    $settings = elgg_get_plugin_by_id('myplugin')->getAllSettings();
    $settings = [
        'foo' => elgg_extract('foo', $settings),
        'bar' => elgg_extract('bar', $settings),
    ];

    ?>
    define(<?php echo json_encode($settings); ?>);

You must also manually register the view as an external resource:

.. code-block:: php

    <?php
    // note the view name does not include ".php"
    elgg_register_simplecache_view('js/myplugin/settings.js');

.. note::

    The PHP view is cached, so you should treat the output as static (the same for all users) and
    avoid session-specific logic.


Setting the URL of a module
^^^^^^^^^^^^^^^^^^^^^^^^^^^

You may have a script outside your views you wish to make available as a module.

In your PHP ``init, system`` event handler, you can use ``elgg_define_js()`` to do this:

.. code-block:: php

    <?php
    elgg_define_js('underscore', [
        'src' => '/mod/myplugin/vendors/underscore/underscore-min.js',
    ]);

.. note::

    The ``src`` option in ``elgg_define_js()`` is passed through ``elgg_normalize_url``, so you can use paths
    relative to the site URL.

Using traditional JS libraries as modules
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

JavaScript libraries that define global resources can also be defined as AMD modules if you shim them by
setting ``exports`` and optionally ``deps``:

.. code-block:: php

    <?php
    // set the path, define its dependencies, and what value it returns
    elgg_define_js('jquery.form', [
        'src' => '/mod/myplugin/vendors/jquery.form.js',
        'deps' => array('jquery'),
        'exports' => 'jQuery.fn.ajaxForm',
    ]);

When this is requested client-side:

#. The jQuery module is loaded, as it's marked as a dependency.
#. ``http://example.org/elgg/mod/myplugin/vendors/jquery/jquery.form.js`` is loaded and executed.
#. The value of ``window.jQuery.fn.ajaxForm`` is returned by the module.

.. warning:: Calls to ``elgg_define_js()`` must be in an ``init, system`` event handler.

Some things to note
^^^^^^^^^^^^^^^^^^^

#. Do not use ``elgg.provide()`` anymore nor other means to attach code to ``elgg`` or other
   global objects. Use modules.
#. Return the value of the module instead of adding to a global variable.
#. JS and CSS views (names starting with ``js/`` or ``css/``) as well as static (.js/.css) files
   are automatically minified and cached by Elgg's simplecache system.


Migrating JS from Elgg 1.8 to AMD / 1.9
=======================================

**Current 1.8 JavaScript modules will continue to work with Elgg**.

We do not anticipate any backwards compatibility issues with this new direction and will fix any
issues that do come up. The old system will still be functional in Elgg 1.9, but developers are
encouraged to begin looking to AMD as the future of JS in Elgg.

.. _1.8-js:

Traditional JavaScript (1.8)
============================


Register third-party libraries with ``elgg_register_js``:

.. code:: php

   elgg_register_js('jquery', $cdnjs_url);

This will override any URLs previously registered under this name.

Load a library on the current page with ``elgg_load_js``:

.. code:: php

   elgg_load_js('jquery');

This will include and execute the linked code.

.. tip::

   Using inline scripts is strongly discouraged because:
    * They are not testable (maintainability)
    * They are not cacheable (performance)
    * Doing so forces some scripts to be loaded in ``<head>`` (performance)

   Inline scripts in core or bundled plugins are considered legacy bugs.

Core functions available in JS
==============================

``elgg.echo()``

Translate interface text

.. code:: js

   elgg.echo('example:text', ['arg1']);


``elgg.system_message()``

Display a status message to the user.

.. code:: js

   elgg.system_message(elgg.echo('success'));


``elgg.register_error()``

Display an error message to the user.

.. code:: js

   elgg.register_error(elgg.echo('error'));


``elgg.forward()``

``elgg.normalize_url()``

Normalize a URL relative to the elgg root:

.. code:: js

    // "http://localhost/elgg/blog"
    elgg.normalize_url('/blog');



Redirect to a new page.

.. code:: js

    elgg.forward('/blog');

This function automatically normalizes the URL.


``elgg.parse_url()``

Parse a URL into its component parts:

.. code:: js

   // returns {
   //   fragment: "fragment",
   //   host: "community.elgg.org",
   //   path: "/file.php",
   //   query: "arg=val"
   // }
   elgg.parse_url(
     'http://community.elgg.org/file.php?arg=val#fragment');


``elgg.get_page_owner_guid()``

Get the GUID of the current page's owner.


``elgg.register_hook_handler()``

Register a hook handler with the event system.

.. code:: js

    // old initialization style
    elgg.register_hook_handler('init', 'system', my_plugin.init);

    // new: AMD module
    define(function (require) {
        var elgg = require('elgg');

        // [init, system] has fired
    });


``elgg.trigger_hook()``

Emit a hook event in the event system.

.. code:: js

    // allow other plugins to alter value
    value = elgg.trigger_hook('my_plugin:filter', 'value', {}, value);


``elgg.security.refreshToken()``

Force a refresh of all XSRF tokens on the page.

This is automatically called every 5 minutes by default.

This requires a valid security token in 1.8, but not in 1.9.

The user will be warned if their session has expired.


``elgg.security.addToken()``

Add a security token to an object, URL, or query string:

.. code:: js

   // returns {
   //   __elgg_token: "1468dc44c5b437f34423e2d55acfdd87",
   //   __elgg_ts: 1328143779,
   //   other: "data"
   // }
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

Get the current page's language.


There are a number of configuration values set in the elgg object:

.. code:: js

    // The root of the website.
    elgg.config.wwwroot;
    // The default site language.
    elgg.config.language;
    // The current page's viewtype
    elgg.config.viewtype;
    // The Elgg version (YYYYMMDDXX).
    elgg.config.version;
    // The Elgg release (X.Y.Z).
    elgg.config.release;

Module ``elgg/spinner``
-----------------------

The ``elgg/spinner`` module can be used to create an Ajax loading indicator fixed to the top of the window.

.. code:: js

   define(function (require) {
      var spinner = require('elgg/spinner');

      elgg.action('friend/add', {
          beforeSend: spinner.start,
          complete: spinner.stop,
          success: function (json) {
              // ...
          }
      });
   });

Hooks
-----

The JS engine has a hooks system similar to the PHP engine's plugin hooks: hooks are triggered and plugins can register callbacks to react or alter information. There is no concept of Elgg events in the JS engine; everything in the JS engine is implemented as a hook.

Registering a callback to a hook
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Callbacks are registered using ``elgg.register_hook_handler()``. Multiple callbacks can be registered for the same hook.

The following example registers the ``elgg.ui.initDatePicker`` callback for the *init*, *system* event. Note that a difference in the JS engine is that instead of passing a string you pass the function itself to ``elgg.register_hook_handler()`` as the callback.

.. code:: javascript

   elgg.provide('elgg.ui.initDatePicker');
   elgg.ui.initDatePicker = function() { ... }
   
   elgg.register_hook_handler('init', 'system', elgg.ui.initDatePicker);

The callback
^^^^^^^^^^^^

The callback accepts 4 arguments:

- **hook** - The hook name
- **type** - The hook type
- **params** - An object or set of parameters specific to the hook
- **value** - The current value

The ``value`` will be passed through each hook. Depending on the hook, callbacks can simply react or alter data.

Triggering custom hooks
^^^^^^^^^^^^^^^^^^^^^^^

Plugins can trigger their own hooks:

.. code:: javascript

   elgg.hook.trigger_hook('name', 'type', {params}, "value");

Available hooks
^^^^^^^^^^^^^^^

init, system
   This hook is fired when the JS system is ready. Plugins should register their init functions for this hook.

ready, system
   This hook is fired when the system has fully booted.

getOptions, ui.popup
   This hook is fired for pop up displays ("rel"="popup") and allows for customized placement options.
