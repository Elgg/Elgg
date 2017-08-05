<?php
/**
 *
 */

namespace Elgg\Database;

use Doctrine\Common\Collections\Expr\Expression;
use Doctrine\DBAL\Query\QueryBuilder;
use Elgg\Database;
use Elgg\Logger;
use MongoDB\Driver\Query;

/**
 * List query builder
 *
 */
class ListQueryBuilder {

	static $iterator;

	/**
	 * @var Database
	 */
	private $db;

	/**
	 * @var array
	 */
	private $options;

	/**
	 * @var QueryBuilder
	 */
	private $qb;

	/**
	 * @var Logger
	 */
	private $logger;

	/**
	 * ListQueryBuilder constructor.
	 *
	 * @param Database $db Database
	 */
	public function __construct(Database $db, Logger $logger, array $options = []) {
		$this->db = $db;
		$this->qb = $db->qb();
		$this->setOptions($options);
		$this->logger = $logger;
	}

	public function setOptions(array $options) {
		$this->options = $this->normalizeOptions($options);
	}

	public function getOptions() {
		return $this->options;
	}

	/**
	 * Build entity list query
	 *
	 * @param array $options ege* options
	 *
	 * @return QueryBuilder
	 */
	public function buildQuery() {

		$this->qb->from($this->db->prefix('entities'), 'e');

		if (!$this->options['count']) {
			$distinct = $this->options['distinct'] ? "DISTINCT" : "";
			$this->qb->select("$distinct e.*");
		} else {
			// note: when DISTINCT unneeded, it's slightly faster to compute COUNT(*) than GUIDs
			$count_expr = $this->options['distinct'] ? "DISTINCT e.guid" : "*";
			$this->qb->select("COUNT({$count_expr} AS total");
		}

		$this->joinSecondaryTable($this->qb)
			->addTypeSubtypeClauses(
				'e',
				$this->options['types'],
				$this->options['subtypes'],
				$this->options['type_subtype_pairs']
			)
			->addGuidColumnClauses(
				'e.guid',
				$this->options['guids']
			)
			->addGuidColumnClauses(
				'e.owner_guid',
				$this->options['owner_guids']
			)
			->addGuidColumnClauses(
				'e.container_guid',
				$this->options['container_guids']
			)
			->addTimeConstraintClauses('e',
				$this->options['created_time_upper'],
				$this->options['created_time_lower'],
				$this->options['modified_time_upper'],
				$this->options['modified_time_lower']
			)
			->addRelationshipClauses(
				"e.{$this->options['relationship_join_on']}",
				$this->options['relationship'],
				$this->options['relationship_guid'],
				$this->options['inverse_relationship']
			)
			->addTimeConstraintClauses(
				'r',
				$this->options['relationship_created_time_upper'],
				$this->options['relationship_created_time_lower']
			);


		$wheres = $this->options['wheres'];
		foreach ($wheres as $where) {
			if (is_callable($where)) {
				$where = call_user_func($where, $this->qb);
			}
			if (empty($where)) {
				continue;
			}
			$this->qb->andWhere($where);
		}

		$joins = $this->options['joins'];
		foreach ($joins as $join) {
			if (is_callable($join)) {
				$join = call_user_func($join, $this->qb);
			}

			if (empty($join)) {
				continue;
			}

			if (is_array($join)) {
				$from_alias = elgg_extract('from_alias', $join, 'e');
				$table = elgg_extract('table', $join);
				$alias = elgg_extract('alias', $join);
				$condition = elgg_extract('condition', $join);
			} else {
				$from_alias = 'e';
				preg_match('/JOIN\s(.*)\s(.*)\sON\s(.*)/i', $join, $parts);
				list(, $table, $alias, $condition) = $parts;
			}
			$this->qb->join($from_alias, $table, $alias, $condition);
		}

		if (!$this->options['count'] && $this->options['selects']) {
			foreach ($this->options['selects'] as $select) {
				if (!empty($select)) {
					$this->qb->addSelect($select);
				}
			}
		}

		// Add access controls
		$this->qb->andWhere(_elgg_get_access_where_sql());

		if ($this->options['count']) {
			return $this->qb;
		}

		if ($this->options['order_by']) {
			$orders = explode(',', $this->options['order_by']);
			foreach ($orders as $order_by) {
				$order_by = trim($order_by);
				list($column, $direction) = explode(' ', $order_by, 2);
				$direction = in_array(strtoupper($direction), [
					'ASC',
					'DESC'
				]) ? strtoupper($direction) : 'ASC';
				if ($this->options['reverse_order_by']) {
					$direction = $direction == 'ASC' ? 'DESC' : 'ASC';
				}
				$this->qb->addOrderBy($column, $direction);
			}
		}

		if ($this->options['group_by']) {
			$groups = explode(',', $this->options['group_by']);
			foreach ($groups as $group) {
				$this->qb->addGroupBy($group);
			}
		}

		if ($this->options['limit']) {
			$this->qb->setMaxResults((int) $this->options['limit']);
			$this->qb->setFirstResult((int) $this->options['offset']);
		}

		return $this->qb;
	}

