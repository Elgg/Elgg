<?php

namespace Elgg\Traits\Database;

use Elgg\Config;
use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Clauses\AttributeWhereClause;
use Elgg\Database\Clauses\Clause;
use Elgg\Database\Clauses\EntitySortByClause;
use Elgg\Database\Clauses\GroupByClause;
use Elgg\Database\Clauses\HavingClause;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\MetadataWhereClause;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\Clauses\RelationshipWhereClause;
use Elgg\Database\Clauses\SelectClause;
use Elgg\Database\Clauses\WhereClause;
use Elgg\Exceptions\InvalidArgumentException;

/**
 * This trait serves as an adapter between legacy ege* options and new OO query builder
 *
 * @internal
 */
trait LegacyQueryOptionsAdapter {

	/**
	 * Normalizes legacy query options
	 *
	 * @param array $options Legacy ege* options
	 *
	 * @return array
	 */
	public function normalizeOptions(array $options = []): array {

		if (!isset($options['__original_options'])) {
			$options['__original_options'] = $options;
		}

		$options = array_merge($this->getDefaults(), $options);

		$options = $this->normalizeGuidOptions($options);
		$options = $this->normalizeTimeOptions($options);
		$options = $this->normalizeAccessOptions($options);
		$options = $this->normalizeTypeSubtypeOptions($options);
		$options = $this->normalizeRelationshipOptions($options);
		$options = $this->normalizeAnnotationOptions($options);
		$options = $this->normalizeMetadataOptions($options);
		$options = $this->normalizeMetadataSearchOptions($options);
		$options = $this->normalizeSelectClauses($options);
		$options = $this->normalizeWhereClauses($options);
		$options = $this->normalizeJoinClauses($options);
		$options = $this->normalizeGroupByClauses($options);
		$options = $this->normalizeHavingClauses($options);
		$options = $this->normalizeOrderByClauses($options);
		
		return $options;
	}

	/**
	 * Returns defaults array
	 *
	 * @return array
	 */
	protected function getDefaults(): array {
		return [
			'types' => null,
			'subtypes' => null,
			'type_subtype_pairs' => null,
			'guids' => null,
			'owner_guids' => null,
			'container_guids' => null,
			'access_ids' => null,

			'created_after' => null,
			'created_before' => null,
			'updated_after' => null,
			'updated_before' => null,
			'last_action_after' => null,
			'last_action_before' => null,

			'sort_by' => [],
			'order_by' => null,
			'count' => false,
			'limit' => elgg_get_config('default_limit'),
			'offset' => 0,

			'selects' => [],
			'wheres' => [],
			'joins' => [],
			'having' => null,
			'group_by' => null,

			'metadata_name_value_pairs' => null,
			'metadata_name_value_pairs_operator' => 'AND',
			'metadata_case_sensitive' => true,
			'metadata_ids' => null,
			'metadata_created_time_lower' => null,
			'metadata_created_time_upper' => null,
			'metadata_calculation' => null,

			'search_name_value_pairs' => null,

			'annotation_names' => null,
			'annotation_values' => null,
			'annotation_name_value_pairs' => null,
			'annotation_name_value_pairs_operator' => 'AND',
			'annotation_case_sensitive' => true,
			'annotation_ids' => null,
			'annotation_created_time_lower' => null,
			'annotation_created_time_upper' => null,
			'annotation_owner_guids' => null,
			'annotation_calculation' => null,

			'relationship_pairs' => [],

			'relationship' => null,
			'relationship_guid' => null,
			'inverse_relationship' => false,
			'relationship_join_on' => 'guid',
			'relationship_created_time_lower' => null,
			'relationship_created_time_upper' => null,

			'preload_owners' => false,
			'preload_containers' => false,
			'callback' => null,
			'distinct' => true,

			'batch' => false,
			'batch_inc_offset' => true,
			'batch_size' => 25,

			'__ElggBatch' => null,
		];
	}

