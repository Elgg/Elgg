JavaScript
##########

.. contents:: Contents
   :local:
   :depth: 2

AMD
===

Developers should use the `AMD (Asynchronous Module
Definition) <http://requirejs.org/docs/whyamd.html>`_ standard for writing JavaScript code in Elgg.

Here we'll describe making and executing AMD modules. The RequireJS documentation for
`defining modules <http://requirejs.org/docs/api.html#define>`_ may also be of use.

Executing a module in the current page
--------------------------------------

Telling Elgg to load an existing module in the current page is easy:

.. code-block:: php

    <?php
    elgg_require_js("myplugin/say_hello");

On the client-side, this will asynchronously load the module, load any dependencies, and
execute the module's definition function, if it has one.

Defining the Module
-------------------

Here we define a basic module that alters the page, by passing a "definition function" to ``define()``:

.. code-block:: javascript

    // in views/default/myplugin/say_hello.js

    define(function(require) {
        var elgg = require("elgg");
        var $ = require("jquery");

        $('body').append(elgg.echo('hello_world'));
    });

The module's name is determined by the view name, which here is ``myplugin/say_hello.js``.
We strip the ``.js`` extension, leaving ``myplugin/say_hello``.

.. warning::

    The definition function **must** have one argument named ``require``.

Making modules dependent on other modules
-----------------------------------------

Below we refactor a bit so that the module depends on a new ``myplugin/hello`` module to provide
the greeting:

.. code-block:: javascript

    // in views/default/myplugin/hello.js

    define(function(require) {
        var elgg = require("elgg");

        return elgg.echo('hello_world');
    });

.. code-block:: javascript

    // in views/default/myplugin/say_hello.js

    define(function(require) {
        var $ = require("jquery");
        var hello = require("myplugin/hello");

        $('body').append(hello);
    });

Passing settings to modules
---------------------------

You can use a PHP-based module to pass values from the server. To make the module ``myplugin/settings``,
create the view file ``views/default/myplugin/settings.js.php`` (note the double extension
``.js.php``).

.. code-block:: php

    <?php

    $settings = elgg_get_plugin_from_id('myplugin')->getAllSettings();
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
    elgg_register_simplecache_view('myplugin/settings.js');

.. note::

    The PHP view is cached, so you should treat the output as static (the same for all users) and
    avoid session-specific logic.


Setting the URL of a module
---------------------------

You may have an AMD script outside your views you wish to make available as a module.

The best way to accomplish this is by configuring the path to the file using the
``views.php`` file in the root of your plugin:

.. code-block:: php

    <?php // views.php
    return [
      'underscore.js' => 'vendor/bower-asset/underscore/underscore.min.js',
    ];

If you've copied the script directly into your plugin instead of managing it with Composer,
you can use something like this instead:

.. code-block:: php

    <?php // views.php
    return [
      'underscore.js' => __DIR__ . '/bower_components/underscore/underscore.min.js',
    ];

That's it! Elgg will now load this file whenever the "underscore" module is requested.


Using traditional JS libraries as modules
-----------------------------------------

It's possible to support JavaScript libraries that do not declare themselves as AMD
modules (i.e. they declare global variables instead) if you shim them by
setting ``exports`` and ``deps`` in ``elgg_define_js``:

.. code-block:: php

    // set the path, define its dependencies, and what value it returns
    elgg_define_js('jquery.form', [
        'deps' => ['jquery'],
        'exports' => 'jQuery.fn.ajaxForm',
    ]);

When this is requested client-side:

#. The jQuery module is loaded, as it's marked as a dependency.
#. ``https://elgg.example.org/cache/125235034/views/default/jquery.form.js`` is loaded and executed.
#. The value of ``window.jQuery.fn.ajaxForm`` is returned by the module.

.. warning:: Calls to ``elgg_define_js()`` must be in an ``init, system`` event handler.

Some things to note
^^^^^^^^^^^^^^^^^^^

#. Do not use ``elgg.provide()`` anymore nor other means to attach code to ``elgg`` or other
   global objects. Use modules.
#. Return the value of the module instead of adding to a global variable.
#. Static (.js,.css,etc.) files are automatically minified and cached by Elgg's simplecache system.
#. The configuration is also cached in simplecache, and should not rely on user-specific values
   like ``get_language()``.

.. _guides/javascript#boot:

Booting your plugin
===================

To add functionality to each page, or make sure your hook handlers are registered early enough, you may create a boot module for your plugin, with the name ``boot/<plugin_id>``.

.. code-block:: javascript

    // in views/default/boot/example.js

    define(function(require) {
        var elgg = require("elgg");
        var Plugin = require("elgg/Plugin");

        // plugin logic
        function my_init() { ... }

        return new Plugin({
            // executed in order of plugin priority
            init: function () {
                elgg.register_hook_handler("init", "system", my_init, 400);
            }
        });
    });

When your plugin is active, this module will automatically be loaded on each page. Other modules can depend on ``elgg/init`` to make sure all boot modules are loaded.

Each boot module **must** return an instance of ``elgg/Plugin``. The constructor must receive an object with a function in the ``init`` key. The ``init`` function will be called in the order of the plugin in Elgg's admin area.

.. note:: Though not strictly necessary, you may want to use the ``init, system`` event to control when your initialization code runs with respect to other modules.

.. warning:: A boot module **cannot** depend on the modules ``elgg/init`` or ``elgg/ready``.

Modules provided with Elgg
==========================

Modules ``jquery`` and ``jquery-ui``
------------------------------------

You must depend on these modules to use ``$`` or ``$.ui`` methods. In the future Elgg may stop loading these by default.

Module ``elgg``
---------------

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