	protected function normalizeOptions(array $options = []) {

		$defaults = [
			'types' => ELGG_ENTITIES_ANY_VALUE,
			'subtypes' => ELGG_ENTITIES_ANY_VALUE,
			'type_subtype_pairs' => ELGG_ENTITIES_ANY_VALUE,

			'guids' => ELGG_ENTITIES_ANY_VALUE,
			'owner_guids' => ELGG_ENTITIES_ANY_VALUE,
			'container_guids' => ELGG_ENTITIES_ANY_VALUE,

			'modified_time_lower' => ELGG_ENTITIES_ANY_VALUE,
			'modified_time_upper' => ELGG_ENTITIES_ANY_VALUE,
			'created_time_lower' => ELGG_ENTITIES_ANY_VALUE,
			'created_time_upper' => ELGG_ENTITIES_ANY_VALUE,

			'reverse_order_by' => false,
			'order_by' => 'e.time_created desc',
			'group_by' => ELGG_ENTITIES_ANY_VALUE,
			'limit' => elgg_get_config('default_limit'),
			'offset' => 0,
			'count' => false,
			'selects' => [],
			'wheres' => [],
			'joins' => [],

			'metadata_names' => ELGG_ENTITIES_ANY_VALUE,
			'metadata_values' => ELGG_ENTITIES_ANY_VALUE,
			'metadata_name_value_pairs' => ELGG_ENTITIES_ANY_VALUE,

			'metadata_name_value_pairs_operator' => 'AND',
			'metadata_case_sensitive' => true,
			'order_by_metadata' => [],

			'metadata_owner_guids' => ELGG_ENTITIES_ANY_VALUE,

			'annotation_names' => ELGG_ENTITIES_ANY_VALUE,
			'annotation_values' => ELGG_ENTITIES_ANY_VALUE,
			'annotation_name_value_pairs' => ELGG_ENTITIES_ANY_VALUE,

			'annotation_name_value_pairs_operator' => 'AND',
			'annotation_case_sensitive' => true,
			'order_by_annotation' => [],

			'annotation_created_time_lower' => ELGG_ENTITIES_ANY_VALUE,
			'annotation_created_time_upper' => ELGG_ENTITIES_ANY_VALUE,
			'annotation_owner_guids' => ELGG_ENTITIES_ANY_VALUE,

			'relationship' => null,
			'relationship_guid' => null,
			'inverse_relationship' => false,
			'relationship_join_on' => 'guid',

			'relationship_created_time_lower' => ELGG_ENTITIES_ANY_VALUE,
			'relationship_created_time_upper' => ELGG_ENTITIES_ANY_VALUE,

			'preload_owners' => false,
			'preload_containers' => false,
			'callback' => 'entity_row_to_elggstar',
			'distinct' => true,

			'batch' => false,
			'batch_inc_offset' => true,
			'batch_size' => 25,

			// private API
			'__ElggBatch' => null,
		];

		$options = array_merge($defaults, $options);

		// can't use helper function with type_subtype_pair because
		// it's already an array...just need to merge it
		if (isset($options['type_subtype_pair'])) {
			if (isset($options['type_subtype_pairs'])) {
				$options['type_subtype_pairs'] = array_merge($options['type_subtype_pairs'],
					$options['type_subtype_pair']);
			} else {
				$options['type_subtype_pairs'] = $options['type_subtype_pair'];
			}
		}

		$singulars = [
			'type',
			'subtype',
			'guid',
			'owner_guid',
			'container_guid',
			'metadata_name',
			'metadata_value',
			'metadata_name_value_pair',
			'metadata_owner_guid',
			'annotation_name',
			'annotation_value',
			'annotation_name_value_pair',
			'annotation_owner_guid',
		];

		$options = _elgg_normalize_plural_options_array($options, $singulars);

		if (!is_array($options['wheres'])) {
			$options['wheres'] = [$options['wheres']];
		}

		if (!is_array($options['joins'])) {
			$options['joins'] = [$options['joins']];
		}

		return $options;
	}

