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

Actions must be registered before use.

There are two ways to register actions:

Using ``elgg_register_action()``

.. code-block:: php

   elgg_register_action("example", __DIR__ . "/actions/example.php");

The ``mod/example/actions/example.php`` script will now be run whenever a form is submitted to ``http://localhost/elgg/action/example``.

Use ``elgg-plugin.php``

.. code-block:: php

   return [
      'actions' => [
         // defaults to using an action file in /actions/myplugin/action_a.php
         'myplugin/action_a' => [
            'access' => 'public',
         ],

         // define custom action path
         'myplugin/action_b' => [
            'access' => 'admin',
            'filename' => __DIR__ . '/actions/action.php'
         ],

         // define a controller
         'myplugin/action_c' => [
            'controller' => \MyPlugin\Actions\ActionC::class,
         ],
      ],
   ];

.. warning:: 

	A stumbling point for many new developers is the URL for actions. The URL always uses ``/action/`` (singular) and 
	never ``/actions/`` (plural). However, action script files are usually saved under the directory ``/actions/`` (plural) 
	and always have an extension. Use ``elgg_generate_action_url()`` to avoid confusion.

Registering actions using plugin config file
--------------------------------------------
You can also register actions via the :doc:`elgg-plugin</guides/plugins>` config file. 
To do this you need to provide an action section in the config file. 
The location of the action files are assumed to be in the plugin folder  ``/actions``.

.. code-block:: php

	<?php

	return [
		'actions' => [
		    'blog/save' => [], // all defaults
		    'blog/delete' => [ // all custom
		          'access' => 'admin',
		          'filename' => __DIR__ . 'actions/blog/remove.php',
		    ],
		],
	];

Permissions
-----------
By default, actions are only available to logged in users.

To make an action available to logged out users, pass ``"public"`` as the third parameter:

.. code-block:: php

   elgg_register_action("example", $filepath, "public");

To restrict an action to only administrators, pass ``"admin"`` for the last parameter:

.. code-block:: php

   elgg_register_action("example", $filepath, "admin");

Writing action files
--------------------

Use the ``get_input()`` function to get access to request parameters:

.. code-block:: php

   $field = get_input('input_field_name', 'default_value');

You can then use the :doc:`database` api to load entities and perform actions on them accordingly.

To indicate a successful action, use ``elgg_ok_response()``. This function accepts data that you want to make available
to the client for XHR calls (this data will be ignored for non-XHR calls)

.. code-block:: php

   $user = get_entity($guid);
   // do something

   $action_data = [
      'entity' => $user,
      'stats' => [
          'friends' => $user->getFriends(['count' => true]);
      ],
   ];

   return elgg_ok_response($action_data, 'Action was successful', 'url/to/forward/to');


To indicate an error, use ``elgg_error_response()``

.. code-block:: php

   $user = elgg_get_logged_in_user_entity();
   if (!$user) {
      // show an error and forward the user to the referring page
      // send 404 error code on AJAX calls
      return elgg_error_response('User not found', REFERRER, ELGG_HTTP_NOT_FOUND);
   }

   if (!$user->canEdit()) {
      // show an error and forward to user's profile
      // send 403 error code on AJAX calls
      return elgg_error_response('You are not allowed to perform this action', $user->getURL(), ELGG_HTTP_FORBIDDEN);
   }

Customizing actions
-------------------

Before executing any action, Elgg triggers a hook:

.. code-block:: php

   $result = elgg_trigger_plugin_hook('action:validate', $action, null, true);

Where ``$action`` is the action being called. If the hook returns ``false`` then the action will not be executed. Don't return anything 
if your validation passes.

Example: Captcha
^^^^^^^^^^^^^^^^

The captcha module uses this to intercept the ``register`` and ``user/requestnewpassword`` actions and redirect them to a 
function which checks the captcha code. This check returns ``false`` if the captcha validation fails (which prevents the associated 
action from executing).

This is done as follows:

