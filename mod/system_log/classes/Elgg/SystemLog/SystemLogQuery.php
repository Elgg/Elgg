<?php

namespace Elgg\SystemLog;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Repository;
use Elgg\Database\Select;
use Elgg\Values;

/**
 * System Log database table query
 */
class SystemLogQuery extends Repository {

	/**
	 * @var int[]
	 */
	public $id;

	/**
	 * @var int[]
	 */
	public $object_id;

	/**
	 * @var string[]
	 */
	public $object_class;

	/**
	 * @var string[]
	 */
	public $object_type;

	/**
	 * @var string[]
	 */
	public $object_subtype;

	/**
	 * @var string[]
	 */
	public $event;

	/**
	 * @var int[]
	 */
	public $performed_by_guid;

	/**
	 * @var int[]
	 */
	public $owner_guid;
	/**
	 * @var int[]
	 */
	public $access_id;
	/**
	 * @var string
	 */
	public $enabled;
	/**
	 * @var int|string|\DateTime
	 */
	public $created_after;

	/**
	 * @var int|string|\DateTime
	 */
	public $created_before;

	/**
	 * @var string[]
	 */
	public $ip_address;

	/**
	 * @var int
	 */
	public $limit;

	/**
	 * @var int
	 */
	public $offset;

	/**
	 * @var callable
	 */
	public $callback;

	/**
	 * Count rows
	 * @return int
	 */
	public function count() {
		$this->normalizeOptions();

		$qb = Select::fromTable('system_log');
		$qb->select('COUNT(*) as total');
		$wheres = $this->buildQuery($qb);
		if ($wheres) {
			$qb->where($wheres);
		}

		$result = _elgg_services()->db->getDataRow($qb);
		if (!$result) {
			return 0;
		}

		return (int) $result->total;
	}

	/**
	 * Apply numeric calculation to a column
	 *
	 * @param string $function      Calculation, e.g. max, min, avg
	 * @param string $property      Property name
	 * @param string $property_type Property type
	 *
	 * @return int|float
	 */
	public function calculate($function, $property, $property_type = null) {
		throw new \LogicException(__METHOD__ . ' not implemented');
	}

	/**
	 * Fetch rows
	 *
	 * @param int            $limit    Number of rows to fetch
	 * @param int            $offset   Index of the first row
	 * @param callable|false $callback Callback function to run database rows through
	 *
	 * @return \ElggData[]|false
	 */
	public function get($limit = null, $offset = null, $callback = null) {

		$this->normalizeOptions();

		$qb = Select::fromTable('system_log');
		$qb->select('*');
		$wheres = $this->buildQuery($qb);
		if ($wheres) {
			$qb->where($wheres);
		}

		if ($limit) {
			$qb->setMaxResults($limit);
			$qb->setFirstResult($offset);
		}

		$qb->orderBy('time_created', 'DESC');

		return _elgg_services()->db->getData($qb, $this->callback);
	}

	/**
	 * Apply correct execution method based on calculation, count or other criteria
	 * @return mixed
	 */
	public function execute() {

		if ($this->count) {
			return $this->count();
		}

		return $this->get($this->limit, $this->offset);
	}

	/**
	 * Normalizes options
	 * @return void
	 */
	protected function normalizeOptions() {
		$defaults = [
			'limit' => elgg_get_config('default_limit'),
			'offset' => 0,
		];

		foreach ($defaults as $key => $value) {
			if (!isset($this->$key)) {
				$this->$key = $value;
			}
		}

		if (isset($this->performed_by_guid)) {
			$this->performed_by_guid = Values::normalizeGuids($this->performed_by_guid);
		}
		if (isset($this->owner_guid)) {
			$this->owner_guid = Values::normalizeGuids($this->owner_guid);
		}
		if (isset($this->object_id)) {
			$this->object_id = Values::normalizeIds($this->object_id);
		}
		if (isset($this->access_id)) {
			$this->access_id = Values::normalizeIds($this->access_id);
		}
		if (isset($this->created_after)) {
			$this->created_after = Values::normalizeTimestamp($this->created_after);
		}
		if (isset($this->created_before)) {
			$this->created_before = Values::normalizeTimestamp($this->created_before);
		}
	}

	/**
	 * Build where clauses
	 *
	 * @param QueryBuilder $qb Query builder
	 *
	 * @return CompositeExpression
	 */
	protected function buildQuery(QueryBuilder $qb) {

		$wheres = [];

		if ($this->performed_by_guid) {
			$wheres[] = $qb->compare('performed_by_guid', '=', $this->performed_by_guid, ELGG_VALUE_INTEGER);
		}
		if ($this->event) {
			$wheres[] = $qb->compare('event', '=', $this->event, ELGG_VALUE_STRING);
		}
		if ($this->object_id) {
			$wheres[] = $qb->compare('object_id', '=', $this->object_id, ELGG_VALUE_INTEGER);
		}
		if ($this->object_class) {
			$wheres[] = $qb->compare('object_class', '=', $this->object_class, ELGG_VALUE_STRING);
		}
		if ($this->object_type) {
			$wheres[] = $qb->compare('object_type', '=', $this->object_type, ELGG_VALUE_STRING);
		}
		if ($this->object_subtype) {
			$wheres[] = $qb->compare('object_subtype', '=', $this->object_subtype, ELGG_VALUE_STRING);
		}
		if ($this->ip_address) {
			$wheres[] = $qb->compare('ip_address', '=', $this->ip_address, ELGG_VALUE_STRING);
		}
		if ($this->owner_guid) {
			$wheres[] = $qb->compare('owner_guid', '=', $this->owner_guid, ELGG_VALUE_INTEGER);
		}
		if ($this->access_id || $this->access_id == ACCESS_PRIVATE) {
			$wheres[] = $qb->compare('access_id', '=', $this->access_id, ELGG_VALUE_INTEGER);
		}
		if ($this->enabled) {
			$wheres[] = $qb->compare('enabled', '=', $this->enabled, ELGG_VALUE_STRING);
		}
		if ($this->created_before || $this->created_after) {
			$wheres[] = $qb->between('time_created', $this->created_after, $this->created_before, ELGG_VALUE_INTEGER);
		}

		return $qb->merge($wheres);
	}

	/**
	 * Set callback to use on DB rows after fetch
	 *
	 * @param callable $callback Callback
	 *
	 * @return void
	 */
	public function setCallback(callable $callback) {
		$this->callback = $callback;
	}
}