``elgg.normalize_url()``

Normalize a URL relative to the elgg root:

.. code:: js

    // "http://localhost/elgg/blog"
    elgg.normalize_url('/blog');

``elgg.forward()``

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
   elgg.parse_url('http://community.elgg.org/file.php?arg=val#fragment');


``elgg.get_page_owner_guid()``

Get the GUID of the current page's owner.


``elgg.register_hook_handler()``

Register a hook handler with the event system. For best results, do this in a plugin boot module.

.. code-block:: js

    // boot module: /views/default/boot/example.js
    define(function (require) {
        var elgg = require('elgg');
        var Plugin = require('elgg/Plugin');

        elgg.register_hook_handler('foo', 'bar', function () { ... });

        return new Plugin();
    });


``elgg.trigger_hook()``

Emit a hook event in the event system. For best results depend on the elgg/init module.

.. code-block:: js

    // old
    value = elgg.trigger_hook('my_plugin:filter', 'value', {}, value);

    define(function (require) {
        require('elgg/init');
        var elgg = require('elgg');

        value = elgg.trigger_hook('my_plugin:filter', 'value', {}, value);
    });


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

Module ``elgg/Ajax``
--------------------

See the :doc:`ajax` page for details.

Module ``elgg/init``
--------------------

``elgg/init`` loads and initializes all boot modules in priority order and triggers the [init, system] hook.

Require this module to make sure all plugins are ready.

Module ``elgg/Plugin``
----------------------

Used to create a :ref:`boot module <guides/javascript#boot>`.

Module ``elgg/ready``
---------------------

``elgg/ready`` loads and initializes all plugin boot modules in priority order.

Require this module to make sure all plugins are ready.

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

.. note:: The ``elgg/Ajax`` module uses the spinner by default.

Module ``elgg/widgets``
-----------------------

Plugins that load a widget layout via Ajax should initialize via this module:

.. code:: js

   require(['elgg/widgets'], function (widgets) {
       widgets.init();
   });

Traditional scripts
===================

Although we highly recommend using AMD modules, you can register scripts with ``elgg_register_js``:

.. code:: php

   elgg_register_js('jquery', $cdnjs_url);

This will override any URLs previously registered under this name.

Load a library on the current page with ``elgg_load_js``:

.. code:: php

   elgg_load_js('jquery');

This will load the library in the page footer. You must use the ``require()`` function to depend on
modules like ``elgg`` and ``jquery``.

.. warning::

   Using inline scripts is NOT SUPPORTED because:
    * They are not testable (maintainability)
    * They are not cacheable (performance)
    * They prevent use of Content-Security-Policy (security)
    * They prevent scripts from being loaded with ``defer`` or ``async`` (performance)

   Inline scripts in core or bundled plugins are considered legacy bugs.

Hooks
=====

The JS engine has a hooks system similar to the PHP engine's plugin hooks: hooks are triggered and plugins can register functions to react or alter information. There is no concept of Elgg events in the JS engine; everything in the JS engine is implemented as a hook.

Registering hook handlers
-------------------------

Handler functions are registered using ``elgg.register_hook_handler()``. Multiple handlers can be registered for the same hook.

The following example registers the ``handleFoo`` function for the ``foo, bar`` hook.

.. code-block:: javascript

    define(function (require) {
        var elgg = require('elgg');
        var Plugin = require('elgg/Plugin');

        function handleFoo(hook, type, params, value) {
            // do something
        }

        elgg.register_hook_handler('foo', 'bar', handleFoo);

        return new Plugin();
   });

The handler function
--------------------

The handler will receive 4 arguments:

- **hook** - The hook name
- **type** - The hook type
- **params** - An object or set of parameters specific to the hook
- **value** - The current value

The ``value`` will be passed through each hook. Depending on the hook, callbacks can simply react or alter data.

Triggering custom hooks
-----------------------

Plugins can trigger their own hooks:

.. code:: javascript

    define(function(require) {
        require('elgg/init');
        var elgg = require('elgg');

        elgg.trigger_hook('name', 'type', {params}, "value");
    });

.. note:: Be aware of timing. If you don't depend on elgg/init, other plugins may not have had a chance to register their handlers.

Available hooks
---------------

**init, system**
    Plugins should register their init functions for this hook. It is fired after Elgg's JS is loaded and all plugin boot modules have been initialized. Depend on the ``elgg/init`` module to be sure this has completed.

**ready, system**
    This hook is fired when the system has fully booted (after init). Depend on the ``elgg/ready`` module to be sure this has completed.

**getOptions, ui.popup**
    This hook is fired for pop up displays (``"rel"="popup"``) and allows for customized placement options.

**config, ckeditor**
    This filters the CKEditor config object. Register for this hook in a plugin boot module. The defaults can be seen in the module ``elgg/ckeditor/config``.

**ajax_request_data, \***
    This filters request data sent by the ``elgg/Ajax`` module. See :doc:`ajax` for details.

**ajax_response_data, \***
    This filters the response data returned to users of the ``elgg/Ajax`` module. See :doc:`ajax` for details.

Third-party assets
==================

We recommend managing third-party scripts and styles with Composer.
Elgg core uses ``fxp/composer-asset-plugin`` for this purpose.
This plugin allows you to pull dependencies from the Bower or NPM package repositories,
but using the Composer command-line tool.

For example, to include jQuery, you could run the following Composer commands:

.. code-block:: shell

    composer global require fxp/composer-asset-plugin:~1.1.1
    composer require bower-asset/jquery:~2.0

.. note::

    ``fxp/composer-asset-plugin`` must be installed globally!
    See https://github.com/francoispluchino/composer-asset-plugin for more info.