	/**
	 * Normalize access options
	 *
	 * @param array $options Options
	 *
	 * @return array
	 */
	protected function normalizeAccessOptions(array $options = []): array {
		return $this->normalizePluralOptions($options, ['access_id']);
	}

	/**
	 * Normalizes type/subtype options
	 *
	 * @param array $options Options
	 *
	 * @return array
	 * @throws InvalidArgumentException
	 */
	protected function normalizeTypeSubtypeOptions(array $options = []): array {
		$options = $this->normalizePluralOptions($options, [
			'type',
			'subtype',
		]);

		// can't use helper function with type_subtype_pair because
		// it's already an array...just need to merge it
		if (isset($options['type_subtype_pair']) && isset($options['type_subtype_pairs'])) {
			$options['type_subtype_pairs'] = array_merge((array) $options['type_subtype_pairs'], (array) $options['type_subtype_pair']);
		} else if (isset($options['type_subtype_pair'])) {
			$options['type_subtype_pairs'] = (array) $options['type_subtype_pair'];
		} else if (isset($options['type_subtype_pairs'])) {
			$options['type_subtype_pairs'] = (array) $options['type_subtype_pairs'];
		} else if (isset($options['types'])) {
			$options['type_subtype_pairs'] = [];
			if ($options['types']) {
				foreach ((array) $options['types'] as $type) {
					$options['type_subtype_pairs'][$type] = isset($options['subtypes']) ? (array) $options['subtypes'] : null;
				}
			}
		} else if (isset($options['subtypes'])) {
			throw new InvalidArgumentException('If filtering for entity subtypes it is required to provide one or more entity types.');
		}

		if (isset($options['type_subtype_pairs']) && is_array($options['type_subtype_pairs'])) {
			foreach ($options['type_subtype_pairs'] as $type => $subtypes) {
				if (!in_array($type, Config::ENTITY_TYPES)) {
					elgg_log("'$type' is not a valid entity type", \Psr\Log\LogLevel::WARNING);
				}
				
				if (!empty($subtypes) && !is_array($subtypes)) {
					$options['type_subtype_pairs'][$type] = [$subtypes];
				}
			}
		}

		unset($options['type_subtype_pair']);
		unset($options['types']);
		unset($options['subtypes']);

		return $options;
	}

	/**
	 * Normalizes metadata options
	 *
	 * @param array $options Options
	 *
	 * @return array
	 */
	protected function normalizeMetadataOptions(array $options = []): array {
		$options = $this->normalizePluralOptions($options, [
			'metadata_id',
			'metadata_name',
			'metadata_value',
			'metadata_name_value_pair',
		]);

		$options = $this->normalizePairedOptions('metadata', $options);

		$props = [
			'metadata_ids',
			'metadata_created_after',
			'metadata_created_before',
		];

		foreach ($props as $prop) {
			if (isset($options[$prop]) && empty($options['metadata_name_value_pairs'])) {
				$options['metadata_name_value_pairs'][] = [
					$prop => $options[$prop]
				];
			}
		}

		foreach ($options['metadata_name_value_pairs'] as $key => $pair) {
			if ($pair instanceof Clause) {
				continue;
			}

			foreach ($props as $prop) {
				if (!isset($pair[$prop])) {
					$options['metadata_name_value_pairs'][$key][$prop] = elgg_extract($prop, $options);
				}
			}

			$options['metadata_name_value_pairs'][$key]['entity_guids'] = $options['guids'];
		}

		$options['metadata_name_value_pairs'] = $this->removeKeyPrefix('metadata_', $options['metadata_name_value_pairs']);

		$defaults = [
			'name' => null,
			'value' => null,
			'comparison' => '=',
			'type' => ELGG_VALUE_STRING,
			'case_sensitive' => true,
			'entity_guids' => null,
			'ids' => null,
			'created_after' => null,
			'created_before' => null,
		];

		foreach ($options['metadata_name_value_pairs'] as $key => $pair) {
			if ($pair instanceof WhereClause) {
				continue;
			}

			$pair = array_merge($defaults, $pair);

			if (in_array($pair['name'], \ElggEntity::PRIMARY_ATTR_NAMES)) {
				$clause = new AttributeWhereClause();
			} else {
				$clause = new MetadataWhereClause();
				$clause->ids = (array) $pair['ids'];
				$clause->entity_guids = (array) $pair['entity_guids'];
				$clause->created_after = $pair['created_after'];
				$clause->created_before = $pair['created_before'];
			}

			$clause->names = (array) $pair['name'];
			$clause->values = (array) $pair['value'];
			$clause->comparison = $pair['comparison'];
			$clause->value_type = $pair['type'];
			$clause->case_sensitive = $pair['case_sensitive'];

			$options['metadata_name_value_pairs'][$key] = $clause;
		}

		return $options;
	}

