Security
########

Elgg's approach to the various security issues common to all web applications.

.. tip::

   To report a potential vulnerability in Elgg, email security@elgg.org.

.. contents:: Contents
   :local:
   :depth: 2

Passwords
=========

Password validation
-------------------

The only restriction that Elgg places on a password is that it must be at least 6 characters long by default, though this may be changed 
in ``/elgg-config/settings.php``. 
Additional criteria can be added by a plugin by registering for the ``registeruser:validate:password`` plugin hook.

Password hashing
----------------

Passwords are never stored in plain text, only salted hashes produced with bcrypt. This is done via the standard ``password_hash()`` function. 
On older systems, the ``password-compat`` polyfill is used, but the algorithm is identical.

Elgg installations created before version 1.10 may have residual "legacy" password hashes created using salted MD5. These are migrated to bcrypt 
as users log in, and will be completely removed when a system is upgraded to Elgg 3.0. In the meantime we're happy to assist site owners to 
manually remove these legacy hashes, though it would force those users to reset their passwords.

Password throttling
-------------------

Elgg has a password throttling mechanism to make dictionary attacks from the outside very difficult. A user is only allowed 5 login attempts 
over a 5 minute period.

Password resetting
------------------

If a user forgets his password, a new random password can be requested. After the request, an email is sent with a unique URL. When the user 
visits that URL, a new random password is sent to the user through email.

Sessions
========

Elgg uses PHP's session handling with custom handlers. Session data is stored in the database. The session cookie contains the session id 
that links the user to the browser. The user's metadata is stored in the session including GUID, username, email address. 

The session's lifetime is controlled through the server's PHP configuration and additionally through options in the ``/elgg-config/settings.php``.

Session fixation
----------------

Elgg protects against session fixation by regenerating the session id when a user logs in.

"Remember me" cookie
--------------------

To allow users to stay logged in for a longer period of time regardless of whether the browser has been closed, Elgg uses a cookie 
(default called ``elggperm``) that contains what could be considered a super session identifier. This identifier is stored in a cookies table. 
When a session is being initiated, Elgg checks for the presence of the ``elggperm`` cookie. If it exists and the session code in the cookie matches 
the code in the cookies table, the corresponding user is automatically logged in.

When a user changes their password all existing permanent cookie codes are removed from the database.

The lifetime of the persistent cookie can be controlled in the `/elgg-config/settings.php` file. The default lifetime is 30 days. The database records
for the persistent cookies will be removed after the lifetime expired.

Alternative authentication
==========================

.. note:: This section is very hand-wavy

To replace Elgg's default user authentication system, a plugin could replace the default ``login`` action with its own. 
Better would be to register a PAM handler using ``register_pam_handler()`` which handles the authentication of the user based on the new requirements.

HTTPS
=====

.. note:: You must enable SSL support on your server for any of these techniques to work.

You can serve your whole site over SSL by changing the site URL to include "https" instead of just "http".

XSS
===

Filtering is used in Elgg to make XSS attacks more difficult. The purpose of the filtering is to remove Javascript and other dangerous input 
from users.

Filtering is performed through the function ``filter_tags()``. This function takes in a string and returns a filtered string. It triggers 
a ``validate, input`` plugin hook.

By default Elgg comes with the htmLawed filtering code. Developers can drop in any additional or replacement filtering code as a plugin.

The ``filter_tags()`` function is called on any user input as long as the input is obtained through a call to ``get_input()``. If for some reason 
a developer did not want to perform the default filtering on some user input, the ``get_input()`` function has a parameter for turning off filtering.

CSRF / XSRF
===========

Elgg generates security tokens to prevent `cross-site request forgery`_. These are embedded in all forms and state-modifying AJAX requests as long 
as the correct API is used. Read more in the :doc:`/guides/actions` developer guide.

Signed URLs
===========

It's possible to protect URLs with a unique signature. Read more in the :doc:`/guides/actions` developer guide.

SQL Injection
=============

Elgg's API sanitizes all input before issuing DB queries. Read more in the :doc:`/design/database` design doc.

Privacy
=======

Elgg uses an ACL system to control which users have access to various pieces of content. Read more in the :doc:`/design/database` design doc.

.. _cross-site request forgery: http://en.wikipedia.org/wiki/Cross-site_request_forgery

Hardening
=========

Site administrators can configure settings which will help with hardening the website. Read more in the Administrator guide :doc:`/admin/security`.
