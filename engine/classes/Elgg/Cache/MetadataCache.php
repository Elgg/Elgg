<?php
namespace Elgg\Cache;

/**
 * In memory cache of known metadata values stored by entity.
 *
 * @access private
 */
class MetadataCache {

	/**
	 * The cached values (or null for known to be empty).
	 *
	 * @var array
	 */
	protected $values = array();

	/**
	 * @var \ElggSession
	 */
	protected $session;

	/**
	 * Constructor
	 *
	 * @param \ElggSession $session The session service
	 */
	public function __construct(\ElggSession $session) {
		$this->session = $session;
	}

	/**
	 * Set the visible metadata for an entity in the cache
	 *
	 * Note this does NOT invalidate any other part of the cache.
	 *
	 * @param int   $entity_guid The GUID of the entity
	 * @param array $values      The metadata values to cache
	 * @return void
	 *
	 * @access private For testing only
	 */
	public function inject($entity_guid, array $values) {
		$this->values[$this->getAccessKey()][$entity_guid] = $values;
	}

	/**
	 * Get the metadata for a particular name. Note, this can return an array of values.
	 *
	 * Warning: You should always call isLoaded() beforehand to verify that this
	 * function's return value can be trusted.
	 *
	 * @see isLoaded
	 *
	 * @param int    $entity_guid The GUID of the entity
	 * @param string $name        The metadata name
	 *
	 * @return array|string|int|null null = value does not exist
	 */
	public function getSingle($entity_guid, $name) {
		$access_key = $this->getAccessKey();

		if (isset($this->values[$access_key][$entity_guid])
				&& array_key_exists($name, $this->values[$access_key][$entity_guid])) {
			return $this->values[$access_key][$entity_guid][$name];
		} else {
			return null;
		}
	}

	/**
	 * Forget about all metadata for an entity. For safety this affects all access states.
	 *
	 * @param int $entity_guid The GUID of the entity
	 * @return void
	 */
	public function clear($entity_guid) {
		foreach (array_keys($this->values) as $access_key) {
			unset($this->values[$access_key][$entity_guid]);
		}
	}

	/**
	 * If true, getSingle() will return an accurate values from the DB
	 *
	 * @param int $entity_guid The GUID of the entity
	 * @return bool
	 */
	public function isLoaded($entity_guid) {
		$access_key = $this->getAccessKey();

		if (empty($this->values[$access_key])) {
			return false;
		}
		return array_key_exists($entity_guid, $this->values[$access_key]);
	}

	/**
	 * Clear entire cache
	 *
	 * @return void
	 */
	public function clearAll() {
		$this->values = array();
	}

	/**
	 * Invalidate based on options passed to the global *_metadata functions
	 *
	 * @param array $options Options passed to elgg_(delete|disable|enable)_metadata
	 *                       "guid" if given, invalidation will be limited to this entity
	 * @return void
	 */
	public function invalidateByOptions(array $options) {
		if (empty($options['guid'])) {
			$this->clearAll();
		} else {
			$this->clear($options['guid']);
		}
	}

	/**
	 * Populate the cache from a set of entities
	 *
	 * @param int|array $guids Array of or single GUIDs
	 * @return void
	 */
	public function populateFromEntities($guids) {
		if (empty($guids)) {
			return;
		}

		$access_key = $this->getAccessKey();

		if (!is_array($guids)) {
			$guids = array($guids);
		}
		$guids = array_unique($guids);

		// could be useful at some point in future
		//$guids = $this->filterMetadataHeavyEntities($guids);

		$db_prefix = _elgg_services()->db->getTablePrefix();
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
		$data = _elgg_services()->metadataTable->getAll($options);

		// make sure we show all entities as loaded
		foreach ($guids as $guid) {
			$this->values[$access_key][$guid] = null;
		}

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
					$this->values[$access_key][$last_guid] = $metadata;
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
				$this->values[$access_key][$guid] = $metadata;
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
		$data = _elgg_services()->metadataTable->getAll($options);
		// don't cache if metadata for entity is over 10MB (or rolled INT)
		foreach ($data as $row) {
			if ($row->bytes > $limit || $row->bytes < 0) {
				array_splice($guids, array_search($row->entity_guid, $guids), 1);
			}
		}
		return $guids;
	}

	/**
	 * Get a key to represent the access ability of the system. This is used to shard the cache array.
	 *
	 * @return string E.g. "ignored" or "123"
	 */
	protected function getAccessKey() {
		if ($this->session->getIgnoreAccess()) {
			return "ignored";
		}
		return (string)$this->session->getLoggedInUserGuid();
	}
}
