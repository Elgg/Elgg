<?php

use Elgg\Access\AccessState;

/**
 * \ElggVolatileMetadataCache
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
	 * Cache metadata for an entity
	 * 
	 * @param int   $entity_guid The GUID of the entity
	 * @param array $values      The metadata values to cache
	 * @return void
	 */
	protected function saveAll($entity_guid, array $values, AccessState $access) {
		if (!$access->ignored) {
			$this->values[$entity_guid] = $values;
			$this->isSynchronized[$entity_guid] = true;
		}
	}

	/**
	 * Get the metadata for an entity
	 * 
	 * @param int $entity_guid The GUID of the entity
	 * @return array
	 */
	public function loadAll($entity_guid, AccessState $access) {
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
	 * @param int $entity_guid The GUID of the entity
	 * @return void
	 */
	protected function markOutOfSync($entity_guid, AccessState $access) {
		unset($this->isSynchronized[$entity_guid]);
	}

	/**
	 * Cache a piece of metadata
	 * 
	 * @param int                   $entity_guid    The GUID of the entity
	 * @param string                $name           The metadata name
	 * @param array|int|string|null $value          The metadata value. null means it is 
	 *                                              known that there is no fetch-able 
	 *                                              metadata under this name
	 * @param bool                  $allow_multiple Can the metadata be an array
	 * @return void
	 */
	public function save($entity_guid, $name, $value, $allow_multiple = false, AccessState $access) {
		if ($access->ignored) {
			// we don't know if what gets saves here will be available to user once
			// access control returns, hence it's best to forget :/
			$this->markUnknown($entity_guid, $name, $access);
		} else {
			if ($allow_multiple) {
				if ($this->isKnown($entity_guid, $name, $access)) {
					$existing = $this->load($entity_guid, $name, $access);
					if ($existing !== null) {
						$existing = (array) $existing;
						$existing[] = $value;
						$value = $existing;
					}
				} else {
					// we don't know whether there are unknown values, so it's
					// safest to leave that assumption
					$this->markUnknown($entity_guid, $name, $access);
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
	 * @param int    $entity_guid The GUID of the entity
	 * @param string $name        The metadata name
	 * @return array|string|int|null null = value does not exist
	 */
	public function load($entity_guid, $name, AccessState $access) {
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
	 * @param int    $entity_guid The GUID of the entity
	 * @param string $name        The metadata name
	 * @return void
	 */
	public function markUnknown($entity_guid, $name, AccessState $access) {
		unset($this->values[$entity_guid][$name]);
		$this->markOutOfSync($entity_guid, $access);
	}

	/**
	 * If true, load() will return an accurate value for this name
	 *
	 * @param int    $entity_guid The GUID of the entity
	 * @param string $name        The metadata name
	 * @return bool
	 */
	public function isKnown($entity_guid, $name, AccessState $access) {
		if (isset($this->isSynchronized[$entity_guid])) {
			return true;
		} else {
			return (isset($this->values[$entity_guid]) && array_key_exists($name, $this->values[$entity_guid]));
		}
	}

	/**
	 * Declare that metadata under this name is known to be not fetch-able from storage
	 *
	 * @param int    $entity_guid The GUID of the entity
	 * @param string $name        The metadata name
	 * @return array
	 */
	public function markEmpty($entity_guid, $name, AccessState $access) {
		$this->values[$entity_guid][$name] = null;
	}

	/**
	 * Forget about all metadata for an entity
	 *
	 * @param int $entity_guid The GUID of the entity
	 * @return void
	 */
	public function clear($entity_guid, AccessState $access) {
		unset($this->values[$entity_guid]);
		$this->markOutOfSync($entity_guid, $access);
	}

	/**
	 * Clear entire cache and mark all entities as out of sync
	 * 
	 * @return void
	 */
	public function flush() {
		$this->values = array();
		$this->isSynchronized = array();
	}

	/**
	 * Invalidate based on options passed to the global *_metadata functions
	 *
	 * @param string $action  Action performed on metadata. "delete", "disable", or "enable"
	 * @param array  $options Options passed to elgg_(delete|disable|enable)_metadata
	 *                         "guid" if given, invalidation will be limited to this entity
	 *                         "metadata_name" if given, invalidation will be limited to metadata with this name
	 * @return void
	 */
	public function invalidateByOptions($action, array $options, AccessState $access) {
		// remove as little as possible, optimizing for common cases
		if (empty($options['guid'])) {
			// safest to clear everything unless we want to make this even more complex :(
			$this->flush();
		} else {
			if (empty($options['metadata_name'])) {
				// safest to clear the whole entity
				$this->clear($options['guid'], $access);
			} else {
				switch ($action) {
					case 'delete':
						$this->markEmpty($options['guid'], $options['metadata_name'], $access);
						break;
					default:
						$this->markUnknown($options['guid'], $options['metadata_name'], $access);
				}
			}
		}
	}

	/**
	 * Populate the cache from a set of entities
	 * 
	 * @param int|array $guids Array of or single GUIDs
	 * @return void
	 */
	public function populateFromEntities($guids, AccessState $access) {
		if (empty($guids)) {
			return;
		}
		if (!is_array($guids)) {
			$guids = array($guids);
		}
		$guids = array_unique($guids);

		// could be useful at some point in future
		//$guids = $this->filterMetadataHeavyEntities($guids);

		$db_prefix = _elgg_services()->config->get('dbprefix');
		$options = array(
			'guids' => $guids,
			'limit' => 0,
			'callback' => false,
			'distinct' => false,
			'joins' => array(
				"JOIN {$db_prefix}metastrings v ON n_table.value_id = v.id",
				"JOIN {$db_prefix}metastrings n ON n_table.name_id = n.id",
			),
			'selects' => array('n.string AS name', 'v.string AS value'),
			'order_by' => 'n_table.entity_guid, n_table.time_created ASC, n_table.id ASC',

			// @todo don't know why this is necessary
			'wheres' => array(_elgg_get_access_where_sql(array('table_alias' => 'n_table'))),
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
					$this->saveAll($last_guid, $metadata, $access);
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
				$this->saveAll($guid, $metadata, $access);
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
	 * @param int   $limit Limit in characters of all metadata (with ints casted to strings)
	 * @return array
	 */
	public function filterMetadataHeavyEntities(array $guids, $limit = 1024000) {
		$db_prefix = _elgg_services()->config->get('dbprefix');

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
