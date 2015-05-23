Authentication
==============

Elgg provides everything needed to authenticate users via username/email and password
out of the box, including:

 * remember-me cookies for persistent login
 * password reset logic
 * secure storage of passwords
 * logout
 * UIs for accomplishing all of the above
 
All that's left for you to do as a developer is to use
the built-in authentication functions to secure your pages and actions.

Working with the logged in user
-------------------------------

Check whether the current user is logged in with ``elgg_is_logged_in()``:

.. code:: php

    if (elgg_is_logged_in()) {
      // do something just for logged-in users
    }

Check if the current user is an admin with ``elgg_is_admin_logged_in()``:

.. code:: php

    if (elgg_is_admin_logged_in()) {
      // do something just for admins
    }
    
Get the currently logged in user with ``elgg_get_logged_in_user_entity()``:

.. code:: php

    $user = elgg_get_logged_in_user_entity();

The returned object is an ``ElggUser`` so you can use all the methods and properties
of that class to access information about the user. If the user is not logged in,
this will return ``null``, so be sure to check for that first.

Gatekeepers
-----------

Gatekeeper functions allow you to manage how code gets executed by applying access control rules.

Forward a user to the front page if they are not logged in with ``elgg_gatekeeper()``:

.. code:: php

    elgg_gatekeeper();
    
    echo "Information for logged-in users only";

.. note::

   In Elgg 1.8 and below this function was called ``gatekeeper()``

Forward a user to the front page unless they are an admin with ``elgg_admin_gatekeeper()``:

.. code:: php

    elgg_admin_gatekeeper();
    
    echo "Information for admins only";

.. note::

   In Elgg 1.8 and below this function was called ``admin_gatekeeper()``


Prevent CSRF attacks with ``action_gatekeeper()``.

.. code:: php

    action_gatekeeper();
    
    // Mutate some state in the database on behalf of the logged in user...

This function should be used in :doc:`actions` prior to Elgg 1.8.

.. note::

   As of Elgg version 1.8 this function is called for all registered actions.
   There is no longer a need to call this function in your own actions.
   If you wish to protect other pages with action tokens then you can call this function.



Pluggable Authentication Modules 
--------------------------------

Elgg has support for pluggable authentication modules (PAM), which enables you to write your own authentication handlers. Whenever a request needs to get authenticated the system will call ``elgg_authenticate()`` which probes the registered PAM handlers until one returns success.

The preferred approach is to create a separate Elgg plugin which will have one simple task: to process an authentication request. This involves setting up an authentication handler in the plugin's :doc:`start.php <plugins>` file, and to register it with the PAM module so it will get processed whenever the system needs to authenticate a request.

The authentication handler is a function and takes a single parameter. Registering the handler is being done by ``register_pam_handler()`` which takes the name of the authentication handler, the importance and the policy as parameters. It is advised to register the handler in the plugin's init function, for example:

.. code:: php

   function your_plugin_init() {
      // Register the authentication handler
      register_pam_handler('your_plugin_auth_handler');
   }
   
   function your_plugin_auth_handler($credentials) {
      // do things ...
   }
   
   // Add the plugin's init function to the system's init event
   elgg_register_elgg_event_handler('init', 'system', 'your_plugin_init');

Importance
----------

By default an authentication module is registered with an importance of **sufficient**.

In a list of authentication modules; if any one marked *sufficient* returns ``true``, ``pam_authenticate()`` will also return ``true``. The exception to this is when an authentication module is registered with an importance of **required**. All required modules must return ``true`` for ``pam_authenticate()`` to return ``true``, regardless of whether all sufficient modules return ``true``.

Passed credentials
------------------

The format of the credentials passed to the handler can vary, depending on the originating request. For example, a regular login via the login form will create a named array, with the keys ``username`` and ``password``. If a request was made for example via XML-RPC then the credentials will be set in the HTTP header, so in this case nothing will get passed to the authentication handler and the handler will need to perform steps on its own to authenticate the request.

Return value
------------

The authentication handle should return a ``boolean``, indicating if the request could be authenticated or not. One caveat is that in case of a regular user login where credentials are available as username and password the user will get logged in. In case of the XML-RPC example the authentication handler will need to perform this step itself since the rest of the system will not have any idea of either possible formats of credentials passed nor its contents. Logging in a user is quite simple and is being done by ``login()``, which expects an ``ElggUser`` object.
