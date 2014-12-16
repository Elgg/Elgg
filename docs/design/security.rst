Security
########

Elgg’s approach to the various security issues common to all web applications.

.. tip::

   To report a potential vulnerability in Elgg, email security@elgg.org.

.. contents:: Contents
   :local:
   :depth: 2

Passwords
=========

Password validation
-------------------

The only restriction that Elgg places on a password is that it must be at least 6 characters long by default, though this may be changed in /engine/settings.php. Additional criteria can be added by a plugin by registering for the ``registeruser:validate:password`` plugin hook.

Password salting
----------------

Elgg salts passwords with a unique 8 character random string. The salt is generated each time the password is set. The main security advantages of the salting are:
 * preventing anyone with access to the database from conducting a precomputed dictionary attack
 * preventing a site administration from noting users with the same password.

Password hashing
----------------

The hashed password is computed using md5 from the user's password text and the salt.

Password storage
----------------

The hashed password and the salt are stored in the users table. Neither are stored in any cookies on a user's computer.

Password throttling
-------------------

Elgg has a password throttling mechanism to make dictionary attacks from the outside very difficult. A user is only allowed 5 login attempts over a 5 minute period.

Password resetting
------------------

If a user forgets his password, a new random password can be requested. After the request, an email is sent with a unique URL. When the user visits that URL, a new random password is sent to the user through email.

Sessions
========

Elgg uses PHP's session handling with custom handlers. Session data is stored in the database. The session cookie contains the session id that links the user to the browser. The user's metadata is stored in the session including GUID, username, email address. The session's lifetime is controlled through the server's PHP configuration.

Session fixation
----------------
Elgg protects against session fixation by regenerating the session id when a user logs in.

Session hijacking
-----------------
.. warning:: This section is questionable.

Besides protecting against session fixation attacks, Elgg also has a further check to try to defeat session hijacking if the session identifier is compromised. Elgg stores a hash of the browser's user agent and a site secret as a session fingerprint. The use of the site secret is rather superfluous but checking the user agent might prevent some session hijacking attempts.

“Remember me” cookie
--------------------
To allow users to stay logged in for a longer period of time regardless of whether the browser has been closed, Elgg uses a cookie (called elggperm) that contains what could be considered a super session identifier. This identifier is stored in a cookies table. When a session is being initiated, Elgg checks for the presence of the elggperm cookie. If it exists and the session code in the cookie matches the code in the cookies table, the corresponding user is automatically logged in.

Alternative authentication
==========================

.. note:: This section is very hand-wavy

To replace Elgg's default user authentication system, a plugin would have to replace the default action with its own through ``register_action()``. It would also have to register its own pam handler using ``register_pam_handler()``.

.. note:: The ``pam_authenticate()`` function used to call the different modules has a bug related to the importance variable.


HTTPS
=====

.. note:: You must enable SSL support on your server for any of these techniques to work.

To make the login form submit over https, turn on login-over-ssl from Elgg’s admin panel.

You can also serve your whole site over SSL by simply changing the site URL to include “https” instead of just “http.”

XSS
===

Filtering is used in Elgg to make XSS attacks more difficult. The purpose of the filtering is to remove Javascript and other dangerous input from users.

Filtering is performed through the function ``filter_tags()``. This function takes in a string and returns a filtered string. It triggers a ``validate, input`` plugin hook.

By default Elgg comes with the htmLawed filtering code as a plugin. Developers can drop in any additional or replacement filtering code as a plugin.

The ``filter_tags()`` function is called on any user input as long as the input is obtained through a call to ``get_input()``. If for some reason a developer did not want to perform the default filtering on some user input, the ``get_input()`` function has a parameter for turning off filtering.

CSRF / XSRF
===========

Elgg generates security tokens to prevent `cross-site request forgery`_. These are embedded in all forms and state-modifying AJAX requests as long as the correct API is used. Read more in the :doc:`/guides/actions` developer guide.

SQL Injection
=============

Elgg’s API sanitizes all input before issuing DB queries. Read more in the :doc:`/design/database` design doc.

Privacy
=======

Elgg uses an ACL system to control which users have access to various pieces of content. Read more in the :doc:`/design/database` design doc.

.. _cross-site request forgery: http://en.wikipedia.org/wiki/Cross-site_request_forgery