<?php

namespace Elgg\Upgrade;

use Elgg\Database\PrivateSettingsTable;
use Elgg\Database\ConfigTable;
use Elgg\Database\Plugins;
use Elgg\PluginHooksService;
use Elgg\BatchUpgrade;
use Elgg\Logger;
use ElggUpgrade;

/**
 * Locates and registers both core and plugin upgrades
 *
 * @private
 */
class Locator {

	private $config;

	private $plugins;

	private $logger;

	private $privateSettings;

	private $hooks;

	/**
	 *
	 */
	public function __construct(ConfigTable $config, Plugins $plugins, Logger $logger, PrivateSettingsTable $privateSettings, PluginHooksService $hooks) {
		$this->config = $config;
		$this->plugins = $plugins;
		$this->logger = $logger;
		$this->privateSettings = $privateSettings;
		$this->hooks = $hooks;
	}

	/**
	 * Looks for upgrades and saves them as ElggUpgrade entities
	 *
	 * @return boolean $pending_upgrades Are there pending upgrades
	 */
	public function run() {
		//$upgrade_paths[] = $this->config->get('path') . 'engine/classes/Elgg/Upgrades';

		$data = $this->hooks->trigger('register', 'upgrades', array(), array());

		foreach ($data as $upgrade_data) {
			$plugin_id = $upgrade_data['plugin_id'];
			$version = $upgrade_data['version'];
			$class = $upgrade_data['class'];

			$upgrade_id = "{$plugin_id}:{$version}";

			if ($this->upgradeExists($upgrade_id)) {
				continue;
			}

			if (!class_exists($class)) {
				$this->logger->error("Upgrade class $class was not found");
				continue;
			}

			$test = new $class;
			if (!$test instanceof BatchUpgrade) {
				$this->logger->error("Upgrade class $class should implement BatchUpgrade");
				continue;
			}

			// Create a new ElggUpgrade to represent the upgrade in the database
			$upgrade = new ElggUpgrade();
			$upgrade->setId($upgrade_id);
			$upgrade->setClass($class);
			$upgrade->title = "{$plugin_id}:upgrade:{$version}:title";
			$upgrade->description = "{$plugin_id}:upgrade:{$version}:description";
			$upgrade->save();

			$pending_upgrades = true;
		}

		return $pending_upgrades;

		/*
		$plugins = $this->plugins->find('all');

		$pending_upgrades = false;

		foreach ($plugins as $plugin) {
			$filename = "{$plugin->getPath()}lib/upgrades.json";

			if (!file_exists($filename)) {
				continue;
			}

			$upgrades = json_decode(file_get_contents($filename));

			if (json_last_error() !== JSON_ERROR_NONE) {
				$msg = json_last_error_msg();
				$this->logger->error("Upgrade file $filename contains invalid JSON: $msg");
				continue;
			}

			foreach ($upgrades as $upgrade_data) {

				// TODO How to define an unique id?
				$upgrade_id = $plugin->getID() . $upgrade_data->title . $upgrade_data->time;

				if ($this->upgradeExists($upgrade_id)) {
					continue;
				}

				if (!class_exists($upgrade_data->class)) {
					$this->logger->error("Upgrade class {$upgrade_data->class} was not found");
					continue;
				}

				// Create a new ElggUpgrade to represent the upgrade in the database
				$upgrade = new ElggUpgrade();
				$upgrade->setId($upgrade_id);
				$upgrade->title = $upgrade_data->title;
				$upgrade->description = $upgrade_data->description;
				$upgrade->setClass($upgrade_data->class);
				$upgrade->save();

				$pending_upgrades = true;
			}

		}

		return true; //$pending_upgrades;
		*/
	}

	/**
	 * Check if there already is an ElggUpgrade for this upgrade
	 *
	 * @param string $upgrade
	 * @return boolean
	 */
	private function upgradeExists($upgrade_id) {
		$upgrade = $this->privateSettings->getEntities(array(
			'type' => 'object',
			'subtype' => 'elgg_upgrade',
			'private_setting_name' => 'id',
			'private_setting_value' => $upgrade_id,
		));

		return !empty($upgrade);
	}
}
