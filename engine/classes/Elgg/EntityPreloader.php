<?php

/**
 * Preload entities based on properties of fetched objects
 *
 * @access private
 *
 * @package Elgg.Core
 * @since   1.9.0
 */
class Elgg_EntityPreloader {

	/**
	 * @var string
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
	 * @param object[] $objects objects loaded from an Elgg query
	 *
	 * @return void
	 * @throws RuntimeException
	 */
	public function preload($objects) {
		$guids = $this->getGuidsToLoad($objects);
		// If only 1 to load, not worth the overhead of elgg_get_entities(),
		// get_entity() will handle it later.
		if (count($guids) > 1) {
			elgg_get_entities(array(
				'guids' => $guids,
			));
		}
	}

	/**
	 * Get GUIDs that need to be loaded
	 *
	 * To simplify the user API, this function accepts non-arrays and arrays containing non-objects
	 *
	 * @param mixed $objects objects loaded from an Elgg query
	 * @return int[]
	 */
	public function getGuidsToLoad($objects) {
		if (!is_array($objects) || count($objects) < 2) {
			return array();
		}
		$preload_guids = array();
		foreach ($objects as $object) {
			if (is_object($object)) {
				foreach ($this->properties as $property) {
					$guid = $object->{$property};
					if ($guid && !_elgg_retrieve_cached_entity($guid)) {
						$preload_guids[] = $guid;
					}
				}
			}
		}
		return array_unique($preload_guids);
	}
}
