<?php
namespace Elgg\Cache;

use Elgg\Database\Clauses\OrderByClause;
use Elgg\Values;
use ElggCache;
use ElggMetadata;
use Elgg\Database\Clauses\GroupByClause;

/**
 * In memory cache of known metadata values stored by entity.
 *
 * @access private
 */
class MetadataCache {

	/**
	 * @var ElggCache
	 */
	protected $cache;

	/**
	 * Constructor
	 *
	 * @param ElggCache $cache Cache
	 */
	public function __construct(ElggCache $cache) {
		$this->cache = $cache;
	}

	/**
	 * Set the visible metadata for an entity in the cache
	 *
	 * Note this does NOT invalidate any other part of the cache.
	 *
	 * @param int   $entity_guid The GUID of the entity
	 * @param array $values      The metadata values to cache
	 *
	 * @return void
	 *
	 * @interal For testing only
	 */
	public function inject($entity_guid, array $values = []) {
		$metadata = [];
		foreach ($values as $key => $value) {
			if ($value instanceof ElggMetadata) {
				$md = $value;
			} else {
				$md = new ElggMetadata();
				$md->name = $key;
				$md->value = $value;
				$md->entity_guid = $entity_guid;
			}

			$metadata[] = $md->toObject();
		}

		$this->cache->save($entity_guid, $metadata);
	}

	/**
	 * Get all entity metadata
	 *
	 * @param int $entity_guid Entity guid
	 * @return array
	 */
	public function getAll($entity_guid) {
		$metadata = $this->getEntityMetadata($entity_guid);
		if (empty($metadata)) {
			return [];
		}

		$metadata_values = [];

		foreach ($metadata as $md) {
			$metadata_values[$md->name][] = $md->value;
		}

		return array_map(function($values) {
			return count($values) > 1 ? $values : $values[0];
		}, $metadata_values);
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
		$metadata = $this->getEntityMetadata($entity_guid);
		if (empty($metadata)) {
			return null;
		}

		$values = [];

		foreach ($metadata as $md) {
			if ($md->name !== $name) {
				continue;
			}

			$values[] = $md->value;
		}

		if (empty($values)) {
			return null;
		}

		return count($values) > 1 ? $values : $values[0];

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
		$metadata = $this->getEntityMetadata($entity_guid);
		if (empty($metadata)) {
			return null;
		}

		$ids = [];

		foreach ($metadata as $md) {
			if ($md->name !== $name) {
				continue;
			}

			$ids[] = $md->id;
		}

		if (empty($ids)) {
			return null;
		}

		return count($ids) > 1 ? $ids : $ids[0];
	}

	/**
	 * Forget about all metadata for an entity.
	 *
	 * @param int $entity_guid The GUID of the entity
	 *
	 * @return void
	 */
	public function clear($entity_guid) {
		$this->invalidateByOptions([
			'guid' => $entity_guid,
		]);
	}

	/**
	 * If true, getSingle() will return an accurate values from the DB
	 *
	 * @param int $entity_guid The GUID of the entity
	 *
	 * @return bool
	 */
	public function isLoaded($entity_guid) {
		return $this->cache->load($entity_guid) !== null;
	}

	/**
	 * Clear entire cache
	 *
	 * @return void
	 */
	public function clearAll() {
		$this->invalidateByOptions([]);
	}

	/**
	 * Returns loaded entity metadata
	 *
	 * @param int $entity_guid Entity guid
	 *
	 * @return \stdClass[]|null
	 */
	public function getEntityMetadata($entity_guid) {
		$entity_guid = (int) $entity_guid;
		$metadata = $this->cache->load($entity_guid);
		if ($metadata === null) {
			$metadata = elgg_extract($entity_guid, $this->populateFromEntities($entity_guid));
		}
		
		return $metadata;
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
			_elgg_services()->sessionCache->clear();
			_elgg_services()->dataCache->clear();
		} else {
			_elgg_services()->entityTable->invalidateCache($options['guid']);
		}
	}

	/**
	 * Populate the cache from a set of entities
	 *
	 * @param int[] ...$guids Array of or single GUIDs
	 * @return array|null [guid => [metadata]]
	 */
	public function populateFromEntities(...$guids) {
		try {
			$guids = Values::normalizeGuids($guids);
		} catch (\DataFormatException $e) {
			return null;
		}

		if (empty($guids)) {
			return null;
		}

		$version = (int) _elgg_config()->version;
		if (!empty($version) && ($version < 2016110900)) {
			// can't use this during upgrade from 2.x to 3.0
			return null;
		}

		$cached_values = [];

		foreach ($guids as $i => $guid) {
			$value = $this->cache->load($guid);
			if ($value !== null) {
				$cached_values[$guid] = $value;
				unset($guids[$i]);
			}
		}

		if (empty($guids)) {
			return $cached_values;
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
		$ia = _elgg_services()->session->setIgnoreAccess(true);
		$data = _elgg_services()->metadataTable->getAll($options);
		_elgg_services()->session->setIgnoreAccess($ia);

		$values = [];

		foreach ($data as $i => $row) {
			$row->value = ($row->value_type === 'text') ? $row->value : (int) $row->value;
			$values[$row->entity_guid][] = $row;
		}

		// store always for every guid, even if there is no metadata
		foreach ($guids as $guid) {
			$metadata = elgg_extract($guid, $values, []);
			
			$this->cache->save($guid, $metadata);
			$cached_values[$guid] = $metadata;
		}
		
		return $cached_values;
	}

	/**
	 * Filter out entities whose concatenated metadata values (INTs casted as string)
	 * exceed a threshold in characters. This could be used to avoid overpopulating the
	 * cache if RAM usage becomes an issue.
	 *
	 * @param array $guids GUIDs of entities to examine
	 * @param int   $limit Limit in characters of all metadata (with ints casted to strings)
	 *
	 * @return array
	 */
	public function filterMetadataHeavyEntities(array $guids, $limit = 1024000) {

		$guids = _elgg_services()->metadataTable->getAll([
			'guids' => $guids,
			'limit' => 0,
			'callback' => function($e) {
				return (int) $e->entity_guid;
			},
			'selects' => ['SUM(LENGTH(n_table.value)) AS bytes'],
			'order_by' => [
				new OrderByClause('n_table.entity_guid'),
				new OrderByClause('n_table.time_created'),
			],
			'group_by' => [
				new GroupByClause('n_table.entity_guid'),
			],
			'having' => [
				"bytes < $limit",
			]
		]);

		return $guids ? : [];
	}
}
