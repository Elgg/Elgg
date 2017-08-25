<?php

namespace Elgg;

use Elgg\Cache\EntityCache;
use Elgg\Database\EntityTable;

/**
 * Preload entities based on properties of fetched objects
 *
 * @access private
 */
class EntityPreloader {

	/**
	 * @var callable DO NOT USE. For unit test mocking
	 * @access private
	 */
	public $_callable_cache_checker;

	/**
	 * @var callable DO NOT USE. For unit test mocking
	 * @access private
	 */
	public $_callable_entity_loader;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->_callable_cache_checker = function ($guid) {
			return elgg_get_session()->entityCache->get($guid);
		};
		$this->_callable_entity_loader = function ($options) {
			return Application::getInstance()->_services->entityTable->getEntities($options);
		};
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
	public function preload($objects, array $guid_properties) {
		$guids = $this->getGuidsToLoad($objects, $guid_properties);
		// If only 1 to load, not worth the overhead of elgg_get_entities(),
		// get_entity() will handle it later.
		if (count($guids) > 1) {
			call_user_func($this->_callable_entity_loader, [
				'guids' => $guids,
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
	 * @return int[]
	 */
	protected function getGuidsToLoad($objects, array $guid_properties) {
		if (!is_array($objects) || count($objects) < 2) {
			return [];
		}
		$preload_guids = [];
		foreach ($objects as $object) {
			if (is_object($object)) {
				foreach ($guid_properties as $property) {
					if (empty($object->{$property})) {
						continue;
					}
					$guid = $object->{$property};
					if ($guid && !call_user_func($this->_callable_cache_checker, $guid)) {
						$preload_guids[] = $guid;
					}
				}
			}
		}
		return array_unique($preload_guids);
	}
}
