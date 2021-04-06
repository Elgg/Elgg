Building a Blog Plugin
######################

This tutorial will teach you how to create a simple blog plugin.
The basic functions of the blog will be creating posts,
saving them and viewing them.
The plugin duplicates features that are found in the
bundled ``blog`` plugin.
You can disable the bundled ``blog`` plugin if you wish,
but it is not necessary since the features do not conflict
each other.

.. contents:: Contents
   :local:
   :depth: 1

Prerequisites:

 - :doc:`Install Elgg</intro/install>`

Create the plugin's directory and composer file
===============================================

First, choose a simple and descriptive name for your plugin.
In this tutorial, the name will be ``my_blog``.
Then, create a directory for your plugin in the ``/mod/`` directory
found in your Elgg installation directory. Other plugins are also located
in ``/mod/``. In this case, the name of the directory should
be ``/mod/my_blog/``. This directory is the root of your plugin and all the
files that you create for the new plugin will go somewhere under it.

Next, in the root of the plugin, create the plugin's composer file,
``composer.json``.

See :doc:`Plugins</guides/plugins>` for more information
about the composer file.

Create the form for creating a new blog post
============================================

Create a file at ``/mod/my_blog/views/default/forms/my_blog/save.php``
that contains the form body. The form should have input fields for the title,
body and tags of the my_blog post. It does not need form tag markup.

.. code-block:: php

    echo elgg_view_field([
        '#type' => 'text',
        '#label' => elgg_echo('title'),
        'name' => 'title',
        'required' => true,
    ]);

    echo elgg_view_field([
        '#type' => 'longtext',
        '#label' => elgg_echo('body'),
        'name' => 'body',
        'required' => true,
    ]);

    echo elgg_view_field([
        '#type' => 'tags',
        '#label' => elgg_echo('tags'),
        '#help' => elgg_echo('tags:help'),
        'name' => 'tags',
    ]);

    $submit = elgg_view_field(array(
        '#type' => 'submit',
        '#class' => 'elgg-foot',
        'value' => elgg_echo('save'),
    ));
    elgg_set_form_footer($submit);


Notice how the form is calling ``elgg_view_field()`` to render inputs. This helper
function maintains consistency in field markup, and is used as a shortcut for
rendering field elements, such as label, help text, and input. See :doc:`/guides/actions`.

You can see a complete list of input views in the
``/vendor/elgg/elgg/views/default/input/`` directory.

It is recommended that you make your plugin translatable by using ``elgg_echo()``
whenever there is a string of text that will be shown to the user. Read more at
:doc:`Internationalization</guides/i18n>`.

Create a page for composing the blogs
=====================================

Create the file ``/mod/my_blog/views/default/resources/my_blog/add.php``.
This page will view the form you created in the above section.

.. code-block:: php

    <?php
                   
    // set the title
    $title = "Create a new my_blog post";

    // add the form to the main column
    $content = elgg_view_form("my_blog/save");

    // optionally, add the content for the sidebar
    $sidebar = "";

    // draw the page, including the HTML wrapper and basic page layout
    echo elgg_view_page($title, [
		'content' => $content,
		'sidebar' => $sidebar
    ]);

The function ``elgg_view_form("my_blog/save")`` views the form that
you created in the previous section. It also automatically wraps
the form with a ``<form>`` tag and the necessary attributes as well
as anti-csrf tokens.

The form's action will be ``"<?= elgg_get_site_url() ?>action/my_blog/save"``.

Create the action file for saving the blog post
===============================================

The action file will save the my_blog post to the database.
Create the file ``/mod/my_blog/actions/my_blog/save.php``:

.. code-block:: php

    <?php
    // get the form inputs
    $title = elgg_get_title_input('title');
    $body = get_input('body');
    $tags = string_to_tag_array(get_input('tags'));

    // create a new my_blog object and put the content in it
    $blog = new ElggObject();
    $blog->title = $title;
    $blog->description = $body;
    $blog->tags = $tags;

    // the object can and should have a subtype
    $blog->setSubtype('my_blog');
    
    // for now, make all my_blog posts public
    $blog->access_id = ACCESS_PUBLIC;

    // owner is logged in user
    $blog->owner_guid = elgg_get_logged_in_user_guid();

    // save to database
    // if the my_blog was saved, we want to display the new post
    // otherwise, we want to register an error and forward back to the form
    if ($blog->save()) {
       return elgg_ok_response('', "Your blog post was saved.", $blog->getURL());
    } else {
       return elgg_error_response("The blog post could not be saved.");
    }

As you can see in the above code, Elgg objects have several fields built
into them. The title of the my_blog post is stored
in the ``title`` field while the body is stored in the
``description`` field. There is also a field for tags which are stored as
metadata.

Objects in Elgg are a subclass of something called an "entity".
Users, sites, and groups are also subclasses of entity.
An entity's subtype allows granular control for listing and displaying,
which is why every entity should have a subtype.
In this tutorial, the subtype "``my_blog``\ " identifies a my\_blog post,
but any alphanumeric string can be a valid subtype.
When picking subtypes, be sure to pick ones that make sense for your plugin.

Create elgg-plugin.php
======================

The ``/mod/my_blog/elgg-plugin.php`` file is used to declare various functionalities of the plugin.
It can, for example, be used to configure entities, actions, widgets and routes.

