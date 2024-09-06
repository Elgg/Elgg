<?php

namespace Elgg;

use Elgg\Cache\EntityCache;
use Elgg\Database\Entities;

/**
 * Preload entities based on properties of fetched objects
 *
 * @internal
 */
class EntityPreloader {
	
	const MAX_PRELOAD = 256;
	
	/**
	 * Constructor
	 *
	 * @param EntityCache $entity_cache Entity cache
	 */
	public function __construct(protected EntityCache $entity_cache) {
	}

	/**
	 * Preload entities based on the given objects
	 *
	 * @param object[] $objects         Objects--e.g. loaded from an Elgg query--from which we can
	 *                                  pluck GUIDs to preload
	 * @param string[] $guid_properties e.g. array("owner_guid")
	 *
	 * @return void
	 */
	public function preload(array $objects, array $guid_properties): void {
		$guids = $this->getGuidsToLoad($objects, $guid_properties);
		
		// If only 1 to load, not worth the overhead of elgg_get_entities(),
		// get_entity() will handle it later.
		if (count($guids) > 1) {
			Entities::find([
				'guids' => $guids,
				'limit' => self::MAX_PRELOAD,
				'order_by' => false,
			]);
		}
	}

	/**
	 * Get GUIDs that need to be loaded
	 *
	 * To simplify the user API, this function accepts non-arrays and arrays containing non-objects
	 *
	 * @param object[] $objects         Objects from which to pluck GUIDs
	 * @param string[] $guid_properties e.g. array("owner_guid")
	 *
	 * @return int[]
	 */
	protected function getGuidsToLoad(array $objects, array $guid_properties): array {
		if (count($objects) < 2) {
			return [];
		}
		
		$preload_guids = [];
		foreach ($objects as $object) {
			if (!is_object($object)) {
				continue;
			}
			
			foreach ($guid_properties as $property) {
				if (empty($object->{$property})) {
					continue;
				}
				
				$guid = (int) $object->{$property};
				if ($guid && is_null($this->entity_cache->load($guid))) {
					$preload_guids[] = $guid;
				}
			}
		}
		
		return array_unique($preload_guids);
	}
}
