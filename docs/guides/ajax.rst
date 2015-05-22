Ajax
####

Actions
=======

From JavaScript we can execute actions via XHR POST operations. Here's an example action and script for some basic math:

.. code:: php

    // in myplugin/actions/do_math.php

    if (!elgg_is_xhr()) {
        register_error('Sorry, Ajax only!');
        forward();
    }

    $arg1 = (int)get_input('arg1');
    $arg2 = (int)get_input('arg2');

    system_message('We did it!');

    echo json_encode([
        'sum' => $arg1 + $arg2,
        'product' => $arg1 * $arg2,
    ]);

.. code:: js

   elgg.action('do_math', {
     data: {
       arg1: 1,
       arg2: 2
     },
     success: function (wrapper) {
       if (wrapper.output) {
         alert(wrapper.output.sum);
         alert(wrapper.output.product);
       } else {
         // the system prevented the action from running
       }
     }
   });

Basically what happens here:

 #. CSRF tokens are added to the data.
 #. The data is posted via XHR to http://localhost/elgg/action/example/add.
 #. The action makes sure this is an XHR request, and returns a JSON string.
 #. Once the action completes, Elgg builds a JSON response wrapper containing the echoed output.
 #. Client-side Elgg extracts and displays the system message "We did it!" from the wrapper.
 #. The ``success`` function receives the full wrapper object and validates the ``output`` key.
 #. The browser alerts "3" then "2".

elgg.action notes
-----------------

 * It's best to echo a non-empty string, as this is easy to validate in the ``success`` function. If the action
   was not allowed to run for some reason, ``wrapper.output`` will be an empty string.
 * You may want to use the :doc:`elgg/spinner</guides/javascript>` module.
 * Elgg does not use ``wrapper.status`` for anything, but a call to ``register_error()`` causes it to be
   set to ``-1``.
 * If the action echoes a non-JSON string, ``wrapper.output`` will contain that string.
 * ``elgg.action`` is based on ``jQuery.ajax`` and returns a ``jqXHR`` object (like a Promise), if you should want to use it.
 * After the PHP action completes, other plugins can alter the wrapper via the plugin hook ``'output', 'ajax'``,
   which filters the wrapper as an array (not a JSON string).
 * A ``forward()`` call forces the action to be processed and output immediately, with the ``wrapper.forward_url``
   value set to the normalized location given.
 * To make sure Ajax actions can only be executed via XHR, check ``elgg_is_xhr()`` first.

The action JSON response wrapper
--------------------------------

.. code::

   {
     current_url: {String} "http://example.org/action/example/math", // not very useful
     forward_url: {String} "http://example.org/foo", ...if forward('foo') was called
     output: {String|Object} from echo in action
     status: {Number} 0 = success. -1 = an error was registered.
     system_messages: {Object}
   }

.. warning::

    It's probably best to rely only on the ``output`` key, and validate it in case the PHP action could not run
    for some reason, e.g. the user was logged out or a CSRF attack did not provide tokens.

Fetching Views
==============

A plugin can use a view script to handle XHR GET requests. Here's a simple example of a view that returns a
link to an object given by its GUID:

.. code:: php

    // in myplugin_init()
    elgg_register_ajax_view('myplugin/get_link');

.. code:: php

    // in myplugin/views/default/myplugin/get_link.php

    if (empty($vars['entity']) || !$vars['entity'] instanceof ElggObject) {
        return;
    }

    $object = $vars['entity'];
    /* @var ElggObject $object */

    echo elgg_view('output/url', [
        'text' => $object->getDisplayName(),
        'href' => $object->getUrl(),
        'is_trusted' => true,
    ]);

.. code:: js

    elgg.get('ajax/view/myplugin/get_link', {
      data: {
        guid: 123 // querystring
      },
      success: function (output) {
        $('.myplugin-link').html(output);
      }
    });

The Ajax view system works significantly differently than the action system.

 * There are no access controls based on session status.
 * Non-XHR requests are automatically rejected.
 * GET vars are injected into ``$vars`` in the view.
 * If the request contains ``$_GET['guid']``, the system sets ``$vars['entity']`` to the corresponding entity or
   ``false`` if it can't be loaded.
 * There's no "wrapper" object placed around the view output.
 * System messages/errors shouldn't be used, as they don't display until the user loads another page.
 * If the view name begins with ``js/`` or ``css/``, a corresponding Content-Type header is added.

.. warning::

    Unlike views rendered server-side, Ajax views must treat ``$vars`` as completely untrusted user data.

Returning JSON from a view
--------------------------

If the view outputs encoded JSON, you must use ``elgg.getJSON`` to fetch it (or use some other method to set jQuery's
ajax option ``dataType`` to ``json``). Your ``success`` function will be passed the decoded Object.

Here's an example of fetching a view that returns a JSON-encoded array of times:

.. code:: js

    elgg.getJSON('ajax/view/myplugin/get_times', {
      success: function (data) {
        alert('The time is ' + data.friendly_time);
      }
    });

Fetching Forms
==============

If you register a form view (name starting with ``forms/``), you can fetch it pre-rendered with ``elgg_view_form()``.
Simply use ``ajax/form/<action>`` (instead of ``ajax/view/<view_name>``):

.. code:: php

    // in myplugin_init()
    elgg_register_ajax_view('forms/myplugin/add');

.. code:: js

    elgg.get('ajax/form/myplugin/add, {
      success: function (output) {
        $('.myplugin-form-container').html(output);
      }
    });

 * The GET params will be passed as ``$vars`` to *your* view, **not** the ``input/form`` view.
 * If you need to set ``$vars`` in the ``input/form`` view, you'll need to use the ``("view_vars", "input/form")``
   plugin hook (this can't be done client-side).

.. warning::

    Unlike views rendered server-side, Ajax views must treat ``$vars`` as completely untrusted user data. Review
    the use of ``$vars`` in an existing form before registering it for Ajax fetching.

Ajax helper functions
---------------------

These functions extend jQuery's native Ajax features.

``elgg.get()`` is a wrapper for jQuery's ``$.ajax()``, but forces GET and does URL normalization.

.. code:: js

   // normalizes the url to the current <site_url>/activity
   elgg.get('/activity', {
      success: function(resultText, success, xhr) {
         console.log(resultText);
      }
   });

``elgg.post()`` is a wrapper for jQuery's ``$.ajax()``, but forces POST and does URL normalization.