.. code-block:: php

	<?php

	return [
		'entities' => [
			[
				'type' => 'object',
				'subtype' => 'my_blog',
				'searchable' => true,
			],
		],
		'actions' => [
			'my_blog/save' => [],
		],
		'routes' => [
			'view:object:blog' => [
				'path' => '/my_blog/view/{guid}/{title?}',
				'resource' => 'my_blog/view',
			],
			'add:object:blog' => [
				'path' => '/my_blog/add/{guid?}',
				'resource' => 'my_blog/add',
			],
			'edit:object:blog' => [
				'path' => '/my_blog/edit/{guid}/{revision?}',
				'resource' => 'my_blog/edit',
				'requirements' => [
					'revision' => '\d+',
				],
			],
		],
	];

Registering the save action will make it available as ``/action/my_blog/save``.
By default, all actions are available only to logged in users.
If you want to make an action available to only admins or open it up to unauthenticated users,
you can pass ``['access' => 'admin']`` or ``['access' => 'public']`` when registering the action.

.. _tutorials/blog#view:

Create a page for viewing a blog post
=====================================

To be able to view a my_blog post on its own page, you need to make a view page.
Create the file ``/mod/my_blog/views/default/resources/my_blog/view.php``:

.. code-block:: php

    <?php

    // get the entity
    $guid = elgg_extract('guid', $vars);
    $my_blog = get_entity($guid);

    // get the content of the post
    $content = elgg_view_entity($my_blog, array('full_view' => true));

    echo elgg_view_page($my_blog->getDisplayName(), [
        'content' => $content,
    ]);

This page has much in common with the ``add.php`` page. The biggest differences
are that some information is extracted from the my_blog entity, and instead of
viewing a form, the function ``elgg_view_entity`` is called. This function
gives the information of the entity to something called the object view.

Create the object view
======================

When ``elgg_view_entity`` is called or when my_blogs are viewed in a list
for example, the object view will generate the appropriate content.
Create the file ``/mod/my_blog/views/default/object/my_blog.php``:

.. code-block:: php

    <?php
    
    echo elgg_view('output/longtext', array('value' => $vars['entity']->description));
    echo elgg_view('output/tags', array('tags' => $vars['entity']->tags)); 

As you can see in the previous section, each my\_blog post is passed to the object
view as ``$vars['entity']``. (``$vars`` is an array used in the views system to
pass variables to a view.)

The last line takes the tags on the my\_blog post and automatically
displays them as a series of clickable links. Search is handled
automatically.

(If you're wondering about the "``default``" in ``/views/default/``,
you can create alternative views. RSS, OpenDD, FOAF, mobile and others
are all valid view types.)

Trying it out
=============

Go to your Elgg site's administration page, list the plugins and activate
the ``my_blog`` plugin.

The page to create a new my\_blog post should now be accessible at
``https://elgg.example.com/my_blog/add``, and after successfully saving the post,
you should see it viewed on its own page.

Displaying a list of blog posts
===============================

Let's also create a page that lists my\_blog entries that have been created.

Create ``/mod/my_blog/views/default/resources/my_blog/all.php``:

.. code-block:: php

    <?php
    $titlebar = "All Site My_Blogs";
    $pagetitle = "List of all my_blogs";

    $body = elgg_list_entities(array(
        'type' => 'object',
        'subtype' => 'my_blog',
    ));

    echo elgg_view_page($titlebar, [
    	'title' => $pagetitle,
    	'content' => $body,
    ]);

The ``elgg_list_entities`` function grabs the latest my_blog posts and
passes them to the object view file.
Note that this function returns only the posts that the user can see,
so access restrictions are handled transparently.
The function (and its cousins) also
transparently handles pagination and even creates an RSS feed for your
my\_blogs if you have defined that view.

The list function can also limit the my_blog posts to those of a specified user.
For example, the function ``elgg_get_logged_in_user_guid`` grabs the Global Unique
IDentifier (GUID) of the logged in user, and by giving that to
``elgg_list_entities``, the list only displays the posts of the current user:

.. code-block:: php

    echo elgg_list_entities(array(
        'type' => 'object',
        'subtype' => 'my_blog',
        'owner_guid' => elgg_get_logged_in_user_guid()
    ));

Next, you will need to register your route to return the new
page when the URL is set to ``/my_blog/all``. Configure the ``routes`` section
in ``elgg-plugin.php`` to contain the following:

.. code-block:: php

	'routes' => [
		'collection:object:my_blog:all' => [
			'path' => '/my_blog/all',
			'resource' => 'my_blog/all',
		],
	],
    
Now, if the URL contains ``/my_blog/all``, the user will see an "All Site My_Blogs" page.

You might also want to update the object view to handle different kinds of viewing,
because otherwise the list of all my_blogs will also show the full content of all my_blogs.
Change ``/mod/my_blog/views/default/object/my_blog.php`` to look like this:

.. code-block:: php

    <?php
    $full = elgg_extract('full_view', $vars, FALSE);

    // full view
    if ($full) {
        echo elgg_view('output/longtext', array('value' => $vars['entity']->description));
        echo elgg_view('output/tags', array('tags' => $vars['entity']->tags));

    // list view or short view
    } else {
        // make a link out of the post's title
        echo elgg_view_title(
            elgg_view('output/url', array(
                'href' => $vars['entity']->getURL(),
                'text' => $vars['entity']->getDisplayName(),
                'is_trusted' => true,
        )));
        echo elgg_view('output/tags', array('tags' => $vars['entity']->tags));
    }

Now, if ``full_view`` is ``true`` (as it was pre-emptively set to be in
:ref:`this section <tutorials/blog#view>`), the object view will show
the post's content and tags (the title is shown by ``view.php``).
Otherwise the object view will render just the title and
tags of the post.

The end
=======

There's much more that could be done,
but hopefully this gives you a good idea of how to get started.
