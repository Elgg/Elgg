<?php

namespace Elgg\Cache;

use Elgg\Config;
use Elgg\Exceptions\DataFormatException;
use Elgg\Values;

/**
 * In memory cache of known metadata values stored by entity.
 *
 * @internal
 */
class MetadataCache extends CacheService {
	
	/**
	 * Constructor
	 *
	 * @param Config $config Elgg config
	 */
	public function __construct(protected Config $config) {
		$flags = CompositeCache::CACHE_RUNTIME;
		
		$this->cache = new CompositeCache('metadata_cache', $this->config, $flags);
	}
	
	/**
	 * Populate the cache from a set of entities
	 *
	 * @param mixed ...$guids Array of entities or GUIDs
	 * @return array|null [guid => [metadata]]
	 */
	public function populateFromEntities(...$guids): ?array {
		try {
			$guids = Values::normalizeGuids($guids);
		} catch (DataFormatException $e) {
			return null;
		}
		
		if (empty($guids)) {
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
		
		$data = _elgg_services()->metadataTable->getRowsForGuids($guids);
		
		$values = [];
		foreach ($data as $row) {
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
}
