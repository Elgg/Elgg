<?php

namespace Elgg;

use Elgg\Database\EntityTable;
use Elgg\Database\Plugins;

/**
 * Serializable collection of data used to boot Elgg
 *
 * @access private
 * @since 2.1
 */
class BootData {

	/**
	 * @var \ElggSite|false
	 */
	private $site = false;

	/**
	 * @var \ElggPlugin[]
	 */
	private $active_plugins;

	/**
	 * @var array
	 */
	private $plugin_settings = [];

	/**
	 * @var array
	 */
	private $plugin_metadata = [];

	/**
	 * Populate the boot data
	 *
	 * @param Config      $config    Elgg config
	 * @param Database    $db        Elgg database
	 * @param EntityTable $entities  Entities service
	 * @param Plugins     $plugins   Plugins service
	 * @param bool        $installed Is the site installed?
	 *
	 * @return void
	 * @throws \InstallationException
	 * @throws \InvalidParameterException
	 * @throws \DatabaseException
	 * @throws \ClassException
	 */
	public function populate(Config $config, Database $db, EntityTable $entities, Plugins $plugins, $installed) {

		// get site entity
		$this->site = $entities->get(1, 'site');
		if (!$this->site && $installed) {
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

		_elgg_services()->metadataCache->populateFromEntities($guids);

		foreach ($guids as $guid) {
			$this->plugin_metadata[$guid] = _elgg_services()->metadataCache->getEntityMetadata($guid);
		}

		// find plugin GUIDs with not too many settings
		$limit = $config->bootdata_plugin_settings_limit;
		if ($limit > 0) {
			$set = implode(',', $guids);
			$sql = "
				SELECT entity_guid
				FROM {$db->prefix}private_settings
				WHERE entity_guid IN ($set)
				  AND name NOT LIKE 'plugin:user_setting:%'
				GROUP BY entity_guid
				HAVING COUNT(*) > $limit
			";
			$unsuitable_guids = $db->getData($sql, function ($row) {
				return (int) $row->entity_guid;
			});
			$guids = array_values($guids);
			$guids = array_diff($guids, $unsuitable_guids);
		}

		if ($guids) {
			// get the settings
			$set = implode(',', $guids);
			$rows = $db->getData("
				SELECT entity_guid, `name`, `value`
				FROM {$db->prefix}private_settings
				WHERE entity_guid IN ($set)
				  AND name NOT LIKE 'plugin:user_setting:%'
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
	 * @return \ElggSite|false False if not installed
	 */
	public function getSite() {
		return $this->site;
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

	/**
	 * Get plugin metadata
	 *
	 * @return array
	 */
	public function getPluginMetadata() {
		return $this->plugin_metadata;
	}
}