.. code-block:: php

   elgg_register_plugin_hook_handler("action:validate", "register", "captcha_verify_action_hook");
   elgg_register_plugin_hook_handler("action:validate", "user/requestnewpassword", "captcha_verify_action_hook");

   ...

   function captcha_verify_action_hook(\Elgg\Hook $hook) {
     $token = get_input('captcha_token');
     $input = get_input('captcha_input');

     if (($token) && (captcha_verify_captcha($input, $token))) {
       return;
     }

     register_error(elgg_echo('captcha:captchafail'));

     return false;
   }

This lets a plugin extend an existing action without the need to replace the whole action. In the case of the captcha plugin it 
allows the plugin to provide captcha support in a very loosely coupled way.

Actions available in core
=========================

``entity/delete``
-----------------

If your plugin does not implement any custom logic when deleting an entity, you can use bundled delete action

.. code-block:: php

   $guid = 123;
   // You can provide optional forward path as a URL query parameter
   $forward_url = 'path/to/forward/to';
   echo elgg_view('output/url', array(
      'text' => elgg_echo('delete'),
      'href' => elgg_generate_action_url('entity/delete', [
      	'guid' => $guid,
      	'forward_url' => $forward_url,
      ]),
      'confirm' => true,
   ));

You can customize the success message keys for your entity type and subtype, using ``"entity:delete:$type:$subtype:success"`` 
and ``"entity:delete:$type:success"`` keys.

.. code-block:: php

   // to add a custom message when a blog post or file is deleted
   // add the translations keys in your language files
   return [
      'entity:delete:object:blog:success' => 'Blog post has been deleted,
      'entity:delete:object:file:success' => 'File titled %s has been deleted',
   ];

Forms
=====

To output a form, use the elgg_view_form function like so:

.. code-block:: php
   
   echo elgg_view_form('example');

Doing this generates something like the following markup:

.. code-block:: html

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

.. code-block:: php

   // /mod/example/views/default/forms/example.php
   echo elgg_view('input/text', array('name' => 'example'));

   // defer form footer rendering
   // this will allow other plugins to extend forms/example view
   elgg_set_form_footer(elgg_view('input/submit'));

Now when you call ``elgg_view_form('example')``, Elgg will produce:

.. code-block:: html

   <form action="http://localhost/elgg/action/example">
     <fieldset>
       <input type="hidden" name="__elgg_ts" value="...">
       <input type="hidden" name="__elgg_token" value="...">

       <input type="text" class="elgg-input-text" name="example">
       <div class="elgg-foot elgg-form-footer">
           <input type="submit" class="elgg-button elgg-button-submit" value="Submit">
       </div>
     </fieldset>
   </form>

Inputs
------

To render a form input, use one of the bundled input views, which cover all standard
HTML input elements. See individual view files for a list of accepted parameters.

.. code-block:: php

   echo elgg_view('input/select', array(
      'required' => true,
      'name' => 'status',
      'options_values' => [
         'draft' => elgg_echo('status:draft'),
         'published' => elgg_echo('status:published'),
      ],
      // most input views will render additional parameters passed to the view
      // as tag attributes
      'data-rel' => 'blog',
   ));

The above example will render a dropdown select input:

.. code-block:: html

   <select required="required" name="status" data-rel="blog" class="elgg-input-select">
      <option value="draft">Draft</option>
      <option value="published">Published</option>
   </select>

To ensure consistency in field markup, use ``elgg_view_field()``, which accepts
all the parameters of the input being rendered, as well as ``#label`` and ``#help``
parameters (both of which are optional and accept HTML or text).

.. code-block:: php

   echo elgg_view_field([
      '#type' => 'select',
      '#label' => elgg_echo('blog:status:label'),
      '#help' => elgg_view_icon('help') . elgg_echo('blog:status:help'),
      'required' => true,
      'name' => 'status',
      'options_values' => [
         'draft' => elgg_echo('status:draft'),
         'published' => elgg_echo('status:published'),
      ],
      'data-rel' => 'blog',
   ]);

The above will generate the following markup:

