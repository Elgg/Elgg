<?php
/**
 * ElggVolatileMetadataCache
 * In memory cache of known metadata values stored by entity.
 *
 * @package    Elgg.Core
 * @subpackage Cache
 *
 * @access private
 */
class ElggVolatileMetadataCache {

	/**
	 * The cached values (or null for known to be empty). If the portion of the cache
	 * is synchronized, missing values are assumed to indicate that values do not
	 * exist in storage, otherwise, we don't know what's there.
	 *
	 * @var array
	 */
	protected $values = array();

	/**
	 * Does the cache know that it contains all names fetch-able from storage?
	 * The keys are entity GUIDs and either the value exists (true) or it's not set.
	 *
	 * @var array
	 */
	protected $isSynchronized = array();

	/**
	 * @var null|bool
	 */
	protected $ignoreAccess = null;

	/**
	 * @param int $entity_guid
	 *
	 * @param array $values
	 */
	public function saveAll($entity_guid, array $values) {
		if (!$this->getIgnoreAccess()) {
			$this->values[$entity_guid] = $values;
			$this->isSynchronized[$entity_guid] = true;
		}
	}

	/**
	 * @param int $entity_guid
	 *
	 * @return array
	 */
	public function loadAll($entity_guid) {
		if (isset($this->values[$entity_guid])) {
			return $this->values[$entity_guid];
		} else {
			return array();
		}
	}

	/**
	 * Declare that there may be fetch-able metadata names in storage that this
	 * cache doesn't know about
	 *
	 * @param int $entity_guid
	 */
	public function markOutOfSync($entity_guid) {
		unset($this->isSynchronized[$entity_guid]);
	}

	/**
	 * @param $entity_guid
	 *
	 * @return bool
	 */
	public function isSynchronized($entity_guid) {
		return isset($this->isSynchronized[$entity_guid]);
	}

	/**
	 * @param int $entity_guid
	 *
	 * @param string $name
	 *
	 * @param array|int|string|null $value  null means it is known that there is no
	 *                                      fetch-able metadata under this name
	 * @param bool $allow_multiple
	 */
	public function save($entity_guid, $name, $value, $allow_multiple = false) {
		if ($this->getIgnoreAccess()) {
			// we don't know if what gets saves here will be available to user once
			// access control returns, hence it's best to forget :/
			$this->markUnknown($entity_guid, $name);
		} else {
			if ($allow_multiple) {
				if ($this->isKnown($entity_guid, $name)) {
					$existing = $this->load($entity_guid, $name);
					if ($existing !== null) {
						$existing = (array) $existing;
						$existing[] = $value;
						$value = $existing;
					}
				} else {
					// we don't know whether there are unknown values, so it's
					// safest to leave that assumption
					$this->markUnknown($entity_guid, $name);
					return;
				}
			}
			$this->values[$entity_guid][$name] = $value;
		}
	}

	/**
	 * Warning: You should always call isKnown() beforehand to verify that this
	 * function's return value should be trusted (otherwise a null return value
	 * is ambiguous).
	 *
	 * @param int $entity_guid
	 *
	 * @param string $name
	 *
	 * @return array|string|int|null null = value does not exist
	 */
	public function load($entity_guid, $name) {
		if (isset($this->values[$entity_guid]) && array_key_exists($name, $this->values[$entity_guid])) {
			return $this->values[$entity_guid][$name];
		} else {
			return null;
		}
	}

	/**
	 * Forget about this metadata entry. We don't want to try to guess what the
	 * next fetch from storage will return
	 *
	 * @param int $entity_guid
	 *
	 * @param string $name
	 */
	public function markUnknown($entity_guid, $name) {
		unset($this->values[$entity_guid][$name]);
		$this->markOutOfSync($entity_guid);
	}

	/**
	 * If true, load() will return an accurate value for this name
	 *
	 * @param int $entity_guid
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function isKnown($entity_guid, $name) {
		if (isset($this->isSynchronized[$entity_guid])) {
			return true;
		} else {
			return (isset($this->values[$entity_guid]) && array_key_exists($name, $this->values[$entity_guid]));
		}

	}

	/**
	 * Declare that metadata under this name is known to be not fetch-able from storage
	 *
	 * @param int $entity_guid
	 *
	 * @param string $name
	 *
	 * @return array
	 */
	public function markEmpty($entity_guid, $name) {
		$this->values[$entity_guid][$name] = null;
	}

	/**
	 * Forget about all metadata for an entity
	 *
	 * @param int $entity_guid
	 */
	public function clear($entity_guid) {
		$this->values[$entity_guid] = array();
		$this->markOutOfSync($entity_guid);
	}

	/**
	 * Clear entire cache and mark all entities as out of sync
	 */
	public function flush() {
		$this->values = array();
		$this->isSynchronized = array();
	}

