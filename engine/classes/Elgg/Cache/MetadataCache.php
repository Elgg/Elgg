<?php
namespace Elgg\Cache;

use Elgg\Database\Clauses\OrderByClause;
use Elgg\Values;
use ElggSharedMemoryCache;

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
	protected $values = [];

	/**
	 * @var array
	 */
	protected $ids = [];

	/**
	 * @var ElggSharedMemoryCache
	 */
	protected $cache;

	/**
	 * Constructor
	 *
	 * @param ElggSharedMemoryCache $cache Cache
	 */
	public function __construct(ElggSharedMemoryCache $cache = null) {
		if (!$cache) {
			$cache = new NullCache();
		}
		$this->cache = $cache;
	}

	/**
	 * Set the visible metadata for an entity in the cache
	 *
	 * Note this does NOT invalidate any other part of the cache.
	 *
	 * @param int   $entity_guid The GUID of the entity
	 * @param array $values      The metadata values to cache
	 * @param array $ids         The metadata ids
	 * @return void
	 *
	 * @access private For testing only
	 */
	public function inject($entity_guid, array $values, array $ids = []) {
		$this->values[$entity_guid] = $values;
		$this->ids[$entity_guid] = $ids;
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
		if (isset($this->values[$entity_guid])
				&& array_key_exists($name, $this->values[$entity_guid])) {
			return $this->values[$entity_guid][$name];
		} else {
			return null;
		}
	}

	/**
	 * Get the metadata id for a particular name
	 *
	 * Warning: You should always call isLoaded() beforehand to verify that this
	 * function's return value can be trusted.
	 *
	 * @see isLoaded
	 *
	 * @param int    $entity_guid The GUID of the entity
	 * @param string $name        The metadata name
	 *
	 * @return int[]|int|null
	 */
	public function getSingleId($entity_guid, $name) {
		if (isset($this->ids[$entity_guid])
			&& array_key_exists($name, $this->ids[$entity_guid])) {
			return $this->ids[$entity_guid][$name];
		} else {
			return null;
		}
	}

	/**
	 * Forget about all metadata for an entity.
	 *
	 * @param int $entity_guid The GUID of the entity
	 * @return void
	 */
	public function clear($entity_guid) {
		unset($this->values[$entity_guid]);
		unset($this->ids[$entity_guid]);
		$this->cache->delete($entity_guid);
	}

	/**
	 * If true, getSingle() will return an accurate values from the DB
	 *
	 * @param int $entity_guid The GUID of the entity
	 * @return bool
	 */
	public function isLoaded($entity_guid) {
		return array_key_exists($entity_guid, $this->values) && array_key_exists($entity_guid, $this->ids);
	}

	/**
	 * Clear entire cache
	 *
	 * @return void
	 */
	public function clearAll() {
		foreach (array_keys($this->values) as $guid) {
			$this->cache->delete($guid);
		}
		$this->values = [];
		$this->ids = [];
	}

	/**
	 * Returns loaded entity metadata
	 *
	 * @param int $entity_guid Entity guid
	 * @return array|null
	 */
	public function getEntityMetadata($entity_guid) {
		if (!$this->isLoaded($entity_guid)) {
			$this->populateFromEntities($entity_guid);
		}

		if ($this->isLoaded($entity_guid)) {
			return [
				'values' => $this->values[$entity_guid],
				'ids' => $this->ids[$entity_guid],
			];
		}

		return null;
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
			_elgg_services()->entityCache->clear();
		} else {
			$this->clear($options['guid']);
			_elgg_services()->entityCache->remove($options['guid']);
		}
	}

	/**
	 * Populate the cache from a set of entities
	 *
	 * @param int[] ...$guids Array of or single GUIDs
	 * @return void
	 */
	public function populateFromEntities(...$guids) {
		$guids = Values::normalizeGuids($guids);
		if (empty($guids)) {
			return;
		}

		$version = (int) _elgg_config()->version;
		if (!empty($version) && ($version < 2016110900)) {
			// can't use this during upgrade from 2.x to 3.0
			return;
		}

		foreach ($guids as $i => $guid) {
			$value = $this->cache->load($guid);
			if ($value !== false) {
				$data = unserialize($value);
				$this->values[$guid] = $data['values'];
				$this->ids[$guid] = $data['ids'];
				unset($guids[$i]);
			}
		}

		if (empty($guids)) {
			return;
		}

		// could be useful at some point in future
		//$guids = $this->filterMetadataHeavyEntities($guids);

		$options = [
			'guids' => $guids,
			'limit' => 0,
			'callback' => false,
			'distinct' => false,
			'order_by' => [
				new OrderByClause('n_table.entity_guid', 'asc'),
				new OrderByClause('n_table.time_created', 'asc'),
				new OrderByClause('n_table.id', 'asc')
			],
		];

		// We already have a loaded entity, so we can ignore entity access clauses
		$ia = elgg_set_ignore_access(true);
		$data = _elgg_services()->metadataTable->getAll($options);
		elgg_set_ignore_access($ia);

		// make sure we show all entities as loaded
		foreach ($guids as $guid) {
			$this->values[$guid] = null;
			$this->ids[$guid] = null;
		}

		// build up metadata for each entity, save when GUID changes (or data ends)
		foreach ($data as $i => $row) {
			$id = $row->id;
			$name = $row->name;
			$value = ($row->value_type === 'text') ? $row->value : (int) $row->value;
			$guid = $row->entity_guid;

			if (isset($this->values[$guid][$name])) {
				$this->values[$guid][$name] = (array) $this->values[$guid][$name];
				$this->values[$guid][$name][] = $value;
			} else {
				$this->values[$guid][$name] = $value;
			}

			if (isset($this->ids[$guid][$name])) {
				$this->ids[$guid][$name] = (array) $this->ids[$guid][$name];
				$this->ids[$guid][$name][] = $id;
			} else {
				$this->ids[$guid][$name] = $id;
			}
		}

		foreach ($guids as $guid) {
			$this->cache->save($guid, serialize([
				'values' => $this->values[$guid],
				'ids' => $this->ids[$guid],
			]));
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

		$data = _elgg_services()->metadataTable->getAll([
			'guids' => $guids,
			'limit' => 0,
			'callback' => false,
			'selects' => ['SUM(LENGTH(n_table.value)) AS bytes'],
			'order_by' => 'n_table.entity_guid, n_table.time_created ASC',
			'group_by' => 'n_table.entity_guid',
		]);

		// don't cache if metadata for entity is over 10MB (or rolled INT)
		foreach ($data as $row) {
			if ($row->bytes > $limit || $row->bytes < 0) {
				array_splice($guids, array_search($row->entity_guid, $guids), 1);
			}
		}
		return $guids;
	}
}