	/**
	 * Normalizes metadata search options
	 * These queries are added merge with 'OR' and appended to other metadata queries with 'AND'
	 *
	 * @param array $options Options
	 *
	 * @return array
	 */
	protected function normalizeMetadataSearchOptions(array $options = []): array {
		$options = $this->normalizePluralOptions($options, ['search_name_value_pair']);

		$options = $this->normalizePairedOptions('search', $options);

		foreach ($options['search_name_value_pairs'] as $key => $pair) {
			if ($pair instanceof Clause) {
				continue;
			}

			$options['search_name_value_pairs'][$key]['entity_guids'] = $options['guids'];
		}

		$options['search_name_value_pairs'] = $this->removeKeyPrefix('metadata_', $options['search_name_value_pairs']);

		$defaults = [
			'name' => null,
			'value' => null,
			'comparison' => '=',
			'type' => ELGG_VALUE_STRING,
			'case_sensitive' => true,
			'entity_guids' => null,
			'ids' => null,
			'created_after' => null,
			'created_before' => null,
		];

		foreach ($options['search_name_value_pairs'] as $key => $pair) {
			if ($pair instanceof WhereClause) {
				continue;
			}

			$pair = array_merge($defaults, $pair);

			if (in_array($pair['name'], \ElggEntity::PRIMARY_ATTR_NAMES)) {
				$clause = new AttributeWhereClause();
			} else {
				$clause = new MetadataWhereClause();
				$clause->ids = (array) $pair['ids'];
				$clause->entity_guids = (array) $pair['entity_guids'];
				$clause->created_after = $pair['created_after'];
				$clause->created_before = $pair['created_before'];
			}

			$clause->names = (array) $pair['name'];
			$clause->values = (array) $pair['value'];
			$clause->comparison = $pair['comparison'];
			$clause->value_type = $pair['type'];
			$clause->case_sensitive = $pair['case_sensitive'];

			$options['search_name_value_pairs'][$key] = $clause;
		}

		return $options;
	}

