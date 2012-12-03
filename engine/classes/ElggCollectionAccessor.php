<?php

/**
 * This is used to access/edit individual GUIDs in a collection.
 *
 * Use ElggCollection::getAccessor() to get one of these objects.
 *
 * @note entity_relationships does not have a priority column, so this implementation uses `id`
 */
class ElggCollectionAccessor {

	/**
	 * @var string
	 */
	protected $relationship_table;

	/**
	 * @var string
	 */
	protected $relationship_key;

	/**
	 * @var int
	 */
	protected $entity_guid;

	/**
	 * @var ElggCollection
	 */
	protected $coll;

	/**
	 * @var ElggDatabase
	 */
	protected $db;

	/**
	 * @param ElggCollection $collection
	 * @param ElggDatabase $db
	 * @throws InvalidArgumentException
	 */
	public function __construct(ElggCollection $collection, ElggDatabase $db = null) {
		if ($collection->isDeleted()) {
			throw new InvalidArgumentException('Collection must not be already deleted');
		}
		$this->coll = $collection;
		$this->entity_guid = $collection->getEntityGuid();
		$this->relationship_key = $collection->getRelationshipKey();
		$this->relationship_table = elgg_get_config('dbprefix') . ElggCollection::TABLE_UNPREFIXED;
		if (!$db) {
			$db = elgg_get_database();
		}
		$this->db = $db;
	}

	/**
	 * Add item(s) to the end of the collection. Already existing items are not added/moved.
	 *
	 * @param array|int|ElggEntity $new_items
	 * @return bool success
	 */
	public function push($new_items) {
		if (! $this->coll->canEdit()) {
			return false;
		}
		if (! $new_items) {
			return true;
		}
		$new_items = $this->castPositiveInt($this->castArray($new_items));

		// remove existing from new list
		$existing_items = $this->intersect($new_items);
		foreach ($existing_items as $i => $item) {
			$existing_items[$i] = $item->getValue();
		}
		$new_items = array_diff($new_items, $existing_items);

		foreach ($new_items as $i => $item) {
			$new_items[$i] = new ElggCollectionItem($item);
		}
		return $this->insertItems($new_items);
	}

	/**
	 * Get number of items
	 *
	 * @return int|bool
	 */
	public function count() {
		return $this->fetchItems(true, '', 0, null, true);
	}

	/**
	 * Move an item to just after another item
	 *
	 * @todo refactor duplicate code with moveUp()
	 *
	 * @param int|ElggEntity $moving_item
	 * @param int|ElggEntity $after_item
	 * @return bool success
	 */
	public function moveDown($moving_item, $after_item) {
		if (! $this->coll->canEdit()) {
			return false;
		}
		$moving_item = $this->castPositiveInt($moving_item);
		$after_item = $this->castPositiveInt($after_item);
		if ($moving_item == $after_item) {
			return true;
		}

		$priorities = $this->getPriorities(array($moving_item, $after_item));
		if (count($priorities) < 2) {
			return false;
		}

		// get full list of rows that must change
		$where = "{PRIORITY} <= {$priorities[$after_item]} AND {PRIORITY} >= {$priorities[$moving_item]}";
		$items_moving = $this->fetchItems(true, $where);
		if (!$items_moving) {
			// $item was probably below $before_item
			return false;
		}

		// Since ID is a key column in relationships, we can't have duplicate keys. The sane way to change IDs
		// is to delete the rows and reinsert them

		// build new list of rows to be inserted later
		$priorities = array_keys($items_moving);
		$items_moving = array_values($items_moving);

		// rearrange items, make priorities match old
		$tmp = array_shift($items_moving);
		array_push($items_moving, $tmp);
		foreach ($items_moving as $i => $item) {
			$item->setPriority($priorities[$i]);
		}

		// replace rows
		$this->remove($items_moving);
		return $this->insertItems($items_moving);
	}