.. code-block:: html

   <div class="elgg-field elgg-field-required">
      <label for="elgg-field-1" class="elgg-field-label">Blog status<span title="Required" class="elgg-required-indicator">*</span></label>
      <div class="elgg-field-input">
         <select required="required" name="status" data-rel="blog" id="elgg-field-1" class="elgg-input-select">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
         </select>
      </div>
      <div class="elgg-field-help elgg-text-help">
         <span class="elgg-icon-help elgg-icon"></span>This indicates whether or not the blog is visible in the feed
      </div>
   </div>

Input types
-----------

A list of bundled input types/views:

* ``input/text`` - renders a text input ``<input type="text">``
* ``input/plaintext`` - renders a textarea ``<textarea></textarea>``
* ``input/longtext`` - renders a WYSIWYG text input
* ``input/url`` - renders a url input ``<input type="url">``
* ``input/email`` - renders an email input ``<input type="email">``
* ``input/checkbox`` - renders a single checkbox ``<input type="checkbox">``
* ``input/checkboxes`` - renders a set of checkboxes with the same name
* ``input/radio`` - renders one or more radio buttons ``<input type="radio">``
* ``input/submit`` - renders a submit button ``<button type="submit">``
* ``input/button`` - renders a button ``<button></button>``
* ``input/file`` - renders a file input ``<input type="file">``
* ``input/select`` - renders a select input ``<select></select>``
* ``input/hidden`` - renders a hidden input ``<input type="hidden">``
* ``input/password`` - renders a password input ``<input type="password">``
* ``input/number`` - renders a number input ``<input type="number">``
* ``input/date`` - renders a jQuery datepicker

Elgg offers some helper input types

* ``input/access`` - renders an Elgg access level select
* ``input/tags`` - renders an Elgg tags input
* ``input/autocomplete`` - renders an Elgg entity autocomplete
* ``input/captcha`` - placeholder view for plugins to extend
* ``input/friendspicker`` - renders an Elgg friend autocomplete
* ``input/userpicker`` - renders an Elgg user autocomplete
* ``input/location`` renders an Elgg location input

Files and images
================

Use the ``input/file`` view in your form’s content view.

.. code-block:: php

   // /mod/example/views/default/forms/example.php
   echo elgg_view('input/file', ['name' => 'icon']);

If you wish to upload an icon for entity you can use the helper view ``entity/edit/icon``.
This view shows a file input for uploading a new icon for the entity, an thumbnail of the current icon and the option to remove the 
current icon.

The view supports some variables to control the output

* ``entity`` - the entity to add/remove the icon for. If provided based on this entity the thumbnail and remove option wil be shown
* ``entity_type`` - the entity type for which the icon will be uploaded. Plugins could find this useful, maybe to validate icon sizes
* ``entity_subtype`` - the entity subtype for which the icon will be uploaded. Plugins could find this useful, maybe to validate icon sizes
* ``icon_type`` - the type of the icon (default: icon)
* ``name`` - name of the input/file (default: icon)
* ``remove_name`` - name of the remove icon toggle (default: $vars['name'] . '_remove')
* ``required`` - is icon upload required (default: false)
* ``show_remove`` - show the remove icon option (default: true)
* ``show_thumb`` - show the thumb of the entity if available (default: true)
* ``thumb_size`` - the icon size to use as the thumb (default: medium)

If using the helper view you can use the following code in you action to save the icon to the entity or remove the current icon.

.. code-block:: php

   if (get_input('icon_remove')) {
      $entity->deleteIcon();
   } else {
      $entity->saveIconFromUploadedFile('icon');
   }

Set the enctype of the form to ``multipart/form-data``:

.. code-block:: php

   echo elgg_view_form('example', array(
     'enctype' => 'multipart/form-data'
   ));

.. note::

   The ``enctype`` of all forms that use the method ``POST`` defaults to ``multipart/form-data``. 

In your action file, use ``elgg_get_uploaded_file('your-input-name')`` to access the uploaded file:

.. code-block:: php

   $icon = elgg_get_uploaded_file('icon');

Sticky forms
============

Sticky forms are forms that retain user input if saving fails. They are "sticky" because the user's data "sticks" in the form 
after submitting, though it was never saved to the database. This greatly improves the user experience by minimizing data loss. 
Elgg includes helper functions so you can make any form sticky.

