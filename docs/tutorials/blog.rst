Building a Blog Plugin
######################

Build a simple blogging site using Elgg.

This duplicates features in the bundled ``blog`` plugin,
so be sure to disable that while working on your own version.

.. contents:: Contents
   :local:
   :depth: 1

Prerequisites:

 - :doc:`Install Elgg</intro/install>`

Create a page for composing the blogs
=====================================

Create the file ``/views/default/resources/my_blog/add.php``.

.. code:: php

    <?php
    // make sure only logged in users can see this page 
    gatekeeper();
                    
    // set the title
    // be sure to use ``elgg_echo()`` for internationalization if you need it
    $title = "Create a new my_blog post";

    // start building the main column of the page
    $content = elgg_view_title($title);

    // add the form to this section
    $content .= elgg_view_form("my_blog/save");

    // optionally, add the content for the sidebar
    $sidebar = "";

    // layout the page
    $body = elgg_view_layout('one_sidebar', array(
       'content' => $content,
       'sidebar' => $sidebar
    ));

    // draw the page, including the HTML wrapper and basic page layout
    echo elgg_view_page($title, $body);

Create the form for creating a new my\_blog post
================================================

Create a file at ``/views/default/forms/my_blog/save.php``
that contains the form body. This corresponds to view that is called above:
``elgg_view_form("my_blog/save")``.

The form should have input fields for the title, body and tags.
Because you used ``elgg_view_form()``, you do not need to include form tag markup.
The view will be automatically wrapped with:

 * a ``<form>`` tag and the necessary attributes
 * anti-csrf tokens

The form's action will be ``"<?= elgg_get_site_url() ?>action/my_blog/save"``,
which we will create in a moment. Here is the content of
``/views/default/forms/my_blog/save.php``:

.. code:: php

    echo elgg_view_input('text', [
        'name' => 'title',
        'label' => elgg_echo('title'),
        'required' => true,
    ]);

    echo elgg_view_input('longtext', [
        'name' => 'body',
        'label' => elgg_echo('body'),
        'required' => true,
    ]);

    echo elgg_view_input('tags', [
        'name' => 'tags',
        'label' => elgg_echo('tags'),
        'help' => elgg_echo('tags:help'),
    ]);

    echo elgg_view_input('submit', array(
        'value' => elgg_echo('save'),
        'field_class' => 'elgg-foot',
    ));


Notice how the form is calling ``elgg_view_input()`` to render inputs. This helper function maintains
consistency in field markup, and is used as a shortcut for rendering field elements, such as label,
help text, and input. See :doc:`/guides/actions`.

You can see a complete list of input views in the ``/vendor/elgg/elgg/views/default/input/`` directory.

The action file
===============

Create the file ``/actions/my_blog/save.php``.
This will save the blog post to the database.

.. code:: php

    <?php
    // get the form inputs
    $title = get_input('title');
    $body = get_input('body');
    $tags = string_to_tag_array(get_input('tags'));

    // create a new my_blog object
    $blog = new ElggObject();
    $blog->subtype = "my_blog";
    $blog->title = $title;
    $blog->description = $body;

    // for now make all my_blog posts public
    $blog->access_id = ACCESS_PUBLIC;

    // owner is logged in user
    $blog->owner_guid = elgg_get_logged_in_user_guid();

    // save tags as metadata
    $blog->tags = $tags;

    // save to database and get id of the new my_blog
    $blog_guid = $blog->save();

    // if the my_blog was saved, we want to display the new post
    // otherwise, we want to register an error and forward back to the form
    if ($blog_guid) {
       system_message("Your blog post was saved");
       forward($blog->getURL());
    } else {
       register_error("The blog post could not be saved");
       forward(REFERER); // REFERER is a global variable that defines the previous page
    }

A few fields are built into Elgg objects. Title and description are two of these.
It makes sense to use description to contain the my\_blog text.
Every entity can have a subtype and in this we are using ``"my_blog"``.
The tags are stored as metadata.

Every object in Elgg has a built-in URL automatically,
although you can override this if you wish.
The ``getURL()`` method is called to get that unique URL.

The object view
===============

Elgg will automatically call the ``object/my_blog`` view to view the
my\_blog post so we need to create the object view.

