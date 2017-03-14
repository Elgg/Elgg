Search
######

Elgg provides a basic search feature.

Overview
--------

All entities are searched through title and description using
MySQL's native `'LIKE %...%'` queries.
This can be overridden on a type/subtype basis.

Search is separated based upon types/subtypes pairs and any 
registered custom search.

.. note::

	**METADATA, ANNOTATIONS, AND PRIVATE DATA ARE NOT SEARCHED BY DEFAULT!**

	These are used in a variety of ways by plugin authors and generally 
	should not be displayed. There are exceptions (profile fields for example) 
	but if a plugin needs to match against metadata, 
	annotations, or private data it can register a search hook itself.

Register content for search
---------------------------

To appear in search you must register your entity type and subtype
by saying in your plugin's init function:

``register_entity_type($type, $subtype);``

If your plugin uses ElggEntity's standard title and description, 
and you don't need a custom display, there is nothing else you need 
to do for your results to appear in search. If you would like more
granular control of search, continue below.

Executing a search
------------------

``elgg_search``

Influencing search
------------------

Search results can be controlled at a object:subtype level.
	


``params`` hook
``format`` hook
``prepare`` hook



Replacing search
----------------

You can specify your own search types by responding to a hook.

``search`` hook