	/**
	 * Use this value instead of calling elgg_get_ignore_access(). By default that
	 * function will be called.
	 *
	 * This setting makes this component a little more loosely-coupled.
	 *
	 * @param bool $ignore
	 */
	public function setIgnoreAccess($ignore) {
		$this->ignoreAccess = (bool) $ignore;
	}

	/**
	 * Tell the cache to call elgg_get_ignore_access() to determing access status.
	 */
	public function unsetIgnoreAccess() {
		$this->ignoreAccess = null;
	}

	/**
	 * @return bool
	 */
	protected function getIgnoreAccess() {
		if (null === $this->ignoreAccess) {
			return elgg_get_ignore_access();
		} else {
			return $this->ignoreAccess;
		}
	}

	/**
	 * Invalidate based on options passed to the global *_metadata functions
	 *
	 * @param string $action  Action performed on metadata. "delete", "disable", or "enable"
	 *
	 * @param array $options  Options passed to elgg_(delete|disable|enable)_metadata
	 *
	 *   "guid" if given, invalidation will be limited to this entity
	 *
	 *   "metadata_name" if given, invalidation will be limited to metadata with this name
	 */
	public function invalidateByOptions($action, array $options) {
		// remove as little as possible, optimizing for common cases
		if (empty($options['guid'])) {
			// safest to clear everything unless we want to make this even more complex :(
			$this->flush();
		} else {
			if (empty($options['metadata_name'])) {
				// safest to clear the whole entity
				$this->clear($options['guid']);
			} else {
				switch ($action) {
					case 'delete':
						$this->markEmpty($options['guid'], $options['metadata_name']);
						break;
					default:
						$this->markUnknown($options['guid'], $options['metadata_name']);
				}
			}
		}
	}

	/**
	 * @param int|array $guids
	 */
	public function populateFromEntities($guids) {
		if (empty($guids)) {
			return;
		}
		if (!is_array($guids)) {
			$guids = array($guids);
		}
		$guids = array_unique($guids);

		// could be useful at some point in future
		//$guids = $this->filterMetadataHeavyEntities($guids);

		$db_prefix = elgg_get_config('dbprefix');
		$options = array(
			'guids' => $guids,
			'limit' => 0,
			'callback' => false,
			'joins' => array(
				"JOIN {$db_prefix}metastrings v ON n_table.value_id = v.id",
				"JOIN {$db_prefix}metastrings n ON n_table.name_id = n.id",
			),
			'selects' => array('n.string AS name', 'v.string AS value'),
			'order_by' => 'n_table.entity_guid, n_table.time_created ASC',

			// @todo don't know why this is necessary
			'wheres' => array(get_access_sql_suffix('n_table')),
		);
		$data = elgg_get_metadata($options);

		// build up metadata for each entity, save when GUID changes (or data ends)
		$last_guid = null;
		$metadata = array();
		$last_row_idx = count($data) - 1;
		foreach ($data as $i => $row) {
			$name = $row->name;
			$value = ($row->value_type === 'text') ? $row->value : (int) $row->value;
			$guid = $row->entity_guid;
			if ($guid !== $last_guid) {
				if ($last_guid) {
					$this->saveAll($last_guid, $metadata);
				}
				$metadata = array();
			}
			if (isset($metadata[$name])) {
				$metadata[$name] = (array) $metadata[$name];
				$metadata[$name][] = $value;
			} else {
				$metadata[$name] = $value;
			}
			if (($i == $last_row_idx)) {
				$this->saveAll($guid, $metadata);
			}
			$last_guid = $guid;
		}
	}

	/**
	 * Filter out entities whose concatenated metadata values (INTs casted as string)
	 * exceed a threshold in characters. This could be used to avoid overpopulating the
	 * cache if RAM usage becomes an issue.
	 *
	 * @param array $guids GUIDs of entities to examine
	 *
	 * @param int $limit Limit in characters of all metadata (with ints casted to strings)
	 *
	 * @return array
	 */
	public function filterMetadataHeavyEntities(array $guids, $limit = 1024000) {
		$db_prefix = elgg_get_config('dbprefix');

		$options = array(
			'guids' => $guids,
			'limit' => 0,
			'callback' => false,
			'joins' => "JOIN {$db_prefix}metastrings v ON n_table.value_id = v.id",
			'selects' => array('SUM(LENGTH(v.string)) AS bytes'),
			'order_by' => 'n_table.entity_guid, n_table.time_created ASC',
			'group_by' => 'n_table.entity_guid',
		);
		$data = elgg_get_metadata($options);
		// don't cache if metadata for entity is over 10MB (or rolled INT)
		foreach ($data as $row) {
			if ($row->bytes > $limit || $row->bytes < 0) {
				array_splice($guids, array_search($row->entity_guid, $guids), 1);
			}
		}
		return $guids;
	}
}
