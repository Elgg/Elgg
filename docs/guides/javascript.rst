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

.. code-block:: js

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

.. code-block:: js

    // in views/default/myplugin/hello.js

    define(function(require) {
        var elgg = require("elgg");

        return elgg.echo('hello_world');
    });

.. code-block:: js

    // in views/default/myplugin/say_hello.js

    define(function(require) {
        var $ = require("jquery");
        var hello = require("myplugin/hello");

        $('body').append(hello);
    });

.. _guides/javascript#config:

Passing settings to modules
---------------------------

The ``elgg.data`` plugin hooks
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The ``elgg`` module provides an object ``elgg.data`` which is populated from two server side hooks:

- **elgg.data, site**: This filters an associative array of site-specific data passed to the client and cached.
- **elgg.data, page**: This filters an associative array of uncached, page-specific data passed to the client.

Let's pass some data to a module:

.. code-block:: php

    <?php

    function myplugin_config_site($hook, $type, $value, $params) {
        // this will be cached client-side
        $value['myplugin']['api'] = elgg_get_site_url() . 'myplugin-api';
        $value['myplugin']['key'] = 'none';
        return $value;
    }

    function myplugin_config_page($hook, $type, $value, $params) {
        $user = elgg_get_logged_in_user_entity();
        if ($user) {
            $value['myplugin']['key'] = $user->myplugin_api_key;
            return $value;
        }
    }

    elgg_register_plugin_hook_handler('elgg.data', 'site', 'myplugin_config_site');
    elgg_register_plugin_hook_handler('elgg.data', 'page', 'myplugin_config_page');

.. code-block:: js

    define(function(require) {
        var elgg = require("elgg");

        var api = elgg.data.myplugin.api;
        var key = elgg.data.myplugin.key; // "none" or a user's key

        // ...
    });

.. note::

    In ``elgg.data``, page data overrides site data. Also note ``json_encode()`` is used to copy
    data client-side, so the data must be JSON-encodable.

Making a config module
^^^^^^^^^^^^^^^^^^^^^^

You can use a PHP-based module to pass values from the server. To make the module ``myplugin/settings``,
create the view file ``views/default/myplugin/settings.js.php`` (note the double extension
``.js.php``).

.. code-block:: php

    <?php

    // this will be cached client-side
    $settings = [
        'api' => elgg_get_site_url() . 'myplugin-api',
        'key' => null,
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
        'default' => [
            'underscore.js' => 'vendor/bower-asset/underscore/underscore.min.js',
        ],
    ];

If you've copied the script directly into your plugin instead of managing it with Composer,
you can use something like this instead:

