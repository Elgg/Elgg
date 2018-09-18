Performance
###########

Make your site run as smoothly and responsively as possible.

.. contents:: Contents
   :local:
   :depth: 2

Can Elgg scale to X million users?
==================================

People often ask whether Elgg can scale to large installations.

First, we might stop and ask, "where are you planning to get all those users?"
Seriously, though, this is a really interesting problem.
Making Elgg scale is, if anything, an issue of technical engineering.
It's interesting but more or less a solved problem. 
Computer science doesn't work differently for Elgg than for Google, for example.
Getting millions of users? That's like the Holy Grail of the entire tech industry.

Second, as with most things in life, the answer is "it depends":

 * How active are your users?
 * What hardware is Elgg running on?
 * Are your plugins behaving well?

`Improving the efficiency of the Elgg engine`__ is an ongoing project,
although there are limits to the amount that any script can do.

__ https://github.com/elgg/elgg/issues?labels=performance&state=open

If you are serious about scalability you will probably want to look at a number of things yourself.

Measure first
=============

There is no point in throwing resources at a problem if you don't know:

 * what the problem is
 * what resources the problem needs
 * where those resources are needed

Invest in some kind of profiling to tell you where your bottleneck is,
especially if you're considering throwing significant money at a problem.

Tune MySQL
==========

Elgg makes extensive use of the back end database, making many trips on each pageload.
This is perfectly normal and a well configured database server will be able to cope with thousands of requests per second.

Here are some configuration tips that might help:

 * Make sure that MySQL is configured to use an appropriate my.cnf for the size of your website.
 * Increase the amount of memory available to PHP and MySQL
   (you will have to increase the amount of memory available to the php process in any case)

Enable caching
==============

Generally, if a program is slow, that is because it is repeatedly performing an expensive computation or operation.
Caching allows the system to avoid doing that work over and over again
by using memory to store the results so that you can skip all the work on subsequent requests.
Below we discuss several generally-available caching solutions relevant to Elgg.


Simplecache
-----------

By default, views are cached in the Elgg data directory for a given period of time.
This removes the need for a view to be regenerated on every page load.

This can be disabled by setting ``$CONFIG->simplecache_enabled = false;``
For best performance, make sure this value is set to ``true``.

This does lead to artifacts during development if you are editing themes in your plugin
as the cached version will be used in preference to the one provided by your plugin.

The simple cache can be disabled via the administration menu.
It is recommended that you do this on your development platform if you are writing Elgg plugins.

This cache is automatically flushed when a plugin is enabled, disabled or reordered,
or when upgrade.php is executed.

