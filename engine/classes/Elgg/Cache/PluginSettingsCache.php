<?php
namespace Elgg\Cache;

use Elgg\Database;

/**
 * In memory cache of (non-user-specific, non-internal) plugin settings
 *
 * @access private
 * @since 2.1
 */
class PluginSettingsCache {

	/**
	 * The cached values.
	 *
	 * @var array GUID => string[]
	 */
	private $values = array();

	/**
	 * Set the settings cache for known plugins
	 *
	 * @param array $cache The settings from the boot data
	 * @return void
	 */
	public function setCachedValues(array $cache) {
		$this->values = $cache;
	}

	/**
	 * Get all the non-user, non-internal settings for a plugin
	 *
	 * @param int $entity_guid The GUID of the entity
	 *
	 * @return null|string[] null if settings are not loaded for this entity
	 */
	public function getAll($entity_guid) {
		return isset($this->values[$entity_guid]) ? $this->values[$entity_guid] : null;
	}

	/**
	 * Clear cache for an entity
	 *
	 * @param int $entity_guid The GUID of the entity
	 * @return void
	 */
	public function clear($entity_guid) {
		unset($this->values[$entity_guid]);
	}

	/**
	 * Clear entire cache
	 *
	 * @return void
	 */
	public function clearAll() {
		$this->values = [];
	}
}
