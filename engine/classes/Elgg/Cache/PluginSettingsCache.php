<?php
namespace Elgg\Cache;

use Elgg\Database;

/**
 * In memory cache of (non-user-specific, non-internal) plugin settings
 *
 * @access private
 */
class PluginSettingsCache {

	/**
	 * The cached values.
	 *
	 * @var array GUID => string[]
	 */
	private $values = array();

	/**
	 * GUIDs of plugins for which we will later load settings
	 *
	 * @var int[]
	 */
	private $plugin_guids = array();

	/**
	 * @var Database
	 */
	private $db;

	/**
	 * Constructor
	 *
	 * @param Database $db Elgg Database
	 */
	public function __construct(Database $db) {
		$this->db = $db;
	}

	/**
	 * Set the settings for a plugin in the cache
	 *
	 * Note this does NOT invalidate any other part of the cache.
	 *
	 * @param int   $entity_guid The GUID of the entity
	 * @param array $values      The settings to cache
	 * @return void
	 *
	 * @access private For testing only
	 */
	public function inject($entity_guid, array $values) {
		$this->values[$entity_guid] = $values;
	}

	/**
	 * Get all the non-user, non-internal settings for a plugin
	 *
	 * @param int $entity_guid The GUID of the entity
	 *
	 * @return null|string[] null if settings are not loaded for this entity
	 */
	public function getAll($entity_guid) {
		$this->lazyLoad();
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
		$this->plugin_guids = [];
	}

	/**
	 * Populate the cache from a set of plugins
	 *
	 * @param int|array $guids Array of GUIDs
	 * @return void
	 */
	public function populateFromPlugins(array $guids) {
		$this->plugin_guids = $guids;
	}

	/**
	 * Lazy-load settings
	 *
	 * @return void
	 */
	protected function lazyLoad() {
		if (!$this->plugin_guids) {
			return;
		}

		$guids = $this->getCacheableGuids($this->plugin_guids);
		$set = '(' . implode(',', $guids) . ')';

		$db_prefix = $this->db->getTablePrefix();
		$data = $this->db->getData("
			SELECT entity_guid, `name`, `value`
			FROM {$db_prefix}private_settings
			WHERE entity_guid IN $set
			  AND name NOT LIKE 'plugin:user_setting:%'
			  AND name NOT LIKE 'elgg:internal:%'
			ORDER BY entity_guid
		");

		// make sure we show all entities as loaded
		$this->values = array_fill_keys($guids, []);

		foreach ($data as $i => $row) {
			$this->values[$row->entity_guid][$row->name] = $row->value;
		}

		$this->plugin_guids = [];
	}

	/**
	 * Filter a set of plugin GUIDs to those having less than a preset number of settings
	 *
	 * If a plugin has a lot of settings, we don't bother preloading them
	 *
	 * @param array $guids Plugin GUIDs
	 * @param int   $limit Limit to number of settings
	 *
	 * @return int[]
	 */
	protected function getCacheableGuids(array $guids, $limit = 30) {
		if (!$guids) {
			return [];
		}

		$limit = (int)($limit);
		$db_prefix = $this->db->getTablePrefix();
		$set = '(' . implode(',', $guids) . ')';

		$sql = "
			SELECT entity_guid
			FROM {$db_prefix}private_settings
			WHERE entity_guid IN $set
			  AND name NOT LIKE 'plugin:user_setting:%'
			  AND name NOT LIKE 'elgg:internal:%'
			GROUP BY entity_guid
			HAVING COUNT(*) > $limit
		";
		$callback = function ($row) {
			return (int)$row->entity_guid;
		};

		$unsuitable_guids = $this->db->getData($sql, $callback);
		$guids = array_values($guids);

		return array_diff($guids, $unsuitable_guids);
	}
}