Helper functions
----------------

Sticky forms are implemented in Elgg by the following functions:

- ``elgg_make_sticky_form($name)`` - Tells the engine to make all input on a form sticky.
- ``elgg_clear_sticky_form($name)`` - Tells the engine to discard all sticky input on a form.
- ``elgg_is_sticky_form($name)`` - Checks if ``$name`` is a valid sticky form.
- ``elgg_get_sticky_values($name)`` - Returns all sticky values saved for ``$name`` by ``elgg_make_sticky_form($name)``.

Overview
--------

The basic flow of using sticky forms is:

1. Call ``elgg_make_sticky_form($name)`` at the top of actions for forms you want to be sticky.
2. Use ``elgg_is_sticky_form($name)`` and ``elgg_get_sticky_values($name)`` to get sticky values when rendering a form view.
3. Call ``elgg_clear_sticky_form($name)`` after the action has completed successfully or after data has been loaded by ``elgg_get_sticky_values($name)``.

Example: User registration
--------------------------

Simple sticky forms require little logic to determine the input values for the form. This logic is placed at the top of the form 
body view itself.

The registration form view first sets default values for inputs, then checks if there are sticky values. If so, it loads the 
sticky values before clearing the sticky form:

.. code-block:: php

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

.. code-block:: php

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

.. code-block:: php

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

.. code-block:: php

   // mod/bookmarks/pages/add.php
   $vars = bookmarks_prepare_form_vars();
   $content = elgg_view_form('bookmarks/save', array(), $vars);
   
Similarly, ``mod/bookmarks/pages/edit.php`` uses the same function, but passes the entity that is being edited as an argument:

.. code-block:: php

   $bookmark_guid = get_input('guid');
   $bookmark = get_entity($bookmark_guid);

   ...

   $vars = bookmarks_prepare_form_vars($bookmark);
   $content = elgg_view_form('bookmarks/save', array(), $vars);

The library file defines ``bookmarks_prepare_form_vars()``. This function accepts an ``ElggEntity`` as an argument and does 3 things:

 1. Defines the input names and default values for form inputs.
 2. Extracts the values from a bookmark object if it's passed. 
 3. Extracts the values from a sticky form if it exists.

.. code-block:: php

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

.. code-block:: php

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

For enhanced security, all actions require an CSRF token. Calls to action URLs that do not include security tokens 
will be ignored and a warning will be generated.

A few views and functions automatically generate security tokens:

.. code-block:: php

   elgg_view('output/url', array('is_action' => true));
   elgg_view('input/securitytoken');
   $url = elgg_add_action_tokens_to_url("http://localhost/elgg/action/example");
   $url = elgg_generate_action_url('myplugin/myaction');

In rare cases, you may need to generate tokens manually:

.. code-block:: php

   $__elgg_ts = elgg()->csrf->getCurrentTime()->getTimestamp();
   $__elgg_token = elgg()->csrf->generateActionToken($__elgg_ts);

You can also access the tokens from javascript:

.. code-block:: js

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

.. code-block:: php

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

.. code-block:: php

    $mac = elgg_build_hmac([123, 456])->getToken();

    // type of first array element differs
    elgg_build_hmac(["123", 456])->matchesToken($mac); // false

    // types identical to original
    elgg_build_hmac([123, 456])->matchesToken($mac); // true

Signed URLs
===========

Signed URLs offer a limited level of security for situations where action tokens are not suitable, for example when 
sending a confirmation link via email. URL signatures verify that the URL has been generated by your 
Elgg installation (using site secret) and that the URL query elements were not tampered with.

URLs a signed with an unguessable SHA-256 HMAC key. See `Security Tokens`_ for more details.

.. code-block:: php

    $url = elgg_http_add_url_query_element(elgg_normalize_url('confirm'), [
       'user_guid' => $user_guid,
    ]);

    $url = elgg_http_get_signed_url($url);

    notify_user($user_guid, $site->guid, 'Confirm', "Please confirm by clicking this link: $url");

.. warning::

   Signed URLs do not offer CSRF protection and should not be used instead of action tokens.
