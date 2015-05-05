<?php

namespace Elgg\Upgrade;

use Elgg\Database\PrivateSettingsTable;
use Elgg\Database\Plugins;
use Elgg\Upgrade\Batch;
use Elgg\Logger;
use ElggUpgrade;

/**
 * Locates and registers both core and plugin upgrades
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @since 3.0.0
 *
 * @access private
 */
class Locator {

	/**
	 * @var Plugins $plugins
	 */
	private $plugins;

	/**
	 * @var Logger $logger
	 */
	private $logger;

	/**
	 * @var PrivateSettingsTable $privateSettings
	 */
	private $privateSettings;

	/**
	 * Constructor
	 *
	 * @param Plugins              $plugins         Plugins
	 * @param Logger               $logger          Logger
	 * @param PrivateSettingsTable $privateSettings PrivateSettingsTable
	 */
	public function __construct(
			Plugins $plugins,
			Logger $logger,
			PrivateSettingsTable $privateSettings) {
		$this->plugins = $plugins;
		$this->logger = $logger;
		$this->privateSettings = $privateSettings;
	}

	/**
	 * Looks for upgrades and saves them as ElggUpgrade entities
	 *
	 * @return boolean $pending_upgrades Are there pending upgrades
	 */
	public function run() {
		$pending_upgrades = false;

		$plugins = $this->plugins->find('active');

		foreach ($plugins as $plugin) {
			$upgrades = $plugin->getStaticConfig('upgrades');

			if (empty($upgrades)) {
				// No upgrades available for this plugin
				continue;
			}

			$plugin_id = $plugin->getID();

			foreach ($upgrades as $class) {
				if (!$this->isValidUpgrade($class)) {
					continue;
				}

				$upgrade = new $class;
				$version = $upgrade::VERSION;
				$upgrade_id = "{$plugin_id}:{$version}";

				// Database holds the information of which upgrades have been processed
				if ($this->upgradeExists($upgrade_id)) {
					$this->logger->info("Upgrade $upgrade_id has already been processed");
					continue;
				}

				// Create a new ElggUpgrade to represent the upgrade in the database
				$object = new ElggUpgrade();
				$object->setId($upgrade_id);
				$object->setClass($class);
				$object->title = "{$plugin_id}:upgrade:{$version}:title";
				$object->description = "{$plugin_id}:upgrade:{$version}:description";
				$object->total = $upgrade->countItems();
				$object->offset = 0;
				$object->save();

				$pending_upgrades = true;
			}
		}

		return $pending_upgrades;
	}

	/**
	 * Checks whether upgrade is a valid instance of BatchUpgrade interface
	 *
	 * @param string $class The fully qualified class name
	 * @return boolean True if valid upgrade
	 */
	private function isValidUpgrade($class) {
		if (!class_exists($class)) {
			$this->logger->error("Upgrade class $class was not found");
			return false;
		}

		$upgrade = new $class;
		if (!$upgrade instanceof Batch) {
			$this->logger->error("Upgrade class $class should implement Elgg\Upgrade\Batch");
			return false;
		}

		$version = $upgrade::VERSION;

		// Version must be in format yyyymmddnn
		if (preg_match("/^[0-9]{10}$/", $version) == 0) {
			$this->logger->error("Upgrade $class defines an invalid upgrade version: $version");
			return false;
		}

		return true;
	}

	/**
	 * Check if there already is an ElggUpgrade for this upgrade
	 *
	 * @param string $upgrade_id Id in format <plugin_id>:<yyymmddnn>
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
