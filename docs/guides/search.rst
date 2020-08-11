Search
######

.. contents:: Contents
   :local:
   :depth: 2


Entity search
-------------

Elgg core provides flexible ``elgg_search()``, which prepares custom search clauses and utilizes ``elgg_get_entities()`` to fetch the results.

In addition to all parameters accepted by ``elgg_get_entities()``, ``elgg_search()`` accepts the following:

 * ``query``         Search query
 * ``fields``        An array of names by property type to search in (see example below)
 * ``sort``          An array containing sorting options, including `property`, `property_type` and `direction`
 * ``type``          Entity type to search
 * ``subtype``       Optional entity subtype to search
 * ``search_type``   Custom search type (required if no ``type`` is provided)
 * ``partial_match`` Allow partial matches
                     By default partial matches are allowed, meaning that ``elgg`` will be matched when searching for ``el``
                     Exact matches may be helpful when you want to match tag values, e.g. when you want to find all objects that are ``red`` and not ``darkred``
 * ``tokenize``      Break down search query into tokens
                     By default search queries are tokenized, meaning that we will match ``elgg has been released`` when searching for ``elgg released``


.. code-block:: php

    // List all users who list United States as their address or mention it in their description
    $options = [
        'type' => 'user',
        'query' => 'us',
        'fields' => [
            'metadata' => ['description'],
            'annotations' => ['location'],
        ],
        'sort' => [
            'property' => 'zipcode',
            'property_type' => 'annotation',
            'direction' => 'asc',
        ]
    ];

    echo elgg_list_entities($options, 'elgg_search');


Search fields
-------------

You can customize search fields for each entity type/subtype, using ``search:fields`` hook:

.. code-block:: php

    // Let's remove search in location and add address field instead
    elgg_register_plugin_hook_handler('search:fields', 'user', 'my_plugin_search_user_fields');

    function my_plugin_search_user_fields(\Elgg\Hook $hook) {
        $fields = $hook->getValue();
        $location_key = array_search('location', $fields['annotations']);
        if ($location_key) {
            unset($fields[$location_key]['annotations']);
        }

        $fields['metadata'][] = 'address';

        return $fields;
    }


Searchable types
----------------

To register an entity type for search, use ``elgg_register_entity_type()``, or do so when defining an entity type in ``elgg-plugin.php``.
To combine search results or filter how search results are presented in the search plugin, use ``'search:config', 'type_subtype_pairs'`` hook.

.. code-block:: php

    // Let's add places and place reviews as public facing entities
    elgg_register_entity_type('object', 'place');
    elgg_register_entity_type('object', 'place_review');

    // Now let's include place reviews in the search results for places
    elgg_register_plugin_hook_handler('search:options', 'object:place', 'my_plugin_place_search_options');
    elgg_register_plugin_hook_handler('search:config', 'type_subtype_pairs', 'my_plugin_place_search_config');

    // Add place review to search options as a subtype
    function my_plugin_place_search_options(\Elgg\Hook $hook) {

        $params = $hook->getParams();
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


.. code-block:: php

    // Let's added proximity search type
    elgg_register_plugin_hook_handler('search:config', 'search_types', function (\Elgg\Hook $hook) {
        $search_types = $hook->getValue();
        $search_types[] = 'promimity';

        return $search_types;
    });

    // Let's add search options that will look for entities that have geo coordinates and order them by proximity to the query location
    elgg_register_plugin_hook_handler('search:options', 'proximity', function (\Elgg\Hook $hook) {

        $query = $hook->getParam('query');
        $options = $hook->getValue();

        // Let's presume we have a geocoding API
        $coords = geocode($query);

        // We are not using standard 'selects' options here, because counting queries do not use custom selects
        $options['wheres']['proximity'] = function (QueryBuilder $qb, $alias) use ($lat, $long) {
            $dblat = $qb->joinMetadataTable($alias, 'guid', 'geo:lat');
            $dblong = $qb->joinMetadataTable($alias, 'guid', 'geo:long');

            $qb->addSelect("(((acos(sin(($lat*pi()/180))
                        *sin(($dblat.value*pi()/180)) + cos(($lat*pi()/180))
                        *cos(($dblat.value*pi()/180))
                        *cos((($long-$dblong.value)*pi()/180)))))*180/pi())
                        *60*1.1515*1.60934
                        AS proximity");

            $qb->orderBy('proximity', 'asc');

            return $qb->merge([
                $qb->compare("$dblat.value", 'is not null'),
                $qb->compare("$dblong.value", 'is not null'),
            ]);
        };

        return $options;
    });


Autocomplete and livesearch endpoint
------------------------------------

Core provides a JSON endpoint for searching users and groups. These endpoints are used by ``input/autocomplete`` and ``input/userpicker`` views.

.. code-block:: php

    // Get JSON results of a group search for 'class'
    $json = file_get_contents('http://example.com/livesearch/groups?view=json&q=class');


You can add custom search types, by adding a corresponding resource view:

.. code-block:: php

    // Let's add an endpoint that will search for users that are not members of a group
    // and render a userpicker for our invite form
    echo elgg_view('input/userpicker', [
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
    if (!$hmac->matchesToken(get_input('mac'))) {
         // request does not originate from our input view
         throw new \Elgg\Exceptions\Http\EntityPermissionsException(); 
    }

    elgg_set_http_header("Content-Type: application/json;charset=utf-8");

    $options = [
        'query' => $query,
        'type' => 'user',
        'limit' => $limit,
        'sort' => 'name',
        'order' => 'ASC',
        'fields' => [
            'metadata' => ['name', 'username'],
        ],
        'item_view' => 'search/entity',
        'input_name' => $input_name,
        'wheres' => function (QueryBuilder $qb) use ($group_guid) {
            $subquery = $qb->subquery('entity_relationships', 'er');
            $subquery->select('1')
                ->where($qb->compare('er.guid_one', '=', 'e.guid'))
                ->andWhere($qb->compare('er.relationship', '=', 'member', ELGG_VALUE_STRING))
                ->andWhere($qb->compare('er.guid_two', '=', $group_guid, ELGG_VALUE_INTEGER));

            return "NOT EXISTS ({$subquery->getSQL()})";
        }
    ];

    echo elgg_list_entities($options, 'elgg_search');