	/**
	 * Normalizes annotation options
	 *
	 * @param array $options Options
	 *
	 * @return array
	 */
	protected function normalizeAnnotationOptions(array $options = []): array {
		$options = $this->normalizePluralOptions($options, [
			'annotation_id',
			'annotation_name',
			'annotation_value',
			'annotation_name_value_pair',
		]);

		$options = $this->normalizePairedOptions('annotation', $options);

		$props = [
			'annotation_ids',
			'annotation_owner_guids',
			'annotation_created_after',
			'annotation_created_before',
			'annotation_sort_by_calculation',
		];

		foreach ($props as $prop) {
			if (isset($options[$prop]) && empty($options['annotation_name_value_pairs'])) {
				$options['annotation_name_value_pairs'][] = [
					$prop => $options[$prop]
				];
			}
		}

		foreach ($options['annotation_name_value_pairs'] as $key => $pair) {
			if ($pair instanceof WhereClause) {
				continue;
			}

			foreach ($props as $prop) {
				if (!isset($pair[$prop])) {
					$options['annotation_name_value_pairs'][$key][$prop] = elgg_extract($prop, $options);
				}
			}

			$options['annotation_name_value_pairs'][$key]['entity_guids'] = $options['guids'];
		}

		$options['annotation_name_value_pairs'] = $this->removeKeyPrefix('annotation_', $options['annotation_name_value_pairs']);

		$defaults = [
			'name' => null,
			'value' => null,
			'comparison' => '=',
			'type' => ELGG_VALUE_STRING,
			'case_sensitive' => true,
			'entity_guids' => null,
			'owner_guids' => null,
			'ids' => null,
			'enabled' => null,
			'access_ids' => null,
			'created_after' => null,
			'created_before' => null,
			'sort_by_calculation' => null,
		];

		foreach ($options['annotation_name_value_pairs'] as $key => $pair) {
			if ($pair instanceof WhereClause) {
				continue;
			}

			$pair = array_merge($defaults, $pair);

			$clause = new AnnotationWhereClause();
			$clause->ids = (array) $pair['ids'];
			$clause->entity_guids = (array) $pair['entity_guids'];
			$clause->owner_guids = (array) $pair['owner_guids'];
			$clause->created_after = $pair['created_after'];
			$clause->created_before = $pair['created_before'];
			$clause->names = (array) $pair['name'];
			$clause->values = (array) $pair['value'];
			$clause->comparison = $pair['comparison'];
			$clause->value_type = $pair['type'];
			$clause->case_sensitive = $pair['case_sensitive'];
			$clause->access_ids = (array) $pair['access_ids'];
			$clause->sort_by_calculation = $pair['sort_by_calculation'];

			if ($clause->sort_by_calculation && empty($options['order_by'])) {
				$clause->sort_by_direction = 'desc';
			}

			$options['annotation_name_value_pairs'][$key] = $clause;
		}

		return $options;
	}

