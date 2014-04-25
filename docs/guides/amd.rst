AMD
###

.. toctree::
   :maxdepth: 2

As of Elgg 1.9, we are encouraging all developers to adopt the `AMD (Asynchronous Module
Definition) <http://requirejs.org/docs/whyamd.html>`_ standard for writing JavaScript code in Elgg.

Usage
=====

Defining and loading a module in Elgg 1.9 takes two steps:

1. Define your module as asynchronous JavaScript.
2. Tell Elgg to asynchronously execute your module in the current page.

1. Define your module as asynchronous JavaScript
------------------------------------------------

You can define a module by creating a view or registering a URL.

Defining modules as a view (js/my/module.js)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Modules defined by creating views are immediately available for use and require no registration.
To register a module named ``my/module``, create the view ``views/default/js/my/module.js``.

.. warning: The extension must be ``.js``.

A basic module could look like this:

.. code-block:: javascript

	define(function(require) {
		var elgg = require("elgg");
		var $ = require("jquery");

		return function() {
			// Some logic in here
		};
	});

Define your module via a URL
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

You can define an existing AMD module using ``elgg_define_js()``. Traditional (browser-globals)
JavaScript files can also be defined as AMD modules if you shim them by setting ``exports`` and
optionally ``deps``.

.. warning:: Calls to ``elgg_define_js()`` must be in an ``init, system`` event handler.


.. code-block:: php

    <?php

	elgg_register_event_handler('init', 'system', 'amd_init');

	function amd_init() {
		// AMD module
		elgg_register_js('backbone', '/vendors/backbone/backbone.js', 'async');
		elgg_register_js('backbone', array(
			'src' => '/vendors/backbone/backbone.js',
		));

		// Shimmed AMD module
		elgg_register_js('jquery.form', array(
			'src' => '/vendors/jquery/jquery.form.js',
			'deps' => array('jquery'),
			'exports' => 'jQuery.fn.ajaxForm',
		));
	}

Some things to note
^^^^^^^^^^^^^^^^^^^

1. Do not use ``elgg.provide()`` or ``elgg.require()`` anymore. They are fully replaced by
``define()`` and ``require()`` respectively.
2. Return the value of the module instead of adding to a global variable.
3. Static views (.css, .js) are automatically minified and cached by Elgg's simplecache system.


2. Tell Elgg to asynchronously execute your module in the current page.
---------------------------------------------------------------------
Once an AMD module is defined, you can use ``require(["my/module"])`` from JavaScript to get
access to its "exported" value.

Also, calling ``elgg_load_js("my/module")`` from PHP tells Elgg to execute the module code
on the current page.


Migrating JS from Elgg 1.8
====================================================================
**Current 1.8 JavaScript modules will continue to work with Elgg**.

We do not anticipate any backwards compatibility issues with this new direction and will fix any
issues that do come up. The old system will still be functional in Elgg 1.9, but developers are
encouraged to begin looking to AMD as the future of JS in Elgg.



@Todo: Is any of the following necessary? It's just reiterating parts of requireJS's site.

Why AMD?
========

For some time now, we have been working hard to make Elgg's JavaScript more maintainable and useful. We made some strides in 1.8 with the introduction of the "elgg" JavaScript object and library, but have quickly realized that based on the number of features we'd like to see added to the platform, the approach we were taking was not scalable. The size of `JS on the web is growing <http://httparchive.org/trends.php?s=All&minlabel=Feb+11+2011&maxlabel=Feb+1+2013#bytesJS&reqJS>`_ quickly, and JS in Elgg is growing too. We want Elgg to be able to offer a solution that makes JS development as productive and maintainable as possible for everyone going forward.

The `reasons to choose AMD <http://requirejs.org/docs/whyamd.html>`_ are plenteous and well-documented. Let's highlight just a few of the most relevant reasons as they relate to Elgg specifically.

1. Simplified dependency management
-----------------------------------
No more "priority" or "location" arguments for your scripts. You *don't even need to call* ``elgg_register_js()`` for new AMD modules. They load asynchronously and execute as soon as their dependencies are available. Also, you don't need to worry about explicitly loading your module's dependencies using ``elgg_load_js()``. The AMD loader (RequireJS in this case) takes care of all that hassle for you. It's also possible have `text dependencies <http://requirejs.org/docs/api.html#text>`_ with the RequireJS text plugin, so client-side templating should be a breeze.

2. AMD works in all browsers. Today.
------------------------------------
Elgg developers are already writing lots of JavaScript. We know you want to write more. We cannot accept waiting 5-10 years for a native JS modules solution to be available in all browsers before we can organize our JavaScript in a maintainable way.

3. You do not need a build step to develop in AMD.
--------------------------------------------------
We like the edit-refresh cycle of web development. We wanted to make sure everyone developing on Elgg could continue experiencing that joy. Synchronous module formats like Closure or CommonJS just weren't an option for us. But even though AMD doesn't require a build step, *it is still very build-friendly*. Because of the ``define()`` wrapper, It's possible to concatenate multiple modules into a single file and ship them all at once in a production environment. [*]_

AMD is a battle-tested and well thought out module loading system for the web today. We're very thankful for the work that has gone into it, and are excited to offer it as the standard solution for JavaScript development in Elgg starting with Elgg 1.9.

.. [*] This is not currently supported by Elgg core, but we'll be looking into it, since reducing round-trips is critical for a good first-view experience, especially on mobile devices.
