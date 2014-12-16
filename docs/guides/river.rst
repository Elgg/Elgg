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

.. code:: php

	<?php

	elgg_create_river_item(array(
		'view' => 'river/object/blog/create',
		'action_type' => 'create',
		'subject_guid' => $blog->owner_guid,
		'object_guid' => $blog->getGUID(),
	));

All available parameters:

* ``view`` => STR The view that will handle the river item (must exist)
* ``action_type`` => STR An arbitrary string to define the action (e.g. 'create', 'update', 'vote', 'review', etc)
* ``subject_guid`` => INT The GUID of the entity doing the action
* ``object_guid`` => INT The GUID of the entity being acted upon
* ``target_guid`` => INT The GUID of the the object entity's container (optional)
* ``access_id`` => INT The access ID of the river item (default: same as the object)
* ``posted`` => INT The UNIX epoch timestamp of the river item (default: now)
* ``annotation_id`` => INT The annotation ID associated with this river entry (optional)

When an item is deleted or changed, the river item will be updated automatically.

River views
===========

In order for events to appear in the river you need to provide a corresponding
:doc:`view <views>` with the name specified in the function above.

We recommend ``/river/{type}/{subtype}/{action}``, where:

* {type} is the entity type of the content we're interested in (``object`` for objects, ``user`` for users, etc)
* {subtype} is the entity subtype of the content we're interested in (``blog`` for blogs, ``photo_album`` for albums, etc)
* {action} is the action that took place (''create'', ''update'', etc)

River item information will be passed in an object called ``$vars['item']``, which contains the following important parameters:

* ``$vars['item']->subject_guid`` The GUID of the user performing the action
* ``$vars['item']->object_guid`` The GUID of the entity being acted upon

Timestamps etc will be generated for you.

For example, the blog plugin uses the following code for its river view:

.. code:: php

	<?php

	$object = $vars['item']->getObjectEntity();

	$excerpt = $object->excerpt ? $object->excerpt : $object->description;
	$excerpt = strip_tags($excerpt);
	$excerpt = elgg_get_excerpt($excerpt);

	echo elgg_view('river/elements/layout', array(
		'item' => $vars['item'],
		'message' => $excerpt,
	));
