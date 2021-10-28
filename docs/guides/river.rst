River
#####

Elgg natively supports the "river", an activity stream containing descriptions
of activities performed by site members. This page gives an overview of adding
events to the river in an Elgg plugin. 

Pushing river items
===================

Items are pushed to the activity river through a function call, which you must
include in your plugins for the items to appear.

Here we add a river item telling that a user has created a new blog post:

.. code-block:: php

	<?php

	elgg_create_river_item([
		'view' => 'river/object/blog/create',
		'action_type' => 'create',
		'subject_guid' => $blog->owner_guid,
		'object_guid' => $blog->getGUID(),
	]);

All available parameters:

* ``view`` => STR The view that will handle the river item (must exist)
* ``action_type`` => STR An arbitrary string to define the action (e.g. 'create', 'update', 'vote', 'review', etc)
* ``subject_guid`` => INT The GUID of the entity doing the action (default: the logged in user guid)
* ``object_guid`` => INT The GUID of the entity being acted upon
* ``target_guid`` => INT The GUID of the object entity's container (optional)
* ``access_id`` => INT The access ID of the river item (default: same as the object)
* ``posted`` => INT The UNIX epoch timestamp of the river item (default: now)
* ``annotation_id`` => INT The annotation ID associated with this river entry (optional)

When an item is deleted or changed, the river item will be updated automatically.

River views
===========

As of Elgg 3.0 the ``view`` parameter is no longer required. A fallback logic has been created to check a series of views for you:

1. ``/river/{$type}/{$subtype}/{$action_type}``: eg. ``river/object/blog/create`` only the ``create`` action will come to this view 
2. ``river/{$type}/{$subtype}/default``: eg. ``river/object/blog/default`` all river activity for ``object`` ``blog`` will come here
3. ``river/{$type}/{$action_type}``: eg. ``river/object/create`` all ``create`` actions for ``object`` will come here
4. ``river/{$type}/default``: eg. ``river/object/default`` all actions for all ``object`` will come here
5. ``river/elements/layout``: ultimate fall back view, this should always be called in any of the river views to make a consistent layout

Both ``type`` and ``subtype`` are based on the ``type`` and ``subtype`` of the ``object_guid`` for which the river item was created.

Summary
-------

If no ``summary`` parameter is provided to the ``river/elements/layout`` the view will try to create it for you. The basic result will be a text
with the text `Somebody did something on Object`, where `Somebody` is based on ``subject_guid`` and `Object` is based on ``object_guid``. For both
`Somebody` and `Object` links will be created. These links are passed to a series of language keys so you can create a meaningfull summary.

The language keys are:

1. ``river:{$type}:{$subtype}:{$action_type}``: eg. ``river:object:blog:create``
2. ``river:{$type}:{$subtype}:default``: eg. ``river:object:blog:default``
3. ``river:{$type}:{$action_type}``: eg. ``river:object:create``
4. ``river:{$type}:default``: eg. ``river:object:default``

Custom river view
=================

If you wish to add some more information to the river view, like an attachment (image, YouTube embed, etc), you must specify the :doc:`view <views>` 
when creating the river item. This view **MUST** exist.

We recommend ``/river/{type}/{subtype}/{action}``, where:

* ``{type}`` is the entity type of the content we're interested in (``object`` for objects, ``user`` for users, etc)
* ``{subtype}`` is the entity subtype of the content we're interested in (``blog`` for blogs, ``photo_album`` for albums, etc)
* ``{action}`` is the action that took place (``create``, ``update``, etc)

River item information will be passed in an object called ``$vars['item']``, which contains the following important parameters:

* ``$vars['item']->subject_guid`` The GUID of the user performing the action
* ``$vars['item']->object_guid`` The GUID of the entity being acted upon

Timestamps etc will be generated for you.

For example, the blog plugin uses the following code for its river view:

.. code-block:: php

	$item = elgg_extract('item', $vars);
	if (!$item instanceof ElggRiverItem) {
		return;
	}
	
	$blog = $item->getObjectEntity();
	if (!$blog instanceof ElggBlog) {
		return;
	}
	
	$vars['message'] = $blog->getExcerpt();
	
	echo elgg_view('river/elements/layout', $vars);
