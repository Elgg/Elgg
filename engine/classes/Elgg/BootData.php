<?php

namespace Elgg;

use Elgg\Database;
use Elgg\Database\EntityTable;
use Elgg\Database\Plugins;
use Elgg\Cache\Pool\InMemory;

/**
 * Serializable collection of data used to boot Elgg
 *
 * @access private
 * @since 2.1
 */
class BootData {

	/**
	 * @var \ElggSite
	 */
	private $site;

	/**
	 * @var array
	 */
	private $config_values = [];

	/**
	 * @var \stdClass[]
	 */
	private $subtype_data = [];

	/**
	 * @var \ElggPlugin[]
	 */
	private $active_plugins = [];

	/**
	 * @var array
	 */
	private $plugin_settings = [];

	/**
	 * Populate the boot data
	 *
	 * @param \stdClass      $config   Elgg CONFIG object
	 * @param \Elgg\Database $db       Elgg database
	 * @param EntityTable    $entities Entities service
	 * @param Plugins        $plugins  Plugins service
	 *
	 * @return void
	 * @throws \InstallationException
	 */
	public function populate(\stdClass $config, Database $db, EntityTable $entities, Plugins $plugins) {
		// get subtypes
		$rows = $db->getData("
			SELECT *
			FROM {$db->prefix}entity_subtypes
		");
		foreach ($rows as $row) {
			$this->subtype_data[$row->id] = $row;
		}

		// get config
		$rows = $db->getData("
			SELECT *
			FROM {$db->prefix}config
		");
		foreach ($rows as $row) {
			$this->config_values[$row->name] = unserialize($row->value);
		}
		
		if (!array_key_exists('installed', $this->config_values)) {
			// try to fetch from old pre 3.0 datalists table
			// need to do this to be able to perform an upgrade from 2.x to 3.0
			try {
				$rows = $db->getData("
					SELECT *
					FROM {$db->prefix}datalists
				");
				foreach ($rows as $row) {
					$value = $row->value;
					if ($row->name == 'processed_upgrades') {
						// config table already serializes data so no need to double serialize
						$value = unserialize($value);
					}
					$this->config_values[$row->name] = $value;
				}
			} catch (\Exception $e) {
			}
		}

		// don't pull in old config values
		/**
		 * @see \Elgg\Config::__construct sets this
		 */
		unset($this->config_values['path']);
		unset($this->config_values['dataroot']);
		unset($this->config_values['default_site']);
		
		// get site entity
		$this->site = $entities->get(1, 'site');
		if (!$this->site) {
			throw new \InstallationException("Unable to handle this request. This site is not configured or the database is down.");
		}

		// get plugins
		$this->active_plugins = $plugins->find('active');

		// get plugin settings
		if (!$this->active_plugins) {
			return;
		}

		// find GUIDs with not too many private settings
		$guids = array_map(function (\ElggPlugin $plugin) {
			return $plugin->guid;
		}, $this->active_plugins);

		// find plugin GUIDs with not too many settings
		$limit = 40;
		$set = implode(',', $guids);
		$sql = "
			SELECT entity_guid
			FROM {$db->prefix}private_settings
			WHERE entity_guid IN ($set)
			  AND name NOT LIKE 'plugin:user_setting:%'
			  AND name NOT LIKE 'elgg:internal:%'
			GROUP BY entity_guid
			HAVING COUNT(*) > $limit
		";
		$unsuitable_guids = $db->getData($sql, function ($row) {
			return (int) $row->entity_guid;
		});
		$guids = array_values($guids);
		$guids = array_diff($guids, $unsuitable_guids);

		if ($guids) {
			// get the settings
			$set = implode(',', $guids);
			$rows = $db->getData("
				SELECT entity_guid, `name`, `value`
				FROM {$db->prefix}private_settings
				WHERE entity_guid IN ($set)
				  AND name NOT LIKE 'plugin:user_setting:%'
				  AND name NOT LIKE 'elgg:internal:%'
				ORDER BY entity_guid
			");
			// make sure we show all entities as loaded
			$this->plugin_settings = array_fill_keys($guids, []);
			foreach ($rows as $i => $row) {
				$this->plugin_settings[$row->entity_guid][$row->name] = $row->value;
			}
		}
	}

	/**
	 * Get the site entity
	 *
	 * @return \ElggSite
	 */
	public function getSite() {
		return $this->site;
	}

	/**
	 * Get config values to merge into $CONFIG
	 *
	 * @return array
	 */
	public function getConfigValues() {
		return $this->config_values;
	}

	/**
	 * Get the subtype data
	 *
	 * @return \stdClass[]
	 */
	public function getSubtypeData() {
		return $this->subtype_data;
	}

	/**
	 * Get active plugins
	 *
	 * @return \ElggPlugin[]
	 */
	public function getActivePlugins() {
		return $this->active_plugins;
	}

	/**
	 * Get the plugin settings (may not include all active plugins)
	 *
	 * @return array
	 */
	public function getPluginSettings() {
		return $this->plugin_settings;
	}
}