	/**
	 * Normalizes paired options
	 *
	 * @param string $type    Pair type
	 * @param array  $options Options
	 *
	 * @return array
	 */
	protected function normalizePairedOptions($type = 'metadata', array $options = []): array {
		if (!is_array($options["{$type}_name_value_pairs"])) {
			$options["{$type}_name_value_pairs"] = [];
		}
		
		$case_sensitive_default = elgg_extract("{$type}_case_sensitive", $options, true);

		/**
		 * transforming root pair to array
		 *
		 * '_name_value_pairs' => [
		 * 		'name' => 'foo',
		 * 		'value' => 'bar'
		 * ]
		 */
		if (isset($options["{$type}_name_value_pairs"]['name'])) {
			$options["{$type}_name_value_pairs"][] = [
				'name' => $options["{$type}_name_value_pairs"]['name'],
				'value' => elgg_extract('value', $options["{$type}_name_value_pairs"]),
				'comparison' => elgg_extract('operand', $options["{$type}_name_value_pairs"], '='),
				'case_sensitive' => elgg_extract('case_sensitive', $options["{$type}_name_value_pairs"], $case_sensitive_default)
			];
			
			unset($options["{$type}_name_value_pairs"]['name']);
			unset($options["{$type}_name_value_pairs"]['value']);
			unset($options["{$type}_name_value_pairs"]['operand']);
			unset($options["{$type}_name_value_pairs"]['case_sensitive']);
		}

		/**
		 * transforming pair level short notation
		 *
		 * @note: short notation for name and value are not supported
		 *
		 * '_name_value_pairs' => [
		 * 		[
		 * 			'foo' => 'bar'
		 * 		]
		 * ]
		 */
		foreach ($options["{$type}_name_value_pairs"] as $index => $pair) {
			if (is_array($pair)) {
				$keys = array_keys($pair);
				if (count($keys) === 1 && is_string($keys[0]) && $keys[0] !== 'name' && $keys[0] !== 'value') {
					$options["{$type}_name_value_pairs"][$index] = [
						'name' => $keys[0],
						'value' => $pair[$keys[0]],
						'comparison' => '=',
					];
				}
			}
		}

		/**
		 * transforming root level short notation
		 *
		 * '_name_value_pairs' => [
		 * 		'foo' => 'bar'
		 * ]
		 */
		foreach ($options["{$type}_name_value_pairs"] as $index => $values) {
			if ($values instanceof Clause) {
				continue;
			}

			if (is_array($values)) {
				if (isset($values['name']) || isset($values['value'])) {
					continue;
				}
			}
			
			$options["{$type}_name_value_pairs"][$index] = [
				'name' => $index,
				'value' => $values,
				'comparison' => '=',
			];
		}

		if (isset($options["{$type}_names"]) || isset($options["{$type}_values"])) {
			$options["{$type}_name_value_pairs"][] = [
				'name' => isset($options["{$type}_names"]) ? (array) $options["{$type}_names"] : null,
				'value' => isset($options["{$type}_values"]) ? (array) $options["{$type}_values"] : null,
				'comparison' => '=',
			];
		}

		foreach ($options["{$type}_name_value_pairs"] as $key => $value) {
			if ($value instanceof Clause) {
				continue;
			}

			if (!isset($value['case_sensitive'])) {
				$value['case_sensitive'] = $case_sensitive_default;
			}
			
			if (!isset($value['type'])) {
				if (isset($value['value']) && is_bool($value['value'])) {
					$value['value'] = (int) $value['value'];
				}
				
				if (isset($value['value']) && is_int($value['value'])) {
					$value['type'] = ELGG_VALUE_INTEGER;
				} else {
					$value['type'] = ELGG_VALUE_STRING;
				}
			}
			
			if (!isset($value['comparison']) && isset($value['operand'])) {
				$value['comparison'] = $value['operand'];
				unset($value['operand']);
			}

			$options["{$type}_name_value_pairs"][$key] = $value;
		}

		unset($options["{$type}_names"]);
		unset($options["{$type}_values"]);
		unset($options["{$type}_case_sensitive"]);

		return $options;
	}

	/**
	 * Normalizes relationship options
	 *
	 * @param array $options Options
	 *
	 * @return array
	 */
	protected function normalizeRelationshipOptions(array $options = []): array {
		$defaults = [
			'relationship_ids' => null,
			'relationship' => null,
			'relationship_guid' => null,
			'inverse_relationship' => false,
			'relationship_join_on' => 'guid',
			'relationship_created_after' => null,
			'relationship_created_before' => null,
		];

		$simple_pair = [];
		foreach (array_keys($defaults) as $prop) {
			if (isset($options[$prop])) {
				$simple_pair[$prop] = $options[$prop];
			}
			
			unset($options[$prop]);
		}

		$options['relationship_pairs'] = (array) $options['relationship_pairs'];
		$options['relationship_pairs'][] = $simple_pair;

		foreach ($options['relationship_pairs'] as $index => $relationship_pair) {
			if ($relationship_pair instanceof WhereClause) {
				continue;
			}

			$options['relationship_pairs'][$index] = array_merge($defaults, $relationship_pair);
		}

		$options['relationship_pairs'] = $this->removeKeyPrefix('relationship_', $options['relationship_pairs']);

		foreach ($options['relationship_pairs'] as $key => $pair) {
			if ($pair instanceof WhereClause) {
				continue;
			}

			if (!$pair['relationship'] && !$pair['guid'] && !$pair['ids']) {
				unset($options['relationship_pairs'][$key]);
				continue;
			}

			$clause = new RelationshipWhereClause();
			$clause->ids = (array) $pair['ids'];
			$clause->names = (array) $pair['relationship'];

			$clause->join_on = $pair['join_on'];
			$clause->inverse = $pair['inverse_relationship'];
			if ($clause->inverse) {
				$clause->object_guids = (array) $pair['guid'];
			} else {
				$clause->subject_guids = (array) $pair['guid'];
			}
			
			$clause->created_after = $pair['created_after'];
			$clause->created_before = $pair['created_before'];


			$options['relationship_pairs'][$key] = $clause;
		}

		return $options;
	}