	/**
	 * Decorate getEntities() options in order to auto-join secondary tables where it's
	 * safe to do so.
	 *
	 * @return static
	 */
	protected function joinSecondaryTable() {

		$options = $this->options;

		// we must be careful that the query doesn't specify any options that may join
		// tables or change the selected columns
		if (!is_array($options['types'])
			|| count($options['types']) !== 1
			|| !empty($options['selects'])
			|| !empty($options['wheres'])
			|| !empty($options['joins'])
			|| $options['callback'] !== 'entity_row_to_elggstar'
			|| $options['count']
		) {
			// Too dangerous to auto-join
			return $this;
		}

		$join_types = [
			// Each class must have a static getExternalAttributes() : array
			'object' => 'ElggObject',
			'user' => 'ElggUser',
			'group' => 'ElggGroup',
			'site' => 'ElggSite',
		];

		// We use reset() because $options['types'] may not have a numeric key
		$type = reset($options['types']);

		// Get the columns we'll need to select. We can't use st.* because the order_by
		// clause may reference "guid", which MySQL will complain about being ambiguous
		try {
			$attributes = \ElggEntity::getExtraAttributeDefaults($type);
		} catch (\Exception $e) {
			$this->logger->error("Unrecognized type: $type");

			return $this;
		}

		// join the secondary table
		$this->qb->join('e', $this->db->prefix("{$type}s_entity"), 'st', $this->qb->expr()->eq('st.guid', 'e.guid'));

		foreach (array_keys($attributes) as $col) {
			$this->qb->addSelect("st.{$col}");
		}

		return $this;
	}

	/**
	 * Adds SQL where clause for owner and containers.
	 *
	 * @param string     $column Column name the guids should be checked against. Usually
	 *                           best to provide in table.column format.
	 * @param null|array $guids  Array of GUIDs.
	 *
	 * @return static
	 */
	protected function addGuidColumnClauses($column, $guids) {

		$guids = array_map(function ($guid) {
			return (int) $guid;
		}, (array) $guids);

		$guids = array_unique($guids);

		if (!empty($guids)) {
			$this->qb->andWhere($this->qb->expr()->in($column, $guids));
		}

		return $this;
	}

