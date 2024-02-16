JavaScript
##########

.. contents:: Contents
   :local:
   :depth: 2

JavaScript Modules
==================

Developers should use the browser native `ECMAScript modules <https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Modules>`_ for writing JavaScript code in Elgg.

Here we'll describe making and importing these modules in Elgg.

Executing a module in the current page
--------------------------------------

Telling Elgg to load an existing module in the current page is easy:

.. code-block:: php

    <?php
    elgg_import_esm('myplugin/say_hello');

On the client-side, this will asynchronously load the module, load any dependencies, and
execute the module's code, if it has any.

Defining the Module
-------------------

Files with the extension ".mjs" are automatically added to an importmap so they can be imported based on their view name.

For example if we have a file in "views/default/myplugin/say_hello.mjs" we can import from php with ``elgg_import_esm('myplugin/say_hello')``
or from javascript using the ``import`` statement ``import 'myplugin/say_hello';`` or on demand with the ``import()`` function. 

If your modules do not have an ".mjs" extension, for example when they come from a dependency, you might need to register it to the importmap.
After registration they can be imported under their registered name.

.. code-block:: php

    <?php
    elgg_register_esm('myplugin/say_hello', elgg_get_simplecache_url('external/dependency/modulename.js'));

Passing settings to modules
---------------------------

The ``elgg.data`` events
^^^^^^^^^^^^^^^^^^^^^^^^

The ``elgg`` module provides an object ``elgg.data`` which is populated from two server side events:

- **elgg.data, page**: This filters an associative array of data passed to the client.

Let's pass some data to a module:

.. code-block:: php

    <?php

    function myplugin_config_page(\Elgg\Event $event) {
        $value = $event->getValue();
        $value['myplugin']['api'] = elgg_get_site_url() . 'myplugin-api';
        $value['myplugin']['key'] = 'none';
        
        $user = elgg_get_logged_in_user_entity();
        if ($user) {
        	$value['myplugin']['key'] = $user->myplugin_api_key;
        }
        
        return $value;
    }

    elgg_register_event_handler('elgg.data', 'page', 'myplugin_config_page');

.. code-block:: js

    define(['elgg'], function(elgg) {
        var api = elgg.data.myplugin.api;
        var key = elgg.data.myplugin.key; // "none" or a user's key

        // ...
    });


Setting the URL of a module
---------------------------

You may have an AMD script outside your views you wish to make available as a module.

The best way to accomplish this is by configuring the path to the file using the ``views`` section of the
``elgg-plugin.php`` file in the root of your plugin:

.. code-block:: php

    <?php // elgg-plugin.php
    return [
        'views' => [
	        'default' => [
	            'underscore.js' => 'vendor/npm-asset/underscore/underscore.min.js',
	        ],
        ],
    ];

If you've copied the script directly into your plugin instead of managing it with Composer,
you can use something like this instead:

.. code-block:: php

    <?php // elgg-plugin.php
    return [
        'views' => [
	        'default' => [
	            'underscore.js' => __DIR__ . '/node_modules/underscore/underscore.min.js',
	        ],
        ],
    ];

That's it! Elgg will now load this file whenever the "underscore" module is requested.

Modules provided with Elgg
==========================

Module ``elgg``
---------------

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


``elgg.get_logged_in_user_guid()``

Returns the logged in user's guid.


``elgg.is_logged_in()``

True if the user is logged in.


``elgg.is_admin_logged_in()``

True if the user is logged in and is an admin.


There are a number of configuration values set in the elgg object:

.. code-block:: js

    // The root of the website.
    elgg.config.wwwroot;
    // The default site language.
    elgg.config.language;
    // The Elgg release (X.Y.Z).
    elgg.config.release;

Module ``elgg/Ajax``
--------------------

See the :doc:`ajax` page for details.

Module ``elgg/hooks``
-------------------------------

The ``elgg/hooks`` module can be used to have plugins interact with eachother. 

Translate interface text

