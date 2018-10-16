<?php

namespace Elgg\Search;

use ElggEntity;
use InvalidParameterException;

/**
 * Search plugin
 */
class Search {

	/**
	 * @var array
	 */
	protected $params;

	/**
	 * Constructor
	 *
	 * @param array $params Search params
	 */
	public function __construct(array $params = []) {
		$this->initParams($params);
	}

	/**
	 * Returns search params
	 * @return array
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * Prepares a new highlighter
	 * @return Highlighter
	 */
	public function getHighlighter() {
		return new Highlighter($this->getParams());
	}

	/**
	 * Returns searchable type/subtype pairs
	 *
	 * <code>
	 * [
	 *    'user' => [],
	 *    'object' => [
	 *       'blog',
	 *    ]
	 * ]
	 * </code>
	 *
	 * @return array
	 */
	public function getTypeSubtypePairs() {
		$type_subtype_pairs = get_registered_entity_types();

		if (_elgg_services()->hooks->hasHandler('search_types', 'get_queries')) {
			elgg_deprecated_notice("
			'search_types','get_queries' hook has been deprecated.
			Use 'search:config','type_subtype_pairs' hook.
			", '3.0');
			$type_subtype_pairs = elgg_trigger_plugin_hook('search_types', 'get_queries', $this->params, $type_subtype_pairs);
		}

		return elgg_trigger_plugin_hook('search:config', 'type_subtype_pairs', $this->params, $type_subtype_pairs);
	}

	/**
	 * Returns search types
	 *
	 * @return array
	 */
	public function getSearchTypes() {
		$search_types = [];

		if (_elgg_services()->hooks->hasHandler('search_types', 'get_types')) {
			elgg_deprecated_notice("
			'search_types','get_types' hook has been deprecated.
			Use 'search:config','search_types' hook.
			", '3.0');
			$search_types = elgg_trigger_plugin_hook('search_types', 'get_types', $this->params, $search_types);
		}

		return elgg_trigger_plugin_hook('search:config', 'search_types', $this->params, $search_types);
	}

	/**
	 * Populate search-related volatile data
	 *
	 * @param ElggEntity $entity Found entity
	 *
	 * @return void
	 */
	public function prepareEntity(ElggEntity $entity) {
		$formatter = new Formatter($entity, $this);
		$formatter->format();
	}

	/**
	 * List search results for given search type
	 *
	 * @param string $search_type Search type
	 * @param string $type        Entity type
	 * @param string $subtype     Subtype
	 * @param bool   $count       Count
	 *
	 * @return int|string
	 * @throws InvalidParameterException
	 */
	public function listResults($search_type, $type = null, $subtype = null, $count = false) {
		$current_params = $this->params;
		
		$current_params['search_type'] = $search_type;
		$current_params['type'] = $type;
		$current_params['subtype'] = $subtype;
		
		// normalizing current search params so the listing has better awareness
		$current_params = _elgg_services()->search->normalizeOptions($current_params);

		switch ($search_type) {
			case 'entities' :
				if ($subtype && _elgg_services()->hooks->hasHandler('search', "$type:$subtype")) {
					$hook_type = "$type:$subtype";
				} else {
					$hook_type = $type;
				}
				break;

			default :
				$hook_type = $search_type;
				break;
		}

		$results = [
			'entities' => [],
			'count' => 0,
		];

		if (_elgg_services()->hooks->hasHandler('search', $hook_type)) {
			elgg_deprecated_notice("
			'search','$hook_type' plugin hook has been deprecated and may be removed.
			Please consult the documentation for the new core search API
			and update your use of search hooks.
		", '3.0');
			$results = elgg_trigger_plugin_hook('search', $hook_type, $current_params, $results);
			if ($count) {
				return (int) $results['count'];
			}
		} else {
			$current_params['count'] = true;
			$results['count'] = (int) elgg_search($current_params);
			if ($count) {
				return $results['count'];
			}
			if (!empty($results['count'])) {
				unset($current_params['count']);
				$results['entities'] = elgg_search($current_params);
			}
		}

		if (empty($results['entities'])) {
			return '';
		}

		return elgg_view('search/list', [
			'results' => $results,
			'params' => $current_params,
		]);

	}

	/**
	 * Returns the name of the view to render an item in search results
	 *
	 * @param \Loggable $item Optional item to get the type/subtype of
	 *
	 * @return string
	 */
	public function getSearchView(\Loggable $item = null) {
		
		if ($item instanceof \Loggable) {
			$type = $item->getType();
			$subtype = $item->getSubtype();
		} else {
			$type = elgg_extract('type', $this->params);
			$subtype = elgg_extract('subtype', $this->params);
		}
		
		$search_type = elgg_extract('search_type', $this->params);

		$views = [
			"search/$search_type/$type/$subtype",
			"search/$search_type/$type/default",
			"search/$type/$subtype/entity", // BC
			"search/$type/entity", // BC
			"search/$type/$subtype",
			"search/$type/default",
			"search/$search_type/entity", // BC
		];

		foreach ($views as $view) {
			if (elgg_view_exists($view)) {
				return $view;
			}
		}

		return '';
	}

	/**
	 * Prepare search params from request query elements
	 *
	 * @param array $params Params
	 *
	 * @return void
	 */
	protected function initParams(array $params = []) {

		// $search_type == all || entities || trigger plugin hook
		$search_type = get_input('search_type', 'all');
		if ($search_type == 'tags') {
			elgg_deprecated_notice('"tags" search type has been deprecated. By default, "entities" search performs search within registered tags.', '3.0');
			$search_type = 'entities';
			$partial_match = false;
			$fields = get_input('tag_names');
			if (!$fields) {
				$fields = (array) elgg_get_registered_tag_metadata_names();
			}
		} else {
			$partial_match = true;
			$fields = get_input('fields');
		}

		$query = get_input('q', get_input('tag', ''));

		if (preg_match('/\"(.*)\"/i', $query)) {
			// if query is quoted, e.g. "elgg has been released", perform literal search
			$tokenize = false;
			$query = preg_replace('/\"(.*)\"/i', '$1', $query);
		} else {
			$tokenize = true;
		}

		if ($search_type == 'all') {
			// We only display 2 results per search type
			$limit = 2;
			$offset = 0;
			$pagination = false;
		} else {
			$limit = max((int) get_input('limit'), elgg_get_config('default_limit'));
			$offset = get_input('offset', 0);
			$pagination = true;
		}

		$entity_type = get_input('entity_type', ELGG_ENTITIES_ANY_VALUE);
		if ($entity_type) {
			$entity_subtype = get_input('entity_subtype', ELGG_ENTITIES_ANY_VALUE);
		} else {
			$entity_subtype = ELGG_ENTITIES_ANY_VALUE;
		}

		$owner_guid = get_input('owner_guid', ELGG_ENTITIES_ANY_VALUE);
		$container_guid = get_input('container_guid', ELGG_ENTITIES_ANY_VALUE);

		$default_order = 'desc';
		$sort = get_input('sort', 'time_created');
		switch ($sort) {
			case 'action_on' :
				$sort = 'last_action';
				break;

			case 'created' :
				$sort = 'time_created';
				break;

			case 'updated' :
				$sort = 'time_updated';
				break;

			case 'alpha' :
				$default_order = 'asc';
				$sort = 'name';
				break;
		}

		$order = get_input('order', $default_order);
		
		$current_params = [
			'query' => $query,
			'offset' => $offset,
			'limit' => $limit,
			'sort' => $sort,
			'order' => $order,
			'search_type' => $search_type,
			'fields' => $fields,
			'partial_match' => $partial_match,
			'tokenize' => $tokenize,
			'type' => $entity_type,
			'subtype' => $entity_subtype,
			'owner_guid' => $owner_guid,
			'container_guid' => $container_guid,
			'pagination' => $pagination,
		];

		$params = array_merge($current_params, $params);
		
		// normalizing here to set query_parts
		$this->params = _elgg_services()->search->normalizeOptions($params);
		
		// unsetting some data which will be reset during actual search
		if (empty($params['fields'])) {
			// no fields provided by input, so unset the magic fields from normalilzation
			unset($this->params['fields']);
		}
		unset($this->params['_elgg_search_service_normalize_options']);
	}
}
