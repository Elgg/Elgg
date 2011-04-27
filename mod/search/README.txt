= Elgg Search =

Full text search developer's reference.

== Contents ==

1. Overview
2. Search and Custom Plugins
3. Controlling Search Results
	1. Entities Returned
	2. Custom Search
4. Controlling Search Views
	1. Entities
	2. Listing
	3. Layout
5. Hints and Quirks


== 1. Overview ==

All entities are searched through title and description using
MySQL's native fulltext search when possible, and `LIKE %...%` when not.
This can be overridden on a type/subtype basis.

Entities are displayed in a standard list view consisting of a 
title, blurb, and icon of the owning entity.  This can be overridden 
on a type/subtype basis.

Search is separated based upon types/subtypes pairs and any 
registered custom search.

**METADATA, ANNOTATIONS, AND PRIVATE DATA ARE NOT SEARCHED BY DEFAULT!**

These are used in a variety of ways by plugin authors and generally 
should not be displayed.  There are exceptions (profile fields and 
comments) but if a plugin needs to match against metadata, 
annotations, or private data it must register a search hook itself.


== 2. Search and Custom Plugins ==

To appear in search you must register your entity type and subtype
by saying in your plugin's init function:

	register_entity_type($type, $subtype);

If you are extending ElggObject with your own class, it is also advised 
to add a subtype in your plugin's run_once function by saying:

	add_subtype($type, $subtype, $class);

If your plugin uses ElggEntity's standard title and description, 
and you don't need a custom display, there is nothing else you need 
to do for your results to appear in search.  If you would like more
granular control of search, continue below.


== 3. Controlling Search Results ==

Search results can be controlled at a object:subtype level.
	
You can specify your own search types by responding to a hook.


=== 3.1 Controlling Search Results - Entities Returned ===

You can override the default search by responding to the search/type
or search/type:subtype hook.  Generally, you will be replying to 
search/object:subtype.

Search will first trigger a hook for search/type:subtype.  If no 
results are returned (but not FALSE, see below) a hook for search/type 
will be triggered.  

FALSE returned for any search hook will halt results for that 
type/subtype.

Register plugin hooks like this:

	register_plugin_hook('search', 'object:my_subtype',
		'my_subtype_search_hook');

The hooked function is provided with details about the search query
in $param.  These include:

* query
* offset
* limit
* search_type
* type - Entity type. (Not applicable for custom searches)
* subtype - Entity subtype.  (Not applicable for custom searches)
* owner_guid
* friends - Should only entities by friends of the logged in
	user be searched? (@todo)
* pagination - Show pagination?

The hooked function should respond to search triggers with the 
following:

	array(
		'count' => A count of ALL entities found,
		'entities' => An array of entities.
	)

This information is passed directly to the search view, so if you are 
registering your own custom hook, you can provide more 
information to display in your custom view. 

For each entity in the returned array, search expects two pieces of
volatile data: search_matched_title and search_matched_description.
Set these by saying:

	$entity->setVolatileData('data_name', 'data_value');

Again, if you are customizing your search views, you can add anything
you need.


=== 3.2  Controlling Search Results - Custom Search ===
	
Non-entities, including information from 3rd party applications,
can easily be included in search by registering a custom search hook
that responds to the search_types/get_types trigger:

	register_plugin_hook('search_types', 'get_types',
		'my_custom_search_hook_function');

In this function, append to the array sent in $value with the name of 
your custom search:

	function my_custom_search_hook_function($hook, $type,
		$value, $params) {

		$value[] = 'my_custom_search';
		return $value;
	}

Search will trigger a hook for search/my_custom_search, which your 
plugin should respond to as detailed in section 3.1 above.


== 4.0  Controlling Search Views ==
Three types views are used for displaying search: entity, listing, 
and layout.

Each view has a default that standardizes the display of entities
regardless of type, subtype, or search type.

The entity and listing views can be customized based upon a type,
subtype, or custom search type of the results.

The layout view can be customized based upon the original search 
type. NB: This can be different to the types for the results.

The entity view controls how each individual result is formatted.

The listing view controls how each group of listings is formatted.

The listing layout controls how each full result set is formatted.


=== 4.1  Controlling Search Views - Entities ===

The default view for entities is search/entity.

Search views are separate from the object/entity views because
view types might not match entity types.

The default search listing view iterates through each entity
found and passes to the entity view.  See 3.3 for more information
about listing views.

Entity views are discovered in the following order.  The first search
view found is used.

	search/type/subtype/entity (For entity-based searches only)
	search/type/entity
	search/entity

The following parameters are passed in $vars to the entity view by 
the default listing view:

	entity => The current returned entity
	results => The results from the search/type:subtype hook
	params => The params passed to the search/type:subtype hook

Example: To create an entity view for an ElggObject of subtype blog,
create a file called:

	views/default/search/object/blog/entity.php

To create an entity view for a custom search mysearch, create a file
called:

	views/default/search/mysearch/entity.php
	
	
=== 4.2  Controlling Search Views - Listing

The default search view is search/listing.

For each entity in the returned array, search expects two pieces of
volatile data: search_matched_title and search_matched_description.

Listing views are discovered in the following order.  The first 
search view found is used.
	search/type/subtype/listing (For entity-based searches only)
	search/type/listing
	search/listing

The view is called with the following in $vars:
	results => The results from the search/type:subtype hook
	params => The params passed to the search/type:subtype hook

Example: To create a listing view for ElggObjects with the subtype 
of blog, create a file called:

	views/default/search/object/blog/listing.php

To create a listing view for the custom search mysearch, create a file
called:

	views/default/search/mysearch/listing.php
		

=== 4.3  Controlling Search Views - Layout ===
	
The default layout view for search is search/layout, which calls
to `elgg_view_layout(two_column_left_sidebar', '', $entity_results);`

Layouts can be overridden only when not searching all entities.

Layout views are discovered in the following order.  The first search
view found is used.

	search/type/subtype/layout (For entity-based searches only)
	search/type/layout
	search/layout

The following parameters are passed in $vars to the layout view:

	body => The HTML formatted list of results.
	params => The original params for the search.

Example: To create a layout view for ElggObjects with the subtype 
of blog, create a file called:

	views/default/search/object/blog/layout.php

To create a layout view for the custom search mysearch, create a file
called:

	views/default/search/mysearch/layout.php


== 5.  Hints and Quirks ==

Use search_get_relevant_substring() to extract and highlight 
relevant substrings for the search_match_title and description.

If searching in 3rd party applications, create a temporary 
ElggObject to hold the results.  No need to save it since search 
uses volatile data.

	$entity = new ElggObject();
	$entity->owner_guid = use_magic_to_match_to_a_real_user();
	$entity->setVolatileData('search_matched_title',
		'3rd Party Integration');
	$entity->setVolatileData('search_matched_description',
		'Searching is fun!');

	return array(
		'count' => $count,
		'entities' => array($entity)
	);

MySQL's fulltext engine returns *ZERO* rows if more than 50% of 
the rows searched match.

The default search hooks for users and groups ignore subtypes.
See [trac ticket 1499](http://trac.elgg.org/elgg/ticket/1499)