	/**
	 * Adds SQL where clause for type and subtype on main entity table
	 *
	 * @param string     $table    Entity table prefix as defined in SELECT...FROM entities $table
	 * @param null|array $types    Array of types or null if none.
	 * @param null|array $subtypes Array of subtypes or null if none
	 * @param null|array $pairs    Array of pairs of types and subtypes
	 *
	 * @return static
	 */
	protected function addTypeSubtypeClauses($table, $types, $subtypes, $pairs) {
		// subtype depends upon type.
		if ($subtypes && !$types) {
			$this->logger->warn("Cannot set subtypes without type.");

			return $this;
		}

		// short circuit if nothing is requested
		if (!$types && !$subtypes && !$pairs) {
			return $this;
		}

		// these are the only valid types for entities in elgg
		$valid_types = elgg_get_config('entity_types');

		// pairs override
		$wheres = [];
		if (!is_array($pairs)) {
			if (!is_array($types)) {
				$types = [$types];
			}

			if ($subtypes && !is_array($subtypes)) {
				$subtypes = [$subtypes];
			}

			// decrementer for valid types.  Return false if no valid types
			$valid_types_count = count($types);
			$valid_subtypes_count = 0;
			// remove invalid types to get an accurate count of
			// valid types for the invalid subtype detection to use
			// below.
			// also grab the count of ALL subtypes on valid types to decrement later on
			// and check against.
			//
			// yes this is duplicating a foreach on $types.
			foreach ($types as $type) {
				if (!in_array($type, $valid_types)) {
					$valid_types_count--;
					unset($types[array_search($type, $types)]);
				} else {
					// do the checking (and decrementing) in the subtype section.
					$valid_subtypes_count += count($subtypes);
				}
			}

			// return false if nothing is valid.
			if (!$valid_types_count) {
				return $this;
			}

			// subtypes are based upon types, so we need to look at each
			// type individually to get the right subtype id.
			foreach ($types as $type) {
				$subtype_ids = [];
				if ($subtypes) {
					foreach ($subtypes as $subtype) {
						// check that the subtype is valid
						if (!$subtype && ELGG_ENTITIES_NO_VALUE === $subtype) {
							// subtype value is 0
							$subtype_ids[] = ELGG_ENTITIES_NO_VALUE;
						} else if (!$subtype) {
							// subtype is ignored.
							// this handles ELGG_ENTITIES_ANY_VALUE, '', and anything falsy that isn't 0
							continue;
						} else {
							$subtype_id = get_subtype_id($type, $subtype);

							if ($subtype_id) {
								$subtype_ids[] = $subtype_id;
							} else {
								$valid_subtypes_count--;
								$this->logger->notice("Type-subtype '$type:$subtype' does not exist!");
								continue;
							}
						}
					}

					// return false if we're all invalid subtypes in the only valid type
					if ($valid_subtypes_count <= 0) {
						return $this;
					}
				}

				if (is_array($subtype_ids) && count($subtype_ids)) {
					$wheres[] = $this->qb->expr()->andX(
						$this->qb->expr()->eq("{$table}.type", $this->db->queryParam($this->qb, $type, 'string')),
						$this->qb->expr()->in("{$table}.subtype", $subtype_ids)
					);
				} else {

					$wheres[] = $this->qb->expr()->eq("{$table}.type", $this->db->queryParam($this->qb, $type, 'string'));
				}
			}
		} else {
			// using type/subtype pairs
			$valid_pairs_count = count($pairs);
			$valid_pairs_subtypes_count = 0;

			// same deal as above--we need to know how many valid types
			// and subtypes we have before hitting the subtype section.
			// also normalize the subtypes into arrays here.
			foreach ($pairs as $paired_type => $paired_subtypes) {
				if (!in_array($paired_type, $valid_types)) {
					$valid_pairs_count--;
					unset($pairs[array_search($paired_type, $pairs)]);
				} else {
					if ($paired_subtypes && !is_array($paired_subtypes)) {
						$pairs[$paired_type] = [$paired_subtypes];
					}
					$valid_pairs_subtypes_count += count($paired_subtypes);
				}
			}

			if ($valid_pairs_count <= 0) {
				return $this;
			}
			foreach ($pairs as $paired_type => $paired_subtypes) {
				// this will always be an array because of line 2027, right?
				// no...some overly clever person can say pair => array('object' => null)
				if (is_array($paired_subtypes)) {
					$paired_subtype_ids = [];
					foreach ($paired_subtypes as $paired_subtype) {
						if (ELGG_ENTITIES_NO_VALUE === $paired_subtype || ($paired_subtype_id = get_subtype_id($paired_type, $paired_subtype))) {
							$paired_subtype_ids[] = (ELGG_ENTITIES_NO_VALUE === $paired_subtype) ?
								ELGG_ENTITIES_NO_VALUE : $paired_subtype_id;
						} else {
							$valid_pairs_subtypes_count--;
							$this->logger->notice("Type-subtype '$paired_type:$paired_subtype' does not exist!");
							// return false if we're all invalid subtypes in the only valid type
							continue;
						}
					}

					// return false if there are no valid subtypes.
					if ($valid_pairs_subtypes_count <= 0) {
						return $this;
					}


					if ($paired_subtype_ids) {
						$wheres[] = $this->qb->expr()->andX(
							$this->qb->expr()->eq("{$table}.type", $this->db->queryParam($this->qb, $paired_type, 'string')),
							$this->qb->expr()->in("{$table}.subtype", $paired_subtype_ids)
						);
					}
				} else {
					$wheres[] = $this->qb->expr()->eq("{$table}.type", $this->db->queryParam($this->qb, $paired_type, 'string'));
				}
			}
		}

		// pairs override the above.  return false if they don't exist.
		if (is_array($wheres) && count($wheres)) {
			$this->qb->andWhere($this->qb->expr()->orX()->addMultiple($wheres));
		}

		return $this;
	}

