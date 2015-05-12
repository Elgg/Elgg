Forms + Actions
###############

Create, update, or delete content.

Elgg forms submit to actions. Actions define the behavior for form submission.

This guide assumes basic familiarity with:

- :doc:`/admin/plugins`
- :doc:`views`
- :doc:`i18n`

.. contents:: Contents
   :local:
   :depth: 2

Registering actions
===================

Actions must be registered before use. Use ``elgg_register_action`` for this:

.. code:: php

   elgg_register_action("example", __DIR__ . "/actions/example.php");

The ``mod/example/actions/example.php`` script will now be run whenever a form is submitted to ``http://localhost/elgg/action/example``.

.. warning:: A stumbling point for many new developers is the URL for actions. The URL always uses ``/action/`` (singular) and never ``/actions/`` (plural). However, action script files are usually saved under the directory ``/actions/`` (plural) and always have an extension.


Permissions
-----------
By default, actions are only available to logged in users.

To make an action available to logged out users, pass ``"public"`` as the third parameter:

.. code:: php

   elgg_register_action("example", $filepath, "public");

To restrict an action to only administrators, pass ``"admin"`` for the last parameter:

.. code:: php

   elgg_register_action("example", $filepath, "admin");


Writing action files
--------------------

Use the ``get_input`` function to get access to request parameters:

.. code:: php

   $field = get_input('input_field_name', 'default_value');

You can then use the :doc:`database` api to load entities and perform actions on them accordingly.

To redirect the page once you've completed your actions, use the ``forward`` function:

.. code:: php

   forward('url/to/forward/to');

For example, to forward to the user's profile:

.. code:: php

   $user = elgg_get_logged_in_user_entity();
   forward($user->getURL());

URLs can be relative to the Elgg root:

.. code:: php

   $user = elgg_get_logged_in_user_entity();
   forward("/example/$user->username");

Redirect to the referring page by using the ``REFERRER`` constant:

.. code:: php

   forward(REFERRER);
   forward(REFERER); // equivalent

Give feedback to the user about the status of the action by using
``system_message`` for positive feedback or ``register_error`` for warnings and errors:

.. code:: php

   if ($success) {
     system_message(elgg_echo(‘actions:example:success’));
   } else {
     register_error(elgg_echo(‘actions:example:error’));
   }


Customizing actions
-------------------

Before executing any action, Elgg triggers a hook:

.. code:: php

   $result = elgg_trigger_plugin_hook('action', $action, null, true);

Where ``$action`` is the action being called. If the hook returns ``false`` then the action will not be executed.

Example: Captcha
^^^^^^^^^^^^^^^^

The captcha module uses this to intercept the ``register`` and ``user/requestnewpassword`` actions and redirect them to a function which checks the captcha code. This check returns ``true`` if valid or ``false`` if not (which prevents the associated action from executing).

This is done as follows:

.. code:: php

   elgg_register_plugin_hook_handler("action", "register", "captcha_verify_action_hook");
   elgg_register_plugin_hook_handler("action", "user/requestnewpassword", "captcha_verify_action_hook");

   ...

   function captcha_verify_action_hook($hook, $entity_type, $returnvalue, $params) {
     $token = get_input('captcha_token');
     $input = get_input('captcha_input');

     if (($token) && (captcha_verify_captcha($input, $token))) {
       return true;
     }
  
     register_error(elgg_echo('captcha:captchafail'));

     return false;
   }

This lets a plugin extend an existing action without the need to replace the whole action. In the case of the captcha plugin it allows the plugin to provide captcha support in a very loosely coupled way.


To output a form, use the elgg_view_form function like so:

.. code:: php
   
   echo elgg_view_form('example');

Doing this generates something like the following markup:

.. code:: html

   <form action="http://localhost/elgg/action/example">
     <fieldset>
       <input type="hidden" name="__elgg_ts" value="1234567890" />
       <input type="hidden" name="__elgg_token" value="3874acfc283d90e34" />
     </fieldset>
   </form>

Elgg does some things automatically for you when you generate forms this way:

 1. It sets the action to the appropriate URL based on the name of the action you pass to it
 2. It adds some anti-csrf tokens (``__elgg_ts`` and ``__elgg_token``) to help keep your actions secure
 3. It automatically looks for the body of the form in the ``forms/example`` view.