.. code-block:: php

    <?php // views.php
    return [
        'default' => [
            'underscore.js' => __DIR__ . '/bower_components/underscore/underscore.min.js',
        ],
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

.. code-block:: js

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

.. code-block:: js

   elgg.echo('example:text', ['arg1']);


``elgg.system_message()``

Display a status message to the user.

.. code-block:: js

   elgg.system_message(elgg.echo('success'));


``elgg.register_error()``

Display an error message to the user.

.. code-block:: js

   elgg.register_error(elgg.echo('error'));


``elgg.normalize_url()``

Normalize a URL relative to the elgg root:

.. code-block:: js

    // "http://localhost/elgg/blog"
    elgg.normalize_url('/blog');

``elgg.forward()``

Redirect to a new page.

.. code-block:: js

    elgg.forward('/blog');

This function automatically normalizes the URL.


``elgg.parse_url()``

Parse a URL into its component parts:

.. code-block:: js

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

The user will be warned if their session has expired.


``elgg.security.addToken()``

Add a security token to an object, URL, or query string:

.. code-block:: js

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

.. code-block:: js

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

.. code-block:: js

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

Module ``elgg/popup``
-----------------------

The ``elgg/popup`` module can be used to display an overlay positioned relatively to its anchor (trigger).

The ``elgg/popup`` module is loaded by default, and binding a popup module to an anchor is as simple as adding ``rel="popup"``
attribute and defining target module with a ``href`` (or ``data-href``) attribute. Popup module positioning can be defined with
``data-position`` attribute of the trigger element.

.. $.position(): http://api.jqueryui.com/position/

.. code-block:: php

   echo elgg_format_element('div', [
      'class' => 'elgg-module-popup hidden',
      'id' => 'popup-module',
   ], 'Popup module content');

   // Simple anchor
   echo elgg_view('output/url', [
      'href' => '#popup-module',
      'text' => 'Show popup',
      'rel' => 'popup',
   ]);

   // Button with custom positioning of the popup
   echo elgg_format_element('button', [
      'rel' => 'popup',
      'class' => 'elgg-button elgg-button-submit',
      'text' => 'Show popup',
      'data-href' => '#popup-module',
      'data-position' => json_encode([
         'my' => 'center bottom',
         'at' => 'center top',
      ]),
   ]);


The ``elgg/popup`` module allows you to build out more complex UI/UX elements. You can open and close
popup modules programmatically:

.. code-block:: js

   define(function(require) {
      var $ = require('jquery');
      $(document).on('click', '.elgg-button-popup', function(e) {

         e.preventDefault();

         var $trigger = $(this);
         var $target = $('#my-target');
         var $close = $target.find('.close');

         require(['elgg/popup'], function(popup) {
            popup.open($trigger, $target, {
               'collision': 'fit none'
            });

            $close.on('click', popup.close);
         });
      });
   });

You can use ``getOptions, ui.popup`` plugin hook to manipulate the position of the popup before it has been opened.
You can use jQuery ``open`` and ``close`` events to manipulate popup module after it has been opened or closed.

.. code-block:: js

   define(function(require) {

      var elgg = require('elgg');
      var $ = require('jquery');

      $('#my-target').on('open', function() {
         var $module = $(this);
         var $trigger = $module.data('trigger');

         elgg.ajax('ajax/view/my_module', {
            beforeSend: function() {
               $trigger.hide();
               $module.html('').addClass('elgg-ajax-loader');
            },
            success: function(output) {
               $module.removeClass('elgg-ajax-loader').html(output);
            }
         });
      }).on('close', function() {
         var $trigger = $(this).data('trigger');
         $trigger.show();
      });
   });

Open popup modules will always contain the following data that can be accessed via ``$.data()``:

 * ``trigger`` - jQuery element used to trigger the popup module to open
 * ``position`` - An object defining popup module position that was passed to ``$.position()``

By default, ``target`` element will be appended to ``$('body')`` thus altering DOM hierarchy. If you need to preserve the DOM position of the popup module, you can add ``.elgg-popup-inline`` class to your trigger.

Module ``elgg/widgets``
-----------------------

Plugins that load a widget layout via Ajax should initialize via this module:

.. code-block:: js

   require(['elgg/widgets'], function (widgets) {
       widgets.init();
   });

Module ``elgg/lightbox``
------------------------

Elgg is distributed with the Colorbox jQuery library. Please go to http://www.jacklmoore.com/colorbox for more information on the options of this lightbox.

Use the following classes to bind your anchor elements to a lightbox:

 * ``elgg-lightbox`` - loads an HTML resource
 * ``elgg-lightbox-photo`` - loads an image resource (should be used to avoid displaying raw image bytes instead of an ``img`` tag)
 * ``elgg-lightbox-inline`` - displays an inline HTML element in a lightbox
 * ``elgg-lightbox-iframe`` - loads a resource in an ``iframe``

You may apply colorbox options to an individual ``elgg-lightbox`` element by setting the attribute ``data-colorbox-opts`` to a JSON settings object.

.. code-block:: php

   echo elgg_view('output/url', [
      'text' => 'Open lightbox',
      'href' => 'ajax/view/my_view',
      'class' => 'elgg-lightbox',
      'data-colorbox-opts' => json_encode([
         'width' => '300px',
      ])
   ]);

Use ``"getOptions", "ui.lightbox"`` plugin hook to filter options passed to ``$.colorbox()`` whenever a lightbox is opened. Note that the hook handler should depend on ``elgg/init`` AMD module.

``elgg/lightbox`` AMD module should be used to open and close the lightbox programmatically:

.. code-block:: js

   define(function(require) {
      var lightbox = require('elgg/lightbox');
      var spinner = require('elgg/spinner');

      lightbox.open({
         html: '<p>Hello world!</p>',
         onClosed: function() {
            lightbox.open({
               onLoad: spinner.start,
               onComplete: spinner.stop,
               photo: true,
               href: 'https://elgg.org/cache/1457904417/default/community_theme/graphics/logo.png',
            });
         }
      });
   });

To support gallery sets (via ``rel`` attribute), you need to bind colorbox directly to a specific selector (note that this will ignore ``data-colorbox-opts`` on all elements in a set):

.. code-block:: js

   require(['elgg/lightbox'], function(lightbox) {
      var options = {
         photo: true,
         width: 500
      };
      lightbox.bind('a[rel="my-gallery"]', options, false); // 3rd attribute ensures binding is done without proxies
   });

You can also resize the lightbox programmatically if needed:

.. code-block:: js

   define(function(require) {
      var lightbox = require('elgg/lightbox');
     
      lightbox.resize({
         width: '300px'
      });
   });

Module ``elgg/ckeditor``
------------------------

This module can be used to add WYSIWYG editor to a textarea (requires ``ckeditor`` plugin to be enabled).
Note that WYSIWYG will be automatically attached to all instances of ``.elgg-input-longtext``.

.. code-block:: js

   require(['elgg/ckeditor'], function (elggCKEditor) {
      elggCKEditor.bind('#my-text-area');

      // Toggle CKEditor
      elggCKEditor.toggle('#my-text-area');

      // Focus on CKEditor input
      elggCKEditor.focus('#my-text-area');
      // or
      $('#my-text-area').trigger('focus');

      // Reset CKEditor input
      elggCKEditor.reset('#my-text-area');
      // or
      $('#my-text-area').trigger('reset');

   });


Inline tabs component
---------------------

Inline tabs component fires an ``open`` event whenever a tabs is open and, in case of ajax tabs, finished loading:

.. code-block:: js

	// Add custom animation to tab content
	require(['jquery', 'elgg/ready'], function($) {
		$(document).on('open', '.theme-sandbox-tab-callback', function() {
			$(this).find('a').text('Clicked!');
			$(this).data('target').hide().show('slide', {
				duration: 2000,
				direction: 'right',
				complete: function() {
					alert('Thank you for clicking. We hope you enjoyed the show!');
					$(this).css('display', ''); // .show() adds display property
				}
			});
		});
	});


Traditional scripts
===================

Although we highly recommend using AMD modules, you can register scripts with ``elgg_register_js``:

.. code-block:: php

   elgg_register_js('jquery', $cdnjs_url);

This will override any URLs previously registered under this name.

Load a library on the current page with ``elgg_load_js``:

.. code-block:: php

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

.. code-block:: js

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

.. code-block:: js

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

**getOptions, ui.lightbox**
    This hook can be used to filter options passed to ``$.colorbox()``

**config, ckeditor**
    This filters the CKEditor config object. Register for this hook in a plugin boot module. The defaults can be seen in the module ``elgg/ckeditor/config``.

**prepare, ckeditor**
    This hook can be used to decorate ``CKEDITOR`` global. You can use this hook to register new CKEditor plugins and add event bindings.

**ajax_request_data, \***
    This filters request data sent by the ``elgg/Ajax`` module. See :doc:`ajax` for details.
    The hook must check if the data is a plain object or an instanceof ``FormData`` to piggyback the values using correct API.

**ajax_response_data, \***
    This filters the response data returned to users of the ``elgg/Ajax`` module. See :doc:`ajax` for details.

**insert, editor**
    This hook is triggered by the embed plugin and can be used to filter content before it is inserted into the textarea. This hook can also be used by WYSIWYG editors to insert content using their own API (in this case the handler should return ``false``). See ckeditor plugin for an example.

Third-party assets
==================

We recommend managing third-party scripts and styles with Composer.
Elgg's composer.json is configured to install dependencies from the **Bower** or **Yarn** package repositories using
Composer command-line tool. Core configuration installs the assets from `Asset Packagist <https://asset-packagist.org>`_
(a repository managed by the Yii community).

Alternatively, you can install ``fxp/composer-asset-plugin`` globally to achieve the same results, but the installation
and update takes much longer.

For example, to include jQuery, you could run the following Composer commands:

.. code-block:: sh

    composer require bower-asset/jquery:~2.0


If you are using a starter-project, or pulling in Elgg as a composer dependency via a custom composer project,
update your ``composer.json`` with the following configuration:

.. code-block:: json

	{
	    "repositories": [
	        {
	            "type": "composer",
	            "url": "https://asset-packagist.org"
	        }
	    ],
		"config": {
	        "fxp-asset": {
	            "enabled": false
	        }
	    },
	}

You can find additional information at `Asset Packagist <https://asset-packagist.org>`_ website.