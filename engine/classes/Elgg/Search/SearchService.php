<?php

namespace Elgg\Search;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use elgg_search() instead.
 *
 * @access private
 * @since 3.0
 */
class SearchService {

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var PluginHooksService
	 */
	private $hooks;

	/**
	 * @var \Elgg\Database
	 */
	private $db;

	/**
	 * Constructor
	 *
	 * @param \Elgg\Config             $config Config
	 * @param \Elgg\PluginHooksService $hooks  Hook registration service
	 * @param \Elgg\Database           $db     Database
	 */
	public function __construct(\Elgg\Config $config, \Elgg\PluginHooksService $hooks, \Elgg\Database $db) {
		$this->config = $config;
		$this->hooks = $hooks;
		$this->db = $db;
	}

	/**
	 * Returns search results as an array of entities, as a batch, or a count,
	 * depending on parameters given.
	 * Returns false if not results were found.
	 *
	 * @see elgg_get_entities()
	 * 
	 * @param array $params Search parameters
	 *                         Accepts all options supported by the elgg_get_entities(), and:
	 *                         - query         STR      Search query
	 *                         - type          STR      Entity type
	 *                                                  Required, if no search type is set
	 *                         - search_type   STR      Custom search type
	 *                                                  Required, if no type is set
	 *                         - fields        ARRAY    Metadata/attribute fields to search in
	 *                         - sort          STRING   Field to sort by
	 *                         - order         STRING   Sorting order (ASC|DESC)
	 *                         - partial_match BOOL Allow partial matches (e.g. find 'elgg' for 'el' search)
	 *                         - tokenize      BOOL Break down search query into tokens (e.g. find 'elgg has been released' for 'elgg released')
	 * @return ElggEntity[]|int|false
	 */
	public function search(array $params = []) {

		$search_type = elgg_extract('search_type', $params, 'entities');
		if (!$search_type) {
			return false;
		}

		$params = $this->hooks->trigger('search:params', $search_type, $params, $params);

		$query = elgg_extract('query', $params);
		if (empty($query)) {
			return false;
		}

		$params['query'] = sanitize_string($query);

		if ($search_type == 'entities') {
			return $this->searchEntities($params);
		}
		
		return $this->searchCustom($params);
	}

	/**
	 * Perform entity search
	 *
	 * @param array $params Search params
	 * @return ElggEntity[]|int|false
	 */
	public function searchEntities(array $params = []) {
		$entity_type = elgg_extract('type', $params);
		if (!in_array($entity_type, $this->config->get('entity_types'))) {
			throw new \InvalidParameterException("'$entity_type' is not a valid entity type");
		}

		$entity_subtype = elgg_extract('subtype', $params);

		$fields = $this->hooks->trigger('search:fields', "$entity_type", $params, []);
		if ($entity_subtype) {
			$fields = $this->hooks->trigger('search:fields', "$entity_type:$entity_subtype", $params, $fields);
		}

		if (!isset($params['fields'])) {
			$params['fields'] = $fields;
		} else {
			// only allow known fields
			$params['fields'] = array_intersect($fields, $params['fields']);
		}

		if (empty($fields)) {
			return false;
		}

		$clauses = _elgg_services()->search->getClauses($params);
		if (!$clauses) {
			return false;
		}
		
		foreach (['wheres', 'joins'] as $ctype) {
			if (isset($clauses[$ctype])) {
				$params[$ctype] = (array) elgg_extract($ctype, $params);
				$params[$ctype] = array_merge($params[$ctype], $clauses[$ctype]);
			}
		}

		if (isset($clauses['order_by'])) {
			$params['order_by'] = $clauses['order_by'];
		}

		if ($this->hooks->hasHandler('search:options', "$entity_type")) {
			$params = $this->hooks->trigger('search:options', "$entity_type", $params, $params);
		}

		if ($entity_subtype && $this->hooks->hasHandler('search:options', "$entity_type:$entity_subtype")) {
			$params = $this->hooks->trigger('search:options', "$entity_type:$entity_subtype", $params, $params);
		}

		if (!$params) {
			return false;
		}

		return elgg_get_entities($params);
	}

	/**
	 * Perform custom search
	 *
	 * @param array $params Search params
	 * @return ElggEntity[]|int|false
	 */
	public function searchCustom(array $params = []) {
		$search_type = elgg_extract('search_type', $params);
		$fields = $this->hooks->trigger('search:fields', "$search_type", $params, $fields);
		if (!isset($params['fields'])) {
			$params['fields'] = $fields;
		} else {
			// only allow known fields
			$params['fields'] = array_intersect($fields, $params['fields']);
		}

		$options = false;
		if ($this->hooks->hasHandler('search:options', "$search_type")) {
			$options = $this->hooks->trigger('search:options', "$search_type", $params, $params);
		}

		if (!$options) {
			return false;
		}

		return elgg_get_entities($options);
	}