	/**
	 * Move an item to just above another item
	 *
	 * @param int|ElggEntity $moving_item
	 * @param int|ElggEntity $before_item
	 * @return bool success
	 */
	public function moveUp($moving_item, $before_item) {
		if (! $this->coll->canEdit()) {
			return false;
		}
		$moving_item = $this->castPositiveInt($moving_item);
		$before_item = $this->castPositiveInt($before_item);
		if ($moving_item == $before_item) {
			return true;
		}

		$priorities = $this->getPriorities(array($moving_item, $before_item));
		if (count($priorities) < 2) {
			return false;
		}

		// get full list of rows that must change
		$where = "{PRIORITY} >= {$priorities[$before_item]} AND {PRIORITY} <= {$priorities[$moving_item]}";
		$items_moving = $this->fetchItems(true, $where);
		if (!$items_moving) {
			// $item was probably above $before_item
			return false;
		}

		// Since ID is a key column in relationships, we can't have duplicate keys. The sane way to change IDs
		// is to delete the rows and reinsert them

		// build new list of rows to be inserted later
		$priorities = array_keys($items_moving);
		$items_moving = array_values($items_moving);

		// rearrange items, make priorities match old
		$tmp = array_pop($items_moving);
		array_unshift($items_moving, $tmp);
		foreach ($items_moving as $i => $item) {
			$item->setPriority($priorities[$i]);
		}

		// replace rows
		$this->remove($items_moving);
		return $this->insertItems($items_moving);
	}