	/**
	 * Normalizes guid based options
	 *
	 * @param array $options Options
	 *
	 * @return array
	 */
	protected function normalizeGuidOptions(array $options = []): array {
		$options = $this->normalizePluralOptions($options, [
			'guid',
			'owner_guid',
			'container_guid',
			'annotation_owner_guid',
		]);

		$names = [
			'guids',
			'owner_guids',
			'container_guids',
			'annotation_owner_guids',
			'relationship_guid',
		];

		foreach ($names as $name) {
			if (!isset($options[$name])) {
				continue;
			}

			if (!is_array($options[$name])) {
				$options[$name] = [$options[$name]];
			}

			foreach ($options[$name] as $key => $value) {
				if ($value === false || $value === '') {
					unset($options[$name][$key]);
				}
			}
		}

		return $options;
	}

	/**
	 * Normalizes time based options
	 *
	 * @param array $options Options array
	 *
	 * @return array
	 */
	protected function normalizeTimeOptions(array $options = []): array {
		$props = [
			'modified',
			'created',
			'updated',
			'metadata_created',
			'annotation_created',
			'relationship_created',
			'last_action',
			'posted',
		];

		$bounds = ['time_lower', 'time_upper', 'after', 'before'];

		foreach ($props as $prop) {
			foreach ($bounds as $bound) {
				$prop_name = "{$prop}_{$bound}";

				$new_prop_name = $prop_name;
				$new_prop_name = str_replace('modified', 'updated', $new_prop_name);
				$new_prop_name = str_replace('posted', 'created', $new_prop_name);
				$new_prop_name = str_replace('time_lower', 'after', $new_prop_name);
				$new_prop_name = str_replace('time_upper', 'before', $new_prop_name);

				if (!isset($options[$new_prop_name])) {
					$options[$new_prop_name] = elgg_extract($prop_name, $options);
				}
			}
		}

		return $options;
	}

	/**
	 * Remove $prefix from array keys
	 *
	 * @param string $prefix Prefix
	 * @param array  $array  Array
	 *
	 * @return array
	 */
	protected function removeKeyPrefix(string $prefix, array $array = []): array {
		foreach ($array as $key => $value) {
			$new_key = $key;
			if (str_starts_with($key, $prefix)) {
				$new_key = substr($key, strlen($prefix));
			}
			
			if (!isset($array[$new_key])) {
				$array[$new_key] = $value;
			}
			
			if ($new_key !== $key) {
				unset($array[$key]);
			}

			if (is_array($array[$new_key])) {
				$array[$new_key] = $this->removeKeyPrefix($prefix, $array[$new_key]);
			}
		}

		return $array;
	}

	/**
	 * Processes an array of 'select' clauses
	 *
	 * @param array $options Options
	 *
	 * @return array
	 */
	protected function normalizeSelectClauses(array $options = []): array {
		$options = $this->normalizePluralOptions($options, ['select']);
		
		if (empty($options['selects'])) {
			$options['selects'] = [];
			
			return $options;
		}
		
		if (!is_array($options['selects'])) {
			$options['selects'] = [$options['selects']];
		}

		foreach ($options['selects'] as $key => $clause) {
			if (empty($clause)) {
				unset($options['selects'][$key]);
				continue;
			}

			if ($clause instanceof SelectClause) {
				continue;
			}

			$options['selects'][$key] = new SelectClause($clause);
		}

		return $options;
	}