	/**
	 * Adds SQL where clause for entity time limits.
	 *
	 * @param string   $table              Entity table prefix as defined in
	 *                                     SELECT...FROM entities $table
	 * @param null|int $time_created_upper Time created upper limit
	 * @param null|int $time_created_lower Time created lower limit
	 * @param null|int $time_updated_upper Time updated upper limit
	 * @param null|int $time_updated_lower Time updated lower limit
	 *
	 * @return static
	 */
	protected function addTimeConstraintClauses($table, $time_created_upper = null,
												$time_created_lower = null, $time_updated_upper = null, $time_updated_lower = null) {

		$wheres = [];

		if ($time_created_upper) {
			$wheres[] = $this->qb->expr()->lte("{$table}.time_created", $this->db->queryParam($this->qb, $time_created_upper, 'integer'));
		}

		if ($time_created_lower) {
			$wheres[] = $this->qb->expr()->gte("{$table}.time_created", $this->db->queryParam($this->qb, $time_created_lower, 'integer'));
		}

		if ($time_updated_upper) {
			$wheres[] = $this->qb->expr()->lte("{$table}.time_updated", $this->db->queryParam($this->qb, $time_updated_upper, 'integer'));
		}

		if ($time_updated_lower) {
			$wheres[] = $this->qb->expr()->gte("{$table}.time_updated", $this->db->queryParam($this->qb, $time_updated_lower, 'integer'));
		}

		if (is_array($wheres) && count($wheres) > 0) {
			$this->qb->andWhere($this->qb->expr()->andX()->addMultiple($wheres));
		}

		return $this;
	}

	/**
	 * Adds SQL appropriate for relationship joins and wheres
	 *
	 * @todo   add support for multiple relationships and guids.
	 *
	 * @param string $column               Column name the GUID should be checked against.
	 *                                     Provide in table.column format.
	 * @param string $relationship         Type of the relationship
	 * @param int    $relationship_guid    Entity GUID to check
	 * @param bool   $inverse_relationship Is $relationship_guid the target of the relationship?
	 *
	 * @return static
	 */
	protected function addRelationshipClauses($column, $relationship = null,
											  $relationship_guid = null, $inverse_relationship = false) {

		if ($relationship == null && $relationship_guid == null) {
			return $this;
		}

		if ($inverse_relationship) {
			$this->qb->join('e', $this->db->prefix('entity_relationships'), 'r', $this->qb->expr()->eq('r.guid_one', $column));
		} else {
			$this->qb->join('e', $this->db->prefix('entity_relationships'), 'r', $this->qb->expr()->eq('r.guid_two', $column));
		}

		$and_clauses = [];

		if ($relationship) {
			$and_clauses[] = $this->qb->expr()->eq('r.relationship', $this->db->queryParam($this->qb, $relationship, 'string'));
		}

		if ($relationship_guid) {
			if ($inverse_relationship) {
				$and_clauses[] = $this->qb->expr()->eq('r.guid_two', $this->db->queryParam($this->qb, $relationship_guid, 'integer'));
			} else {
				$and_clauses[] = $this->qb->expr()->eq('r.guid_one', $this->db->queryParam($this->qb, $relationship_guid, 'integer'));
			}
		} else {
			// See #5775. Queries that do not include a relationship_guid must be grouped by entity table alias,
			// otherwise the result set is not unique
			$this->qb->groupBy($column);
		}

		if ($and_clauses) {
			$this->qb->addSelect('r.id');
			$this->qb->andWhere($this->qb->expr()->andX()->addMultiple($and_clauses));
		}

		return $this;
	}

}