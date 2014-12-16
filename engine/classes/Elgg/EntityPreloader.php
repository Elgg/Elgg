<?php

namespace Elgg;

/**
 * Preload entities based on properties of fetched objects
 *
 * @access private
 *
 * @package Elgg.Core
 */
class EntityPreloader {

	/**
	 * @var string[]
	 */
	protected $properties;

	/**
	 * Configure the preloader to check these properties of fetched objects for GUIDs.
	 *
	 * @param array $guid_properties e.g. array("owner_guid")
	 */
	public function __construct(array $guid_properties) {
		$this->properties = $guid_properties;
	}

	/**
	 * Preload entities based on the given objects
	 *
	 * @param object[] $objects Objects--e.g. loaded from an Elgg query--from which we can pluck GUIDs to preload
	 *
	 * @return void
	 */
	public function preload($objects) {
		$guids = $this->getGuidsToLoad($objects);
		// If only 1 to load, not worth the overhead of elgg_get_entities(),
		// get_entity() will handle it later.
		if (count($guids) > 1) {
			call_user_func($this->_callable_entity_loader, array(
				'guids' => $guids,
			));
		}
	}

	/**
	 * Get GUIDs that need to be loaded
	 *
	 * To simplify the user API, this function accepts non-arrays and arrays containing non-objects
	 *
	 * @param object[] $objects Objects from which to pluck GUIDs
	 * @return int[]
	 */
	protected function getGuidsToLoad($objects) {
		if (!is_array($objects) || count($objects) < 2) {
			return array();
		}
		$preload_guids = array();
		foreach ($objects as $object) {
			if (is_object($object)) {
				foreach ($this->properties as $property) {
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

	/**
	 * DO NOT USE. For unit test mocking
	 * @access private
	 */
	public $_callable_cache_checker = '_elgg_retrieve_cached_entity';

	/**
	 * DO NOT USE. For unit test mocking
	 * @access private
	 */
	public $_callable_entity_loader = 'elgg_get_entities';
}