	/**
	 * Processes an array of 'where' clauses
	 *
	 * @param array $options Options
	 *
	 * @return array
	 */
	protected function normalizeWhereClauses(array $options = []): array {
		$options = $this->normalizePluralOptions($options, ['where']);

		if (empty($options['wheres'])) {
			$options['wheres'] = [];
			
			return $options;
		}
		
		if (!is_array($options['wheres'])) {
			$options['wheres'] = [$options['wheres']];
		}
		
		foreach ($options['wheres'] as $key => $clause) {
			if (empty($clause)) {
				unset($options['wheres'][$key]);
				continue;
			}

			if ($clause instanceof WhereClause) {
				continue;
			}

			$options['wheres'][$key] = new WhereClause($clause);
		}

		return $options;
	}

	/**
	 * Processes an array of 'joins' clauses
	 *
	 * @param array $options Options
	 *
	 * @return array
	 */
	protected function normalizeJoinClauses(array $options = []): array {
		$options = $this->normalizePluralOptions($options, ['join']);
		
		if (empty($options['joins'])) {
			$options['joins'] = [];
			
			return $options;
		}
		
		if (!is_array($options['joins'])) {
			$options['joins'] = [$options['joins']];
		}

		foreach ($options['joins'] as $key => $join) {
			if (empty($join)) {
				unset($options['joins'][$key]);
				continue;
			}

			if ($join instanceof JoinClause) {
				continue;
			}

			if (is_string($join)) {
				preg_match('/((LEFT|INNER|RIGHT)\s+)?JOIN\s+(.*?)\s+((as\s+)?(.*?)\s+)ON\s+(.*)$/im', $join, $parts);

				$type = !empty($parts[2]) ? strtolower($parts[2]) : 'inner';
				$table = $parts[3];
				$alias = $parts[6];
				$condition = preg_replace('/\r|\n/', '', $parts[7]);

				$dbprefix = elgg_get_config('dbprefix');
				if (!elgg_is_empty($dbprefix) && str_starts_with($table, $dbprefix)) {
					$table = substr($table, strlen($dbprefix));
				}

				$clause = new JoinClause($table, $alias, $condition, $type);
				$options['joins'][$key] = $clause;
			}
		}

		return $options;
	}

	/**
	 * Processes an array of 'joins' clauses
	 *
	 * @param array $options Options
	 *
	 * @return array
	 */
	protected function normalizeOrderByClauses(array $options = []): array {
		$orders = $options['order_by'];
		$options['order_by'] = [];

		if (!empty($orders)) {
			if (is_string($orders)) {
				$orders = explode(',', $orders);
			} elseif (!is_array($orders)) {
				$orders = [$orders];
			}

			foreach ($orders as $order) {
				if ($order instanceof OrderByClause) {
					$options['order_by'][] = $order;
					continue;
				}

				$order = trim($order);
				$parts = [];
				if (preg_match('/(.*)(?=\s+(asc|desc))/i', $order, $parts)) {
					$column = $parts[1];
					$direction = $parts[2];
				} else {
					$column = $order;
					$direction = 'ASC';
				}

				$options['order_by'][] = new OrderByClause($column, $direction);
			}
		}
		
		$sort_by = $options['sort_by'];
		if (!is_array($sort_by)) {
			return $options;
		}
		
		if (isset($sort_by['property'])) {
			// single array variant, convert to an array of sort_by specs
			$options['sort_by'] = [$sort_by];
		}
		
		foreach ($options['sort_by'] as $sort_spec) {
			$clause = new EntitySortByClause();
			$clause->property = elgg_extract('property', $sort_spec);
			$clause->property_type = elgg_extract('property_type', $sort_spec);
			$clause->join_type = elgg_extract('join_type', $sort_spec, 'inner');
			$clause->direction = elgg_extract('direction', $sort_spec);
			$clause->signed = elgg_extract('signed', $sort_spec);
			$clause->inverse_relationship = elgg_extract('inverse_relationship', $sort_spec);
			$clause->relationship_guid = elgg_extract('relationship_guid', $sort_spec);

			$options['order_by'][] = $clause;
		}

		return $options;
	}