.. code-block:: js

   define(['elgg/hooks'], function (hooks) {
       hooks.register('my_plugin:filter', 'value', handler, priority);
       
       var result = hooks.trigger('my_plugin:filter', 'value', {}, 'default');

Module ``elgg/i18n``
-------------------------------

The ``elgg/i18n`` module can be used to use translations. 

Translate interface text

.. code-block:: js

   define(['elgg/i18n'], function (i18n) {
       i18n.echo('example:text', ['arg1']);
   });

Module ``elgg/system_messages``
-------------------------------

The ``elgg/system_messages`` module can be used to show system messages to the user. 

.. code-block:: js

   define(['elgg/system_messages'], function (system_messages) {
       system_messages.success('Your success message');
       
       system_messages.error('Your error message');
       
       system_messages.clear();
   });

Module ``elgg/security``
------------------------

The ``elgg/security`` module can be used to add a security token to an object, URL, or query string:

.. code-block:: js

	define(['elgg/security'], function (security) {
       // returns {
	   //   __elgg_token: "1468dc44c5b437f34423e2d55acfdd87",
	   //   __elgg_ts: 1328143779,
	   //   other: "data"
	   // }
	   security.addToken({'other': 'data'});
	
	   // returns: "action/add?__elgg_ts=1328144079&__elgg_token=55fd9c2d7f5075d11e722358afd5fde2"
	   security.addToken("action/add");
	
	   // returns "?arg=val&__elgg_ts=1328144079&__elgg_token=55fd9c2d7f5075d11e722358afd5fde2"
	   security.addToken("?arg=val");
   });
   
Module ``elgg/spinner``
-----------------------

The ``elgg/spinner`` module can be used to create an loading indicator fixed to the top of the window. 
This can be used to give users feedback that the system is performing a longer running task. Using ajax features from ``elgg/Ajax`` will do this by default.
You can also use it in your own code.

.. code-block:: js

   define(['elgg/spinner'], function (spinner) {
       spinner.start();
       // your code
       spinner.stop();
   });

Module ``elgg/popup``
-----------------------

The ``elgg/popup`` module can be used to display an overlay positioned relatively to its anchor (trigger).

The ``elgg/popup`` module is automatically loaded for content drawn using ``output/url`` with the ``class='elgg-popup'``
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
      'class' => 'elgg-popup',
   ]);

   // Button with custom positioning of the popup
   elgg_import_esm('elgg/popup');
   echo elgg_format_element('button', [
      'class' => 'elgg-button elgg-button-submit elgg-popup',
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

   define(['jquery', 'elgg/popup'], function($, popup) {
      $(document).on('click', '.elgg-button-popup', function(e) {

         e.preventDefault();

         var $trigger = $(this);
         var $target = $('#my-target');
         var $close = $target.find('.close');

         popup.open($trigger, $target, {
            'collision': 'fit none'
         });

         $close.on('click', popup.close);
      });
   });

You can use ``getOptions, ui.popup`` plugin hook to manipulate the position of the popup before it has been opened.
You can use jQuery ``open`` and ``close`` events to manipulate popup module after it has been opened or closed.

.. code-block:: js

   define(['jquery', 'elgg/Ajax'], function($, Ajax) {

      $('#my-target').on('open', function() {
         var $module = $(this);
         var $trigger = $module.data('trigger');
         var ajax = new Ajax();
         
         ajax.view('my_module', {
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

   import('elgg/widgets').then((widgets) => {
       widgets.default.init();
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

Use ``"getOptions", "ui.lightbox"`` plugin hook to filter options passed to ``$.colorbox()`` whenever a lightbox is opened.

``elgg/lightbox`` AMD module should be used to open and close the lightbox programmatically:

.. code-block:: js

   define(['elgg/lightbox', 'elgg/spinner'], function(lightbox, spinner) {
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

   import('elgg/lightbox').then((lightbox) => {
      var options = {
         photo: true,
         width: 500
      };
      lightbox.default.bind('a[rel="my-gallery"]', options, false); // 3rd attribute ensures binding is done without proxies
   });

You can also resize the lightbox programmatically if needed:

.. code-block:: js

   define(['elgg/lightbox'], function(lightbox) {
      lightbox.resize({
         width: '300px'
      });
   });

If you wish your content to be loaded by the ``elgg/Ajax`` AMD module, which automaticly loads the JS dependencies, you can pass the option ``ajaxLoadWithDependencies``

.. code-block:: js

   define(['elgg/lightbox'], function(lightbox) {
      lightbox.open({
         href: 'some/view/with/js/dependencies',
         ajaxLoadWithDependencies: true
      });
   });

Module ``elgg/ckeditor``
------------------------

This module can be used to add WYSIWYG editor to a textarea (requires ``ckeditor`` plugin to be enabled).
Note that WYSIWYG will be automatically attached to all instances of ``.elgg-input-longtext``.

.. code-block:: js

   import('elgg/ckeditor').then((elggCKEditor) => {
      elggCKEditor.default.bind('#my-text-area');

      // Toggle CKEditor
      elggCKEditor.default.toggle('#my-text-area');

      // Focus on CKEditor input
      elggCKEditor.default.focus('#my-text-area');
      // or
      $('#my-text-area').trigger('focus');

      // Reset CKEditor input
      elggCKEditor.default.reset('#my-text-area');
      // or
      $('#my-text-area').trigger('reset');

   });


Inline tabs component
---------------------

Inline tabs component fires an ``open`` event whenever a tabs is open and, in case of ajax tabs, finished loading:

.. code-block:: js

	// Add custom animation to tab content
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


Traditional scripts
===================

Although we highly recommend using AMD modules, and there is no Elgg API for loading the scripts, 
you can register scripts in a event handler to add elements to the head links;

.. code-block:: php

	elgg_register_event_handler('head', 'page', $callback);

Hooks
=====

The JS engine has a hooks system similar to the PHP engine's events: hooks are triggered and plugins can register functions to react or alter information.

Registering hook handlers
-------------------------

Handler functions are registered using ``hooks.register()``. Multiple handlers can be registered for the same hook.

.. code-block:: js

    define(['elgg/hooks'], function(hooks) {
        hooks.register('name', 'type', {handler}, {priority});
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

    define(['elgg/hooks'], function(hooks) {
        hooks.trigger('name', 'type', {params}, "value");
    });

Available hooks
---------------

**init, system**
    Plugins should register their init functions for this hook. It is fired after Elgg's JS is loaded and all plugin boot modules have been initialized.

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

Third-party assets
==================

We recommend managing third-party scripts and styles with Composer.
Elgg's composer.json is configured to install dependencies from the **NPM** or **Yarn** package repositories using
Composer command-line tool. Core configuration installs the assets from `Asset Packagist <https://asset-packagist.org>`_
(a repository managed by the Yii community).

Alternatively, you can install ``fxp/composer-asset-plugin`` globally to achieve the same results, but the installation
and update takes much longer.

For example, to include jQuery, you could run the following Composer commands:

.. code-block:: sh

    composer require npm-asset/jquery:~2.0


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