Put the content of your form in your plugin’s ``forms/example`` view:

.. code:: php

   // /mod/example/views/default/forms/example.php
   echo elgg_view('input/text', array('name' => 'example'));
   echo elgg_view('input/submit');

Now when you call ``elgg_view_form('example')``, Elgg will produce:

.. code:: html

   <form action="http://localhost/elgg/action/example">
     <fieldset>
       <input type="hidden" name="__elgg_ts" value="...">
       <input type="hidden" name="__elgg_token" value="...">
 
       <input type="text" class="elgg-input-text" name="example">
       <input type="submit" class="elgg-button elgg-button-submit" value="Submit">
     </fieldset>
   </form>

Files and images
================

Use the input/file view in your form’s content view.

.. code:: php

   // /mod/example/views/default/forms/example.php
   echo elgg_view(‘input/file’, array(‘name’ => ‘icon’));

Set the enctype of the form to multipart/form-data:

.. code:: php

   echo elgg_view_form(‘example’, array(
     ‘enctype’ => ‘multipart/form-data’
   ));

In your action file, use the ``$_FILES`` global to access the uploaded file:

.. code:: php

   $icon = $_FILES[‘icon’]

Sticky forms
============

Sticky forms are forms that retain user input if saving fails. They are "sticky" because the user's data "sticks" in the form after submitting, though it was never saved to the database. This greatly improves the user experience by minimizing data loss. Elgg 1.8 includes helper functions so you can make any form sticky.

Helper functions
----------------

Sticky forms are implemented in Elgg 1.8 by the following functions:

``elgg_make_sticky_form($name)``
Tells the engine to make all input on a form sticky.

``elgg_clear_sticky_form($name)``
Tells the engine to discard all sticky input on a form.

``elgg_is_sticky_form($name)``
Checks if $name is a valid sticky form.

``elgg_get_sticky_values($name)``
Returns all sticky values saved for $name by elgg_make_sticky_form().

Overview
--------

The basic flow of using sticky forms is:
Call ``elgg_make_sticky_form($name)`` at the top of actions for forms you want to be sticky.
Use ``elgg_is_sticky_form($name)`` and ``elgg_get_sticky_values($name)`` to get sticky values when rendering a form view.
Call ``elgg_clear_sticky_form($name)`` after the action has completed successfully or after data has been loaded by ``elgg_get_sticky_values($name)``.

Example: User registration
--------------------------

Simple sticky forms require little logic to determine the input values for the form. This logic is placed at the top of the form body view itself.

The registration form view first sets default values for inputs, then checks if there are sticky values. If so, it loads the sticky values before clearing the sticky form:

.. code:: php

   // views/default/forms/register.php
   $password = $password2 = '';
   $username = get_input('u');
   $email = get_input('e');
   $name = get_input('n');
 
   if (elgg_is_sticky_form('register')) {
	extract(elgg_get_sticky_values('register'));
	elgg_clear_sticky_form('register');
   }

The registration action sets creates the sticky form and clears it once the action is completed:

.. code:: php

   // actions/register.php
   elgg_make_sticky_form('register');
 
   ...
 
   $guid = register_user($username, $password, $name, $email, false, $friend_guid, $invitecode);
 
   if ($guid) {
	elgg_clear_sticky_form('register');
	....
   }

Example: Bookmarks
------------------

The bundled plugin Bookmarks' save form and action is an example of a complex sticky form.

The form view for the save bookmark action uses ``elgg_extract()`` to pull values from the ``$vars`` array:

.. code:: php

   // mod/bookmarks/views/default/forms/bookmarks/save.php
   $title = elgg_extract('title', $vars, '');
   $desc = elgg_extract('description', $vars, '');
   $address = elgg_extract('address', $vars, '');
   $tags = elgg_extract('tags', $vars, '');
   $access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
   $container_guid = elgg_extract('container_guid', $vars);
   $guid = elgg_extract('guid', $vars, null);
   $shares = elgg_extract('shares', $vars, array());

The page handler scripts prepares the form variables and calls ``elgg_view_form()`` passing the correct values:

.. code:: php

   // mod/bookmarks/pages/add.php
   $vars = bookmarks_prepare_form_vars();
   $content = elgg_view_form('bookmarks/save', array(), $vars);
   