	/**
	 * Normalize 'having' statements
	 *
	 * @param array $options Options
	 *
	 * @return array
	 */
	protected function normalizeHavingClauses(array $options = []): array {
		if (empty($options['having'])) {
			$options['having'] = [];
			
			return $options;
		}
		
		if (!is_array($options['having'])) {
			$options['having'] = [$options['having']];
		}

		foreach ($options['having'] as $key => $expr) {
			if ($expr instanceof HavingClause) {
				continue;
			}

			$options['having'][$key] = new HavingClause($expr);
		}

		return $options;
	}
	
	/**
	 * Normalize 'group_by' statements
	 *
	 * @param array $options Options
	 *
	 * @return array
	 */
	protected function normalizeGroupByClauses(array $options = []): array {
		if (empty($options['group_by'])) {
			$options['group_by'] = [];
			
			return $options;
		}

		if (!is_array($options['group_by'])) {
			$options['group_by'] = [$options['group_by']];
		}

		foreach ($options['group_by'] as $key => $expr) {
			if ($expr instanceof GroupByClause) {
				continue;
			}
			
			$options['group_by'][$key] = new GroupByClause($expr);
		}

		return $options;
	}

	/**
	 * Normalizes metadata / annotation option names to their corresponding metastrings name.
	 *
	 * @param array $options An options array
	 *
	 * @return array
	 * @internal
	 */
	public static function normalizeMetastringOptions(array $options = []): array {

		// support either metastrings_type or metastring_type
		// because I've made this mistake many times and hunting it down is a pain...
		$type = elgg_extract('metastring_type', $options, null);
		$type = elgg_extract('metastrings_type', $options, $type);

		$options['metastring_type'] = $type;

		// support annotation_ and annotations_ because they're way too easy to confuse
		$prefixes = ['metadata_', 'annotation_', 'annotations_'];

		// map the metadata_* options to metastring_* options
		$map = [
			'names'                 => 'metastring_names',
			'values'                => 'metastring_values',
			'case_sensitive'        => 'metastring_case_sensitive',
			'owner_guids'           => 'metastring_owner_guids',
			'created_time_lower'    => 'metastring_created_time_lower',
			'created_time_upper'    => 'metastring_created_time_upper',
			'calculation'           => 'metastring_calculation',
			'ids'                   => 'metastring_ids',
		];

		foreach ($prefixes as $prefix) {
			$singulars = ["{$prefix}name", "{$prefix}value", "{$prefix}owner_guid", "{$prefix}id"];
			$options = self::normalizePluralOptions($options, $singulars);

			foreach ($map as $specific => $normalized) {
				$key = $prefix . $specific;
				if (isset($options[$key])) {
					$options[$normalized] = $options[$key];
				}
			}
		}

		return $options;
	}

	/**
	 * Normalise the singular keys in an options array to plural keys.
	 *
	 * Used in elgg_get_entities*() functions to support shortcutting plural
	 * names by singular names.
	 *
	 * @param array $options   The options array. $options['keys'] = 'values';
	 * @param array $singulars A list of singular words to pluralize by adding 's'.
	 *
	 * @return array
	 * @internal
	 */
	public static function normalizePluralOptions(array $options, array $singulars): array {
		foreach ($singulars as $singular) {
			$plural = $singular . 's';

			if (array_key_exists($singular, $options)) {
				if ($options[$singular] === ELGG_ENTITIES_ANY_VALUE) {
					$options[$plural] = $options[$singular];
				} else {
					// Test for array refs #2641
					if (!is_array($options[$singular])) {
						$options[$plural] = [$options[$singular]];
					} else {
						$options[$plural] = $options[$singular];
					}
				}
			}

			unset($options[$singular]);
		}

		return $options;
	}
}
