River
#####

Elgg natively supports an activity stream, also known as ``river``, 
containing descriptions of activities performed by site members. 
This page gives an overview of adding events to the river in an Elgg plugin. 

Pushing river items
===================

Items are pushed to the activity river when a river event occurs.
You can register a river event by calling ``elgg_register_river_event()``
River supports entity, annotation and relationship events.

.. code-block:: php

	// User X published a blog post Y
	elgg_register_river_event('publish', 'object', 'blog');

	// User X checked in at Y
	// Custom plugin event
	// In your plugin, you can trigger an event elgg_trigger_event('checkin', 'object', $place)
	elgg_register_river_event('checkin', 'object', 'place');
	elgg_register_river_event('poke', 'user');

	// User X reviewed Y with 5-stars
	// Annotation based event
	elgg_register_river_event('create', 'annotation', 'rating');

	// User X invited user Y to a group
	// Relationship based event
	elgg_register_river_event('create', 'relationship', 'invite');

When subject, object, target or result objects are deleted, the river item will be updated automatically.

River views
===========

Summary
-------

If no action/type specific ``summary`` view is present, the summary will be determined automatically from granular language keys.

The system will iterate through available translation strings to find the most precise match, so you can add your translation keys
based on granularity you need. Translations can use ``sprintf`` interpolation variables. Translations will receive the subject and object
of the activity, wrapped as anchor elements.

 * ``activity:{$action}:{$type}:{$subtype}``: eg. ``activity:create:object:blog`` or ``activity:comment:object:blog``
 * ``activity:{$action}:{$type}``: eg. ``activity:likes:object`` or ``activity:friend:user``
 * ``activity:{$action}``: eg. ``activity:review``

River elements
--------------

River items comprise of the following elements:

 * ``image`` - subject user avatar
 * ``summary`` - summary of the activity
 * ``message`` - message of the activity (usually an excerpt)
 * ``attachments`` - extended representation of the activity, which can include an object card, a media player, a file preview etc
 * ``responses`` - comment block

You can customize an of these with the granularity you need. For each of the following, the system will iterate through a set of views,
and default to core view, if none present.

 * ``river/<action>/<type>/<subtype>/<element>``
 * ``river/<action>/<type>/<element>``
 * ``river/<action>/<element>``

Use can further use ``format, river`` hook to override any of the above elements for a specific river item.