For best performance, you can also create a symlink from ``/cache/`` in your www
root dir to the ``assetroot`` directory specified in your config (by default it's located under
``/path/to/dataroot/caches/views_simplecache/``:

.. code-block:: sh

    cd /path/to/wwwroot/
    ln -s /path/to/dataroot/caches/views_simplecache/ cache

If your webserver supports following symlinks, this will serve files straight off
disk without booting up PHP each time.

For security reasons, some webservers (e.g. Apache in version 2.4) might follow the symlinks
by default only if the owner of the symlink source and target match. If the cache symlink
fails to work on your server, you can change the owner of the cache symlink itself (and
not the ``/views_simplecache/`` directory) with

.. code-block:: sh

    cd /path/to/wwwroot/
    chown -h wwwrun:www cache

In this example it's assumed that the ``/views_simplecache/`` directory in the data directory is owned by the
wwwrun account that belongs to the www group. If this is not the case on your server, you have to modify the
chown command accordingly.

System cache
------------

The location of views are cached so that they do not have to be
discovered (profiling indicated that page load took a non-linear amount
of time the more plugins were enabled due to view discovery).
Elgg also caches information like the language mapping and class map.

This can be disabled by setting ``$CONFIG->system_cache_enabled = false;``
For best performance, make sure this value is set to ``true``.

This is currently stored in files in your dataroot (although later
versions of Elgg may use memcache). As with the simple cache it is
flushed when a plugin is enabled, disabled or reordered, or when
upgrade.php is executed.

The system cache can be disabled via the administration menu, and it is
recommended that you do this on your development platform if you are
writing Elgg plugins.

Boot cache
----------

Elgg has the ability to cache numerous resources created and fetched during
the boot process. To configure how long this cache is valid you must set a TTL in your ``settings.php``
file: ``$CONFIG->boot_cache_ttl = 3600;``

Look at the `Stash <http://www.stashphp.com/index.html>`_ documentation for more info about the TTL. 


Database query cache
--------------------

For the lifetime of a given page's execution, a cache of all ``SELECT`` queries is kept.
This means that for a given page load a given select query will only ever go out to the database once,
even if it is executed multiple times. Any write to the database will flush this cache,
so it is advised that on complicated pages you postpone database writes until
the end of the page or use the ``execute_delayed_*`` functionality.
This cache will be automatically cleared at the end of a page load.

You may experience memory problems if you use the Elgg framework as a library in a PHP CLI script.
This can be disabled by setting ``$CONFIG->db_disable_query_cache = true;``


Etags and Expires headers
-------------------------

These technologies tell your users' browsers to cache static assets (CSS, JS, images) locally.
Having these enabled greatly reduces server load and improves user-perceived performance.

Use the `Firefox yslow plugin`__ or Chrome DevTools Audits
to confirm which technologies are currently running on your site.

If the static assets aren't being cached:
 * Verify that you have these extensions installed and enabled on your host
 * Update your .htaccess file, if you are upgrading from a previous version of Elgg
 * Enable Simplecache_, which turns select views into browser-cacheable assets

__ https://addons.mozilla.org/en-us/firefox/addon/yslow/

Memcached
---------

Libmemcached was created by Brian Aker and was designed from day one to give the best performance available to users of Memcached. 

.. seealso::

	http://libmemcached.org/About.html and https://secure.php.net/manual/en/book.memcached.php

Installation requirements:

- php-memcached
- libmemcached
- memcached

Configuration:

Uncomment and populate the following sections in ``settings.php``

.. code-block:: php

    $CONFIG->memcache = true;
    
    $CONFIG->memcache_servers = array (
        array('server1', 11211),
        array('server2', 11211)
    );

Optionaly if you run multiple Elgg installations but use ony one Memcache server, you may want 
to add a namespace prefix. In order to do this, uncomment the following line

.. code-block:: php

    $CONFIG->memcache_namespace_prefix = '';

Squid
-----

We have had good results by using `Squid`_ to cache images for us.

.. _Squid: http://en.wikipedia.org/wiki/Squid_cache


Bytecode caching
----------------

There are numerous PHP code caches available on the market.
These speed up your site by caching the compiled byte code from your
script meaning that your server doesn't have to compile the PHP code
each time it is executed.

Direct file serving
-------------------

If your server can be configured to support the X-Sendfile or X-Accel headers,
you can configure it to be used in ``settings.php``. This allows your web server to
directly stream files to the client instead of using PHP's ``readfile()``.

Hosting
=======

Don't expect to run a site catering for millions of users on a cheap shared host.
You will need to have your own host hardware and access over the configuration,
as well as lots of bandwidth and memory available.

Memory, CPU and bandwidth
-------------------------

Due to the nature of caching, all caching solutions will require memory.
It is a fairly cheap return to throw memory and CPU at the problem.

On advanced hardware it is likely that bandwidth is going to be your bottleneck before the server itself.
Ensure that your host can support the load you are suggesting.

Configuration
-------------

Lastly, take a look at your configuration as there are a few gotchas that can catch people.

For example, out of the box, Apache can handle quite a high load.
However, most distros of Linux come with mysql configured for small sites.
This can result in Apache processes getting stalled waiting to talk to one very overloaded MySQL process.

Check for poorly-behaved plugins
================================

Plugins can be programmed in a very naive way and this can cause your whole site to feel slow.

Try disabling some plugins to see if that noticeably improves performance.
Once you've found a likely offender, go to the original plugin author and report your findings.

Use client-rendered HTML
========================

We've found that at a certain point, much of the time spent on the server
is simply building the HTML of the page with Elgg's views system.

It's very difficult to cache the output of templates since they can generally take arbitrary inputs.
Instead of trying to cache the HTML output of certain pages or views,
the suggestion is to switch to an HTML-based templating system so that the user's browser can cache the templates themselves.
Then have the user's computer do the work of generating the output by applying JSON data to those templates.

This can be very effective, but has the downside of being significant extra development cost.
The Elgg team is looking to integrate this strategy into Elgg directly,
since it is so effective especially on pages with repeated or hidden content.
