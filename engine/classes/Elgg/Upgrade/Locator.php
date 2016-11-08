<?php

namespace Elgg\Upgrade;

use Elgg\Database\PrivateSettingsTable;
use Elgg\Database\Plugins;
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
	 * @param Plugins              $plugins          Plugins
	 * @param Logger               $logger           Logger
	 * @param PrivateSettingsTable $private_settings PrivateSettingsTable
	 */
	public function __construct(Plugins $plugins, Logger $logger, PrivateSettingsTable $private_settings) {
		$this->plugins = $plugins;
		$this->logger = $logger;
		$this->privateSettings = $private_settings;
	}

	/**
	 * Looks for upgrades and saves them as ElggUpgrade entities
	 *
	 * @param string[] $core_upgrades Class names of core upgrades
	 *
	 * @return boolean $pending_upgrades Are there pending upgrades
	 */
	public function run(array $core_upgrades) {
		$pending_upgrades = false;

		// Check for core upgrades
		foreach ($core_upgrades as $class) {
			$upgrade = $this->getUpgrade($class, 'core');

			if ($upgrade) {
				$pending_upgrades = true;
			}
		}

		$plugins = $this->plugins->find('active');

		// Check for plugin upgrades
		foreach ($plugins as $plugin) {
			$batches = $plugin->getStaticConfig('upgrades');

			if (empty($batches)) {
				continue;
			}

			$plugin_id = $plugin->getID();

			foreach ($batches as $class) {
				if ($this->getUpgrade($class, $plugin_id)) {
					$pending_upgrades = true;
				}
			}
		}

		return $pending_upgrades;
	}

	/**
	 * Gets intance of an ElggUpgrade based on the given class and id
	 *
	 * @param string $class Class implementing Elgg\Upgrade\Batch
	 * @param string $id    Either plugin_id or "core"
	 * @return ElggUpgrade|null
	 */
	public function getUpgrade($class, $id) {
		$batch = $this->getBatch($class);

		if (!$batch) {
			return;
		}

		$version = $batch->getVersion();
		$upgrade_id = "{$id}:{$version}";

		// Database holds the information of which upgrades have been processed
		if ($this->upgradeExists($upgrade_id)) {
			$this->logger->info("Upgrade $id has already been processed");
			return;
		}

		// Create a new ElggUpgrade to represent the upgrade in the database
		$object = new ElggUpgrade();
		$object->setId($upgrade_id);
		$object->setClass($class);
		$object->title = "{$id}:upgrade:{$version}:title";
		$object->description = "{$id}:upgrade:{$version}:description";
		$object->offset = 0;

		try {
			$object->save();
			return $object;
		} catch (\UnexpectedValueException $ex) {
			$this->logger->error($ex->getMessage());
		}
	}

	/**
	 * Validates class and returns an instance of batch
	 *
	 * @param string $class The fully qualified class name
	 * @return Batch|false if invalid upgrade
	 */
	public function getBatch($class) {
		if (!class_exists($class)) {
			$this->logger->error("Upgrade class $class was not found");
			return false;
		}

		$batch = new $class;
		if (!$batch instanceof Batch) {
			$this->logger->error("Upgrade class $class should implement " . Batch::class);
			return false;
		}

		// check version before shouldBeSkipped() so authors can get immediate feedback on an
		// invalid batch.
		$version = $batch->getVersion();

		// Version must be in format yyyymmddnn
		if (preg_match("/^[0-9]{10}$/", $version) == 0) {
			$this->logger->error("Upgrade $class returned an invalid version: $version");
			return false;
		}

		if ($batch->shouldBeSkipped()) {
			return false;
		}

		return $batch;
	}

	/**
	 * Check if there already is an ElggUpgrade for this upgrade
	 *
	 * @param string $upgrade_id Id in format <plugin_id>:<yyymmddnn>
	 * @return boolean
	 */
	public function upgradeExists($upgrade_id) {
		$upgrade = $this->privateSettings->getEntities([
			'type' => 'object',
			'subtype' => 'elgg_upgrade',
			'private_setting_name_value_pairs' => [
				[
					'name' => 'id',
					'value' => (string) $upgrade_id,
				],
			],
		]);

		return !empty($upgrade);
	}
}