	/**
	 * Prepare default entity search clauses
	 *
	 * @param array $params Entity search params
	 * @return array|false
	 */
	public function getClauses(array $params = []) {

		$type = elgg_extract('type', $params);
		$sort = elgg_extract('sort', $params, 'time_created');
		$order = strtoupper(elgg_extract('order', $params, 'DESC'));
		$fields = elgg_extract('fields', $params);
		$query = elgg_extract('query', $params);
		$partial = elgg_extract('partial_match', $params, true);
		$tokenize = elgg_extract('tokenize', $params, true);
		
		$clauses = [
			'wheres' => [],
			'joins' => [],
		];

		switch ($type) {
			case 'user' :
				$table = 'users_entity';
				$secondary_attributes = array_keys(\ElggUser::getExternalAttributes());
				if (!elgg_is_admin_logged_in()) {
					$clauses['wheres'][] = "ct.banned = 'no'";
				}
				break;

			case 'group' :
				$table = 'groups_entity';
				$secondary_attributes = array_keys(\ElggGroup::getExternalAttributes());
				break;

			case 'object' :
				$table = 'objects_entity';
				$secondary_attributes = array_keys(\ElggObject::getExternalAttributes());
				break;

			default :
				return false;
		}

		$primary_attributes = array_keys(\ElggEntity::getPrimaryAttributes());

		if (!in_array($order, ['ASC', 'DESC'])) {
			$order = 'DESC';
		}

		$order_by = null;
		$order_by_metadata = null;
		if (in_array($sort, $primary_attributes)) {
			$clauses['order_by'] = "e.{$sort} $order";
		} else if (in_array($sort, $secondary_attributes)) {
			$clauses['order_by'] = "ct.{$sort} $order";
		} else {
			$order_by = elgg_extract('order_by', $params);
			$order_by_metadata = [
				'name' => $sort,
				'direction' => $order,
			];
		}

		$clauses['joins'][] = "JOIN {$this->db->prefix}{$table} ct ON e.guid = ct.guid";

		foreach ($fields as $field) {
			if (in_array($field, $primary_attributes)) {
				// we do not allow search in primary attributes
			} else if (in_array($field, $secondary_attributes)) {
				$attribute_fields[] = $field;
			} else {
				$metadata_fields[] = $field;
			}
		}

		if (!empty($attribute_fields)) {
			$attributes_where = $this->getWhereSQL('ct', $attribute_fields, $query, $partial, $tokenize);
		}

		if (!empty($metadata_fields)) {
			$clauses['joins'][] = "JOIN {$this->db->prefix}metadata md on e.guid = md.entity_guid";

			// get the where clauses for the md names
			// can't use egef_metadata() because the n_table join comes too late.
			$md_clauses = _elgg_entities_get_metastrings_options('metadata', [
				'metadata_names' => $metadata_fields,
				'order_by_metadata' => $order_by_metadata,
				'order_by' => $order_by,
			]);

			$clauses['joins'] = array_merge($clauses['joins'], $md_clauses['joins']);

			$metadata_where = $this->getWhereSQL('md', ['value'], $query, $partial, $tokenize);
			$clauses['wheres'][] = "(($attributes_where) OR ((({$md_clauses['wheres'][0]}) AND $metadata_where)))";
		} else {
			$clauses['wheres'][] = $attributes_where;
		}

		return $clauses;
	}

	/**
	 * Returns a where clause for a search query.
	 *
	 * @param string $table         Prefix for table to search on
	 * @param array  $fields        Fields to match against
	 * @param string $query         Search query
	 * @param bool   $partial_match Allow partial matches
	 * @param bool   $tokenize      Tokenize search query into separate words
	 * @return string
	 */
	public function getWhereSQL($table, $fields, $query, $partial_match = true, $tokenize = true) {

		// add the table prefix to the fields
		foreach ($fields as $i => $field) {
			if ($table) {
				$fields[$i] = "$table.$field";
			}
		}

		$likes = [];

		if ($tokenize) {
			$query_parts = explode(' ', $query);
			foreach ($fields as $field) {
				$sublikes = [];

				foreach ($query_parts as $query_part) {
					$query_part = sanitise_string($query_part);

					if (strlen($query_part) == 0) {
						continue;
					}

					if ($partial_match) {
						$sublikes[] = "$field LIKE '%$query_part%'";
					} else {
						$sublikes[] = "$field LIKE '$query_part'";
					}
				}

				$likes[] = '(' . implode(' AND ', $sublikes) . ')';
			}
		} else {
			foreach ($fields as $field) {
				if ($partial_match) {
					$likes[] = "$field LIKE '%$query%'";
				} else {
					$likes[] = "$field LIKE '$query'";
				}
			}
		}

		$likes_str = implode(' OR ', $likes);

		return "($likes_str)";
	}

}
