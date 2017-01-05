Search
######

.. contents:: Contents
   :local:
   :depth: 2


Entity search
-------------

Elgg core provides flexible ``elgg_search()``, which prepares custom search clauses and utilizes ``elgg_get_entities()`` to fetch the results.

In addition to all parameters accepted by ``elgg_get_entities()``, ``elgg_search()`` accepts the following:

 * ``query``         search query
 * ``fields``        an array of metadata and attribute names to search in
                     Default fields include:
                       ``name``, ``username``, ``description`` as well as all profile fields and registered tags for users
                       ``name``, ``description`` as well as all profile fields and registered tags for groups
                       ``title``, ``description`` as well as registered tags for objects
 * ``sort``          name of the field to sort by
                     Any of the search fields can be used to sort search results
                     Note that sorting by metadata fields may not be very efficient
 * ``order``         Sorting direction (ASC|DESC)
 * ``type``          entity types to search
 * ``subtype``       entity subtypes to search
 * ``search_type``   custom search type (required if not ``type`` is provided)
 * ``partial_match`` Allow partial matches
                     By default partial matches are allowed, meaning that ``elgg`` will be matched when search for ``el``
                     Exact matches may be helpful when you want to match tag values, e.g. when you want to find all objects that are ``red`` and not ``darkred``
 * ``tokenize``      Break down search query into tokens
                     By default search queries are tokenized, meaning that we will match ``elgg has been released`` when searching for ``elgg released``


.. code:: php

	// List all users whose username or address matches ``us``

	$options = [
		'type' => 'user',
		'query' => 'us',
		'fields' => ['username', 'address'],
	];

	echo elgg_list_entities($options, 'elgg_search');


Search fields
-------------

You can customize search fields for each entity type/subtype, using ``search::fields`` hook:

.. code:: php

	// Let's remove search in location and add address field instead
	elgg_register_plugin_hook_handler('search:fields', 'user', 'my_plugin_search_user_fields');

	function my_plugin_search_user_fields(\Elgg\Hook $hook) {
		$fields = $hook->getValue();
		$location_key = array_search('location', $fields);
		if ($location_key) {
			unset($fields[$location_key]);
		}

		$fields[] = 'address';
		return $fields;
	}


Searchable types
----------------

To register an entity type for search, use ``elgg_register_entity_type()``.
To combine search results or filter how search results are presented in the search plugin, use ``'search:config', 'type_subtype_pairs'`` hook.

.. code:: php

	// Let's add places and place reviews as public facing entities
	elgg_register_entity_type('object', 'place');
	elgg_register_entity_type('object', 'place_review');

	// Now let's include place reviews in the search results for places
	elgg_register_plugin_hook_handler('search:options', 'object:place', 'my_plugin_place_search_options');
	elgg_register_plugin_hook_handler('search:config', 'type_subtype_pairs', 'my_plugin_place_search_config');

	// Add place review to search options as a subtype
	function my_plugin_place_search_options($hook, $type, $value, $params) {

		if (empty($params) || !is_array($params)) {
			return;
		}

		if (isset($params['subtypes'])) {
			$subtypes = (array) $params['subtypes'];
		} else {
			$subtypes = (array) elgg_extract('subtype', $params);
		}

		if (!in_array('place', $subtypes)) {
			return;
		}

		unset($params["subtype"]);

		$subtypes[] = 'place_review';
		$params['subtypes'] = $subtypes;

		return $params;
	}

	// Remove place reviews as a separate entry in search sections
	function my_plugin_place_search_config(\Elgg\Hook $hook) {

		$types = $hook->getValue();

		if (empty($types['object'])) {
			return;
		}

		foreach ($types['object'] as $key => $subtype) {
			if ($subtype == 'place_review') {
				unset($types['object'][$key]);
			}
		}

		return $types;
	}


Custom search types
-------------------

Elgg core only supports entity search. You can implement custom searches, e.g. using search query as a location and listing entities by proximity to that location.


.. code:: php

	// Let's added proximity search type
	elgg_register_plugin_hook_handler('search:config', 'search_types', function(\Elgg\Hook $hook) {
		$search_types = $hook->getValue();
		$search_types[] = 'promimity';
	});

	// Let's add search options that will look for entities that have geo coordinates and order them by proximity to the query location
	elgg_register_plugin_hook_handler('search:options', 'proximity', function(\Elgg\Hook $hook) {

		$query = $hook->getParam('query');
		$options = $hook->getValue();

		// Let's presume we have a geocoding API
		$coords = geocode($query);

		$lat = $coords['lat'];
		$long = $coords['long'];

		$options['joins']['mdlat'] = "JOIN {$dbprefix}metadata mdlat on e.guid = mdlat.entity_guid AND mdlat.name = 'geo:lat'";
		$options['joins']['mdlong'] = "JOIN {$dbprefix}metadata mdlong on e.guid = mdlong.entity_guid AND mdlong.name = 'geo:long'";

		$options['selects']['proximity'] = "(((acos(sin(($lat*pi()/180))
			*sin((mdlat.value*pi()/180))+cos(($lat*pi()/180))
			*cos((mdlat.value*pi()/180))
			*cos((($long-mdlong.value)*pi()/180)))))*180/pi())*60*1.1515*1.60934 AS proximity";

		$options['order_by'] = "proximity ASC, e.time_updated DESC";

		return $options;
	});


Autocomplete and livesearch endpoint
------------------------------------

Core provides a JSON endpoint for searching users and groups. These endpoints are used by ``input/autocomplete`` and ``input/userpicker`` views.

.. code:: php

	// Get JSON results of a group search for 'class'
	$json = file_get_contents('http://example.com/livesearch/groups?view=json&q=class');


You can add custom search types, by adding a corresponding resource view:

.. code:: php

	// Let's add an endpoint that will search for users that are not members of a search group
	// and render a userpicker for our invite form

	echo elgg_view('input/userpicker' [
		'handler' => 'livesearch/non_members',
		'options' => [
			// this will be sent as URL query elements
			'group_guid' => $group_guid,
		],
	]);

	// To enable /livesearch/non_members endpoint, we need to add a view
	// in /views/json/resources/livesearch/non_members.php

	$limit = get_input('limit', elgg_get_config('default_limit'));
	$query = get_input('term', get_input('q'));
	$input_name = get_input('name');

	// We have passed this value to our input view, and we want to make sure
	// external scripts are not using it to mine data on group members
	// so let's validate the HMAC that was generated by the userpicker input
	$group_guid = (int) get_input('group_guid');

	$data = [
		'group_guid' => $group_guid,
	];

	// let's sort by key, in case we have more elements
	ksort($data);

	$hmac = elgg_build_hmac($data);
	if (!$hmac->matchesToken(get_input('mac')) {
		// request does not originate from our input view
		forward('', '404');
	}

	elgg_set_http_header("Content-Type: application/json;charset=utf-8");

	$dbprefix = elgg_get_config('dbprefix');
	$options = [
		'query' => $query,
		'type' => 'user',
		'limit' => $limit,
		'sort' => 'name',
		'order' => 'ASC',
		'fields' => ['name', 'username'],
		'item_view' => 'search/entity',
		'input_name' => $input_name,
		'wheres' => [
			"
				NOT EXISTS(
					SELECT 1 FROM {$dbprefix}entity_relationships
					WHERE guid_one = e.guid
					AND relationship = 'member'
					AND guid_two = $group_guid
				)
			"
		],
	];

	echo elgg_list_entities($options, 'elgg_search');