	/**
	 * @return int|bool
	 */
	public function removeAll() {
		if (!$this->coll->canEdit()) {
			return false;
		}
		return $this->db->deleteData($this->preprocessSql("
			DELETE FROM {TABLE}
			WHERE {IN_COLLECTION}
		"));
	}

	/**
	 * Remove items
	 *
	 * @param array|int|ElggEntity|ElggCollectionItem $items
	 * @return int|bool
	 */
	public function remove($items) {
		if (! $this->coll->canEdit()) {
			return false;
		}
		if (! $items) {
			return true;
		}
		$items = $this->castPositiveInt($this->castArray($items));
		return $this->db->deleteData($this->preprocessSql("
			DELETE FROM {TABLE}
			WHERE {IN_COLLECTION} AND {ITEM} IN (" . implode(',', $items) . ")
		"));
	}

	/**
	 * Remove item(s) from the beginning.
	 *
	 * @param int $num
	 * @return int|bool num rows removed
	 */
	public function removeFromBeginning($num = 1) {
		return $this->removeMultipleFrom($num, true);
	}

	/**
	 * Remove item(s) from the end.
	 *
	 * @param int $num
	 * @return int|bool num rows removed
	 */
	public function removeFromEnd($num = 1) {
		return $this->removeMultipleFrom($num, false);
	}

	/**
	 * Do any of the provided items appear in the collection?
	 *
	 * @param array|int|ElggEntity|ElggCollectionItem $items
	 * @return bool
	 */
	public function hasAnyOf($items) {
		return (bool) $this->intersect($items);
	}

	/**
	 * Do all of the provided items appear in the collection?
	 *
	 * @param array|int|ElggEntity|ElggCollectionItem $items
	 * @return bool
	 */
	public function hasAllOf($items) {
		if (!is_array($items)) {
			return $this->hasAnyOf($items);
		}
		return count($this->intersect($items)) === count($items);
	}

	/**
	 * @param int|ElggEntity $item
	 * @return bool|int 0-indexed position of item in collection or false if not found
	 */
	public function indexOf($item) {
		$item = $this->castPositiveInt($item);
		$row = $this->db->getDataRow($this->preprocessSql("
			SELECT COUNT(*) AS cnt
			FROM {TABLE}
			WHERE {IN_COLLECTION}
			  AND {PRIORITY} <=
				(SELECT {PRIORITY} FROM {TABLE}
				WHERE {IN_COLLECTION} AND {ITEM} = $item
				ORDER BY {PRIORITY}
				LIMIT 1)
			ORDER BY {PRIORITY}
		"));
		return ($row->cnt == 0) ? false : (int)$row->cnt - 1;
	}

	/**
	 * Similar behavior as array_slice (w/o the first param)
	 *
	 * Note: the large numbers in these queries is to make up for MySQL's lack of
	 * support for offset without limit: http://stackoverflow.com/a/271650/3779
	 *
	 * @param int $offset
	 * @param int|null $length
	 * @return array
	 */
	public function slice($offset = 0, $length = null) {
		if ($length !== null) {
			if ($length == 0) {
				return array();
			}
			$length = (int)$length;
		}
		$offset = (int)$offset;
		if ($offset == 0) {
			if ($length === null) {
				return $this->fetchValues();
			} elseif ($length > 0) {
				return $this->fetchValues(true, '', 0, $length);
			} else {
				// length < 0
				return array_reverse($this->fetchValues(false, '', - $length));
			}
		} elseif ($offset > 0) {
			if ($length === null) {
				return $this->fetchValues(true, '', $offset);
			} elseif ($length > 0) {
				return $this->fetchValues(true, '', $offset, $length);
			} else {
				// length < 0
				$sql_length = -$length;
				$rows = $this->db->getData($this->preprocessSql("
					SELECT {ITEM} FROM (
						SELECT {PRIORITY}, {ITEM} FROM {TABLE}
						WHERE {IN_COLLECTION}
						ORDER BY {PRIORITY} DESC
						LIMIT $sql_length, 18446744073709551615
					) AS q1
					ORDER BY {PRIORITY}
					LIMIT $offset, 18446744073709551615
				"));
			}
		} else {
			// offset < 0
			if ($length === null) {
				return array_reverse($this->fetchValues(false, '', 0, - $offset));
			} elseif ($length > 0) {
				$sql_offset = -$offset;
				$rows = $this->db->getData($this->preprocessSql("
					SELECT {ITEM} FROM (
						SELECT {PRIORITY}, {ITEM} FROM {TABLE}
						WHERE {IN_COLLECTION}
						ORDER BY {PRIORITY} DESC
						LIMIT $sql_offset
					) AS q1
					ORDER BY {PRIORITY}
					LIMIT $length
				"));
			} else {
				// length < 0
				$sql_offset = -$offset;
				$sql_length = -$length;
				$rows = $this->db->getData($this->preprocessSql("
					SELECT {ITEM} FROM (
						SELECT {PRIORITY}, {ITEM} FROM {TABLE}
						WHERE {IN_COLLECTION}
						ORDER BY {PRIORITY} DESC
						LIMIT $sql_offset
					) AS q1
					ORDER BY {PRIORITY} DESC
					LIMIT $sql_length, 18446744073709551615
				"));
				if ($rows) {
					$rows = array_reverse($rows);
				}
			}
		}
		$items = array();
		if ($rows) {
			foreach ($rows as $row) {
				$items[] = (int)$row->{ElggCollection::COL_ITEM};
			}
		}
		return $items;
	}

	/**
	 * @param ElggCollectionItem[] $items
	 * @return bool
	 */
	protected function insertItems(array $items) {
		if (!$items) {
			return true;
		}
		$rows = array();
		$entity_guid = $this->db->quote($this->entity_guid);
		$key = $this->db->quote($this->relationship_key);

		foreach ($items as $item) {
			$value = $this->db->quote($item->getValue());
			$time = $this->db->quote($item->getTime());
			$priority = $item->getPriority();
			$priority = $priority ? $this->db->quote($priority) : 'null';
			$rows[] = "($priority, $value, $key, $entity_guid, $time)";
		}
		$this->db->insertData($this->preprocessSql("
			INSERT INTO {TABLE}
			({PRIORITY}, {ITEM}, {KEY}, {ENTITY_GUID}, {TIME})
			VALUES " . implode(', ', $rows) . "
		"));
		return true;
	}

	/**
	 * Return only items that also appear in the collection (and in the order they
	 * appear in the collection)
	 *
	 * @param array|int|ElggEntity $items
	 * @return ElggCollectionItem[]
	 *
	 * @access private
	 */
	protected function intersect($items) {
		if (! $items) {
			return array();
		}
		$items = $this->castPositiveInt($this->castArray($items));
		return $this->fetchItems(true, '{ITEM} IN (' . implode(',', $items) . ')');
	}

	/**
	 * @param int|ElggEntity|array $items one or more items
	 * @return int|bool|array for each item given, the ID will be returned, or false if the item is not found.
	 *                        If the given item was an array, an array will be returned with a key for each item
	 *
	 * @access private
	 */
	protected function getPriorities($items) {
		$is_array = is_array($items);
		$items = $this->castPositiveInt($this->castArray($items));
		$rows = $this->db->getData($this->preprocessSql("
			SELECT {PRIORITY}, {ITEM} FROM {TABLE}
			WHERE {IN_COLLECTION} AND {ITEM} IN (" . implode(',', $items) . ")
		"));
		if (!$is_array) {
			return $rows ? $rows[0]->{ElggCollection::COL_PRIORITY} : false;
		}
		$ret = array();
		if ($rows) {
			foreach ($rows as $row) {
				$ret->{ElggCollection::COL_ITEM} = $row->{ElggCollection::COL_PRIORITY};
			}
		}
		return $ret;
	}

	/**
	 * Fetch ElggCollectionItem instances by query (or a count), with keys being the priorities
	 *
	 * @param bool $ascending
	 * @param string $where
	 * @param int $offset
	 * @param int|null $limit
	 * @param bool $count_only if true, return will be number of rows
	 * @return ElggCollectionItem[]|int|bool
	 *
	 * @access private
	 */
	protected function fetchItems($ascending = true, $where = '', $offset = 0,
								  $limit = null, $count_only = false) {
		$where_clause = "WHERE {ENTITY_GUID} = $this->entity_guid";
		if (! empty($where)) {
			$where_clause .= " AND ($where)";
		}

		$asc_desc = $ascending ? '' : 'DESC';
		$order_by_clause = "ORDER BY {PRIORITY} $asc_desc";

		if ($offset == 0 && $limit === null) {
			$limit_clause = "";
		} elseif ($offset == 0) {
			$limit_clause = "LIMIT $limit";
		} else {
			// has offset
			if ($limit === null) {
				// must provide LIMIT to specify offset (MySQL limitation)
				// http://stackoverflow.com/a/271650/3779
				$limit_clause = "LIMIT $offset, 18446744073709551615";
			} else {
				$limit_clause = "LIMIT $offset, $limit";
			}
		}

		$columns = '{PRIORITY}, {ITEM}, {TIME}';
		if ($count_only) {
			$columns = 'COUNT(*) AS cnt';
			$order_by_clause = '';
		}
		$rows = $this->db->getData($this->preprocessSql("
			SELECT $columns FROM {TABLE}
			$where_clause $order_by_clause $limit_clause
		"));
		if ($count_only) {
			return isset($rows[0]->cnt) ? (int)$rows[0]->cnt : false;
		}

		$items = array();
		if ($rows) {
			foreach ($rows as $row) {
				$items[$row->{ElggCollection::COL_PRIORITY}] = new ElggCollectionItem(
					$row->{ElggCollection::COL_ITEM},
					$row->{ElggCollection::COL_PRIORITY},
					$row->{ElggCollection::COL_TIME}
				);
			}
		}
		return $items;
	}

	/**
	 * Fetch array of item values by query (or a count)
	 *
	 * @param bool $ascending
	 * @param string $where
	 * @param int $offset
	 * @param int|null $limit
	 * @param bool $count_only if true, return will be number of rows
	 * @return array|int|bool keys will be 0-indexed
	 *
	 * @see fetchItems()
	 *
	 * @access private
	 */
	protected function fetchValues($ascending = true, $where = '', $offset = 0,
								  $limit = null, $count_only = false) {
		$items = $this->fetchItems($ascending, $where, $offset, $limit, $count_only);
		if (is_array($items)) {
			$new_items = array();
			foreach ($items as $item) {
				$new_items[] = $item->getValue();
			}
			$items = $new_items;
		} elseif ($items instanceof ElggCollectionItem) {
			$items = $items->getValue();
		}
		return $items;
	}

	/**
	 * Remove several from the beginning/end
	 *
	 * @param int $num
	 * @param bool $from_beginning remove from the beginning of the collection?
	 * @return int|bool num rows removed
	 *
	 * @access private
	 */
	protected function removeMultipleFrom($num, $from_beginning) {
		if (! $this->coll->canEdit()) {
			return false;
		}
		$num = (int)max($num, 0);
		$asc_desc = $from_beginning ? 'ASC' : 'DESC';
		return $this->db->deleteData($this->preprocessSql("
			DELETE FROM {TABLE}
			WHERE {IN_COLLECTION}
			ORDER BY {PRIORITY} $asc_desc
			LIMIT $num
		"));
	}

	/**
	 * Cast a single value/entity to an int (or an array of values to an array of ints)
	 *
	 * @param mixed|array $i
	 * @return int|array
	 * @throws InvalidParameterException
	 *
	 * @access private
	 */
	protected function castPositiveInt($i) {
		$is_array = is_array($i);
		if (! $is_array) {
			$i = array($i);
		}
		foreach ($i as $k => $v) {
			if (! is_int($v) || $v <= 0) {
				if (! is_numeric($v)) {
					if ($v instanceof ElggEntity) {
						$v = $v->getGUID();
					} elseif ($v instanceof ElggCollectionItem) {
						$v = $v->getValue();
					}
				}
				$v = (int)$v;
				if ($v < 1) {
					throw new InvalidParameterException(elgg_echo('InvalidParameterException:UnrecognisedValue'));
				}
				$i[$k] = $v;
			}
		}
		return $is_array ? $i : $i[0];
	}

	/**
	 * Cast to array without fear of breaking objects
	 *
	 * @param mixed
	 * @return array
	 *
	 * @access private
	 */
	protected function castArray($i) {
		return is_array($i) ? $i : array($i);
	}

	/**
	 * @param string $sql
	 * @return string
	 *
	 * @access private
	 */
	protected function preprocessSql($sql) {
		return strtr($sql, array(
			'{TABLE}' => $this->relationship_table,
			'{PRIORITY}' => ElggCollection::COL_PRIORITY,
			'{ITEM}' => ElggCollection::COL_ITEM,
			'{KEY}' => ElggCollection::COL_KEY,
			'{TIME}' => ElggCollection::COL_TIME,
			'{ENTITY_GUID}' => ElggCollection::COL_ENTITY_GUID,
			'{IN_COLLECTION}' => "(" . ElggCollection::COL_ENTITY_GUID . " = $this->entity_guid "
				. "AND " . ElggCollection::COL_KEY . " = '$this->relationship_key')",
		));
	}

	/**
	 * Get an item by index (can be negative!)
	 *
	 * @param int $index
	 * @return int|null
	 *
	 * @access private
	 */
	/*public function get($index) {
		$item = $this->fetchItems(true, '', $index, 1);
		return $item ? array_pop($item) : null;
	}*/

	/**
	 * Remove items by priority
	 *
	 * @param array $priorities
	 * @return int|bool
	 *
	 * @access private
	 */
	/*public function removeByPriority($priorities) {
		if (! $this->coll->canEdit()) {
			return false;
		}
		if (! $priorities) {
			return true;
		}
		$priorities = $this->castPositiveInt((array)$priorities);
		return $this->db->deleteData($this->preprocessSql("
			DELETE FROM {TABLE}
			WHERE {IN_COLLECTION}
			  AND {PRIORITY} IN (" . implode(',', $priorities) . ")
		"));
	}*/
}