Objects in Elgg are a subclass of something called an “entity”.
Users, sites, and groups are also subclasses of entity.
All entities can (and should) have a subtype,
which allows granular control for listing and displaying.
Here, we have used the subtype "``my_blog``\ " to identify a my\_blog post,
but any alphanumeric string can be a valid subtype.
When picking subtypes, be sure to pick ones that make sense for your plugin.

Create the file ``/views/default/object/my_blog.php``.

Each my\_blog post will be passed to this PHP file as
``$vars['entity']``. (``$vars`` is an array used in the views system to
pass variables to a view.) The content of ``object/my_blog.php`` can
just be something like:

.. code:: php

    <?php
    
    echo elgg_view_title($vars['entity']->title);
    echo elgg_view('output/longtext', array('value' => $vars['entity']->description));
    echo elgg_view('output/tags', array('tags' => $vars['entity']->tags)); 

The last line takes the tags on the my\_blog post and automatically
displays them as a series of clickable links. Search is handled
automatically.

(If you're wondering about the '``default``\ ' in ``/views/default/``,
you can create alternative views. RSS, OpenDD, FOAF, mobile and others
are all valid view types.)

start.php
================

For this example, we just need to register the action file we created earlier:
Also see a related guide about :doc:`/guides/actions`.

.. code:: php

    <?php
    
    elgg_register_action("my_blog/save", __DIR__ . "/actions/my_blog/save.php");

The action will now be available as ``/action/my_blog/save``.
By default, all actions are available only to logged in users.
If you want to make an action available to only admins or open it up to unauthenticated users,
you can pass 'admin' or 'public' as the third parameter of ``elgg_register_action()``, respectively.

Registering a page handler
==========================

In order to be able to serve the page that generates the form, you'll
need to register a page handler. Add the following to your start.php:

.. code:: php

    elgg_register_page_handler('my_blog', 'my_blog_page_handler');

    function my_blog_page_handler($segments) {
        if ($segments[0] == 'add') {
            echo elgg_view_resource('my_blog/add');
            return true;
        }
        return false;
    }

Page handling functions need to return ``true`` or ``false``. ``true``
means the page exists and has been handled by the page handler.
``false`` means that the page does not exist and the user will be
forwarded to the site's 404 page (requested page does not exist or not found).
In this particular example, the URL must contain
``/my_blog/add`` for the user to view a page with a form, otherwise the
user will see a 404 page.

Trying it out
=============

The page to create a new my\_blog post should be accessible at ``https://elgg.example.com/my_blog/add``.

Displaying list of my\_blogs
============================

Let's also create a page that lists my\_blog entries that have been created.

Create ``/views/default/resources/my_blog/all.php``.

To grab the latest my\_blog posts, we'll use ``elgg_list_entities``.
Note that this function returns only the posts that the user can see,
so access restrictions are handled transparently:

.. code:: php

    $body = elgg_list_entities(array(
        'type' => 'object',
        'subtype' => 'my_blog',
    ));

The function \`elgg\_list\_entities\` (and its cousins) also
transparently handle pagination, and even create an RSS feeds for your
my\_blogs if you have defined these views.

Finally, we'll draw the page:

.. code:: php

    $body = elgg_view_layout('one_column', array('content' => $body));

    echo elgg_view_page("All Site Blogs", $body);

We will then need to modify our my\_blog page handler to grab the new
page when the URL is set to ``/my_blog/all``. So, your new
``my_blog_page_handler()`` function in start.php should look like:

.. code:: php

    function my_blog_page_handler($segments) {
        switch ($segments[0]) {
            case 'add':
               echo elgg_view_resource('my_blog/add');
               break;

            case 'all':
            default:
               echo elgg_view_resource('my_blog/all');
               break;
        }
        
        return true;
    }

Now, if the URL contains just ``/my_blog`` or ``/my_blog/all``,
the user will see an "All Site Blogs" page.

A user's blog page
==================

If we grab the Global Unique IDentifier (GUID) of the logged in user, we
can limit the my\_blog posts to those posted by specifying the
owner\_guid argument in the list function above.

.. code:: php

    echo elgg_list_entities(array(
        'type' => 'object',
        'subtype' => 'my_blog',
        'owner_guid' => elgg_get_logged_in_user_guid()
    ));


The end
=======

There's much more that could be done,
but hopefully this gives you a good idea of how to get started with your own.
