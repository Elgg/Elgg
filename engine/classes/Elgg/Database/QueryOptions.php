<?php

namespace Elgg\Database;

use ArrayObject;
use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Clauses\EntitySortByClause;
use Elgg\Database\Clauses\GroupByClause;
use Elgg\Database\Clauses\HavingClause;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\MetadataWhereClause;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\Clauses\PrivateSettingWhereClause;
use Elgg\Database\Clauses\RelationshipWhereClause;
use Elgg\Database\Clauses\SelectClause;
use Elgg\Database\Clauses\WhereClause;

/**
 * This class aggregates and standardizes various parameters that have been added to
 * entity-related queries over the years. Until we switch to OO queries, this class will
 * serve as an adapter between legacy options and new repository instances
 *
 * @property array                       $type_subtype_pairs
 * @property int[]                       $guids
 * @property int[]                       $owner_guids
 * @property int[]                       $container_guids
 * @property int[]                       $access_ids
 * @property \DateTime|string|int        $created_after
 * @property \DateTime|string|int        $created_before
 * @property \DateTime|string|int        $updated_after
 * @property \DateTime|string|int        $updated_before
 * @property \DateTime|string|int        $last_action_after
 * @property \DateTime|string|int        $last_action_before
 *
 * @property boolean                     $distinct
 * @property SelectClause[]              $selects
 * @property WhereClause[]               $wheres
 * @property JoinClause[]                $joins
 * @property OrderByClause[]             $order_by
 * @property GroupByClause[]             $group_by
 * @property HavingClause[]              $having
 *
 * @property EntitySortByClause[]        $sort_by
 *
 * @property boolean                     $count
 * @property int                         $limit
 * @property int                         $offset
 *
 * @property MetadataWhereClause[]       $metadata_name_value_pairs
 * @property string                      $metadata_name_value_pairs_operator
 * @property string                      $metadata_calculation
 *
 * @property MetadataWhereClause[]       $search_name_value_pairs
 *
 * @property AnnotationWhereClause[]     $annotation_name_value_pairs
 * @property string                      $annotation_name_value_pairs_operator
 * @property string                      $annotation_calculation
 *
 * @property RelationshipWhereClause[]   $relationship_pairs
 *
 * @property PrivateSettingWhereClause[] $private_setting_name_value_pairs
 * @property string                      $private_setting_name_value_pairs_operator
 *
 * @property boolean                     $preload_owners
 * @property boolean                     $preload_containers
 * @property callable                    $callback
 *
 * @property boolean                     $batch
 * @property boolean                     $batch_inc_offset
 * @property int                         $batch_size
 */
class QueryOptions extends ArrayObject implements QueryFiltering {

	use LegacyQueryOptionsAdapter;

	/**
	 * {@inheritdoc}
	 */
	public function __construct($input = [], $flags = 0, $iterator_class = "ArrayIterator") {
		$input = $this->normalizeOptions($input);
		parent::__construct($input, $flags, $iterator_class);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __get($name) {
		$val = &$this[$name];

		return $val;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __set($name, $value) {
		$this[$name] = $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __unset($name) {
		unset($this[$name]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __isset($name) {
		return isset($this[$name]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function distinct($distinct = true) {
		$this->distinct = $distinct;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function where(WhereClause $clause) {
		$this->wheres[] = $clause;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function select(SelectClause $clause) {
		$this->selects[] = $clause;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function join(JoinClause $clause) {
		$this->joins[] = $clause;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function groupBy(GroupByClause $clause) {
		$this->group_by[] = $clause;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function having(HavingClause $clause) {
		$this->having[] = $clause;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function orderBy(OrderByClause $clause) {
		$this->order_by[] = $clause;

		return $this;
	}

}
