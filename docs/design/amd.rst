AMD
###

.. toctree::
   :maxdepth: 2

Overview
========

If you want to use JavaScript in Elgg: we use a `AMD (Asynchronous Module
Definition) <http://requirejs.org/docs/whyamd.html>`_ compatible system.

This discusses the benefits of using AMD in Elgg.

Why AMD?
========

We have been working hard to make Elgg's JavaScript more maintainable and useful.
We made some strides in 1.8 with the introduction of the "``elgg``" JavaScript object and library, but
have quickly realized the approach we were taking was not scalable.

The size of `JS on the web is growing
<http://httparchive.org/trends.php?s=All&minlabel=Feb+11+2011&maxlabel=Feb+1+2013#bytesJS&reqJS>`_
quickly, and JS in Elgg is growing too. We want Elgg to be able to offer a solution that makes JS
development as productive and maintainable as possible going forward.

The `reasons to choose AMD <http://requirejs.org/docs/whyamd.html>`_ are plenteous and
well-documented. Let's highlight just a few of the most relevant reasons as they relate to Elgg
specifically.

1. Simplified dependency management
-----------------------------------
AMD modules load asynchronously and execute as soon as their dependencies are available, so this
eliminates the need to specify "priority" and "location" when registering JS libs in Elgg. Also, you
don't need to worry about explicitly loading a module's dependencies in PHP. The AMD loader (RequireJS in this
case) takes care of all that hassle for you. It's also possible have
`text dependencies <http://requirejs.org/docs/api.html#text>`_ with the RequireJS text plugin,
so client-side templating should be a breeze.

2. AMD works in all browsers. Today.
------------------------------------
Elgg developers are already writing lots of JavaScript. We know you want to write more. We cannot
accept waiting 5-10 years for a native JS modules solution to be available in all browsers before
we can organize our JavaScript in a maintainable way.

3. You do not need a build step to develop in AMD.
--------------------------------------------------
We like the edit-refresh cycle of web development. We wanted to make sure everyone developing in
Elgg could continue experiencing that joy. Synchronous module formats like Closure or CommonJS just
weren't an option for us. But even though AMD doesn't require a build step, *it is still very
build-friendly*. Because of the ``define()`` wrapper, it's possible to concatenate multiple modules
into a single file and ship them all at once in a production environment. [#]_

AMD is a battle-tested and well thought out module loading system for the web today. We're very
thankful for the work that has gone into it, and are excited to offer it as the standard solution
for JavaScript development in Elgg starting with Elgg 1.9.

.. [#] This is not currently supported by Elgg core, but we'll be looking into it since reducing round-trips is critical for a good first-view experience, especially on mobile devices.