Similarly, ``mod/bookmarks/pages/edit.php`` uses the same function, but passes the entity that is being edited as an argument:

.. code:: php

   $bookmark_guid = get_input('guid');
   $bookmark = get_entity($bookmark_guid);

   ...
 
   $vars = bookmarks_prepare_form_vars($bookmark);
   $content = elgg_view_form('bookmarks/save', array(), $vars);

The library file defines ``bookmarks_prepare_form_vars()``. This function accepts an ``ElggEntity`` as an argument and does 3 things:

 1. Defines the input names and default values for form inputs.
 2. Extracts the values from a bookmark object if it's passed. 
 3. Extracts the values from a sticky form if it exists.

TODO: Include directly from lib/bookmarks.php

.. code:: php

   // mod/bookmarks/lib/bookmarks.php
   function bookmarks_prepare_form_vars($bookmark = null) {
   	// input names => defaults
     $values = array(
       'title' => get_input('title', ''), // bookmarklet support
       'address' => get_input('address', ''),
       'description' => '',
       'access_id' => ACCESS_DEFAULT,
       'tags' => '',
       'shares' => array(),
       'container_guid' => elgg_get_page_owner_guid(),
       'guid' => null,
       'entity' => $bookmark,
     );
 
     if ($bookmark) {
	  foreach (array_keys($values) as $field) {
          if (isset($bookmark->$field)) {
            $values[$field] = $bookmark->$field;
          }
       }
     }

     if (elgg_is_sticky_form('bookmarks')) {
	  $sticky_values = elgg_get_sticky_values('bookmarks');
	  foreach ($sticky_values as $key => $value) {
         $values[$key] = $value;
       }
     }

     elgg_clear_sticky_form('bookmarks');
 
     return $values;
   }

The save action checks the input, then clears the sticky form upon success:

.. code:: php

   // mod/bookmarks/actions/bookmarks/save.php
   elgg_make_sticky_form('bookmarks');
   ...
 
   if ($bookmark->save()) {
	elgg_clear_sticky_form('bookmarks');
   }


Ajax
====

See the :doc:`Ajax guide</guides/ajax>` for instructions on calling actions from JavaScript.

Security
========
For enhanced security, all actions require an CSRF token. Calls to action URLs that do not include security tokens will be ignored and a warning will be generated.

A few views and functions automatically generate security tokens:

.. code:: php

   elgg_view('output/url', array('is_action' => TRUE));
   elgg_view('input/securitytoken');
   $url = elgg_add_action_tokens_to_url("http://localhost/elgg/action/example");

In rare cases, you may need to generate tokens manually:

.. code:: php

   $__elgg_ts = time();
   $__elgg_token = generate_action_token($__elgg_ts);

You can also access the tokens from javascript:

.. code:: js

   elgg.security.token.__elgg_ts;
   elgg.security.token.__elgg_token;

These are refreshed periodically so should always be up-to-date.


Security Tokens
===============
On occasion we need to pass data through an untrusted party or generate an "unguessable token" based on some data.
The industry-standard `HMAC <http://security.stackexchange.com/a/20301/4982>`_ algorithm is the right tool for this.
It allows us to verify that received data were generated by our site, and were not tampered with. Note that even
strong hash functions like SHA-2 should *not* be used without HMAC for these tasks.

Elgg provides ``elgg_build_hmac()`` to generate and validate HMAC message authentication codes that are unguessable
without the site's private key.

.. code:: php

    // generate a querystring such that $a and $b can't be altered
    $a = 1234;
    $b = "hello";
    $query = http_build_query([
        'a' => $a,
        'b' => $b,
        'mac' => elgg_build_hmac([$a, $b])->getToken(),
    ]);
    $url = "action/foo?$query";


    // validate the querystring
    $a = (int) get_input('a', '', false);
    $b = (string) get_input('b', '', false);
    $mac = get_input('mac', '', false);

    if (elgg_build_hmac([$a, $b])->matchesToken($mac)) {
        // $a and $b have not been altered
    }

Note: If you use a non-string as HMAC data, you must use types consistently. Consider the following:

.. code:: php

    $mac = elgg_build_hmac([123, 456])->getToken();

    // type of first array element differs
    elgg_build_hmac(["123", 456])->matchesToken($mac); // false

    // types identical to original
    elgg_build_hmac([123, 456])->matchesToken($mac); // true
