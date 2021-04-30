<?php

namespace Elgg\Upgrade;

use Elgg\Database\Plugins;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Includer;
use Elgg\Project\Paths;

/**
 * Locates and registers both core and plugin upgrades
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @since  3.0.0
 * @internal
 */
class Locator {

	/**
	 * @var Plugins $plugins
	 */
	private $plugins;

	/**
	 * Constructor
	 *
	 * @param Plugins $plugins Plugins
	 */
	public function __construct(Plugins $plugins) {
		$this->plugins = $plugins;
	}

	/**
	 * Looks for upgrades and saves them as ElggUpgrade entities
	 *
	 * @return \ElggUpgrade[]
	 */
	public function locate() {
		$pending_upgrades = [];

		// Check for core upgrades
		$core_upgrades = Includer::includeFile(Paths::elgg() . 'engine/upgrades.php');

		foreach ($core_upgrades as $class) {
			$upgrade = $this->getUpgrade($class, 'core');
			if ($upgrade) {
				$pending_upgrades[] = $upgrade;
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
				$upgrade = $this->getUpgrade($class, $plugin_id);
				if ($upgrade) {
					$pending_upgrades[] = $upgrade;
				}
			}
		}

		return $pending_upgrades;
	}

	/**
	 * Gets intance of an ElggUpgrade based on the given class and id
	 *
	 * @param string $class        Class implementing Elgg\Upgrade\Batch
	 * @param string $component_id Either plugin_id or "core"
	 *
	 * @return \ElggUpgrade
	 */
	public function getUpgrade($class, $component_id) {

		$batch = $this->getBatch($class);

		$version = $batch->getVersion();
		$upgrade_id = "{$component_id}:{$version}";

		$upgrade = $this->upgradeExists($upgrade_id);

		if (!$upgrade) {
			$upgrade = elgg_call(ELGG_IGNORE_ACCESS, function () use ($upgrade_id, $class, $component_id, $version) {
				$site = elgg_get_site_entity();

				// Create a new ElggUpgrade to represent the upgrade in the database
				$upgrade = new \ElggUpgrade();
				$upgrade->owner_guid = $site->guid;
				$upgrade->container_guid = $site->guid;

				$upgrade->setId($upgrade_id);
				$upgrade->setClass($class);
				$upgrade->title = "{$component_id}:upgrade:{$version}:title";
				$upgrade->description = "{$component_id}:upgrade:{$version}:description";
				$upgrade->offset = 0;
				$upgrade->save();

				return $upgrade;
			});
		}

		return $upgrade;
	}

	/**
	 * Validates class and returns an instance of batch
	 *
	 * @param string $class The fully qualified class name
	 *
	 * @return Batch
	 * @throws InvalidArgumentException
	 */
	public function getBatch($class) {
		if (!class_exists($class)) {
			throw new InvalidArgumentException("Upgrade class $class was not found");
		}

		if (!is_subclass_of($class, Batch::class)) {
			throw new InvalidArgumentException("Upgrade class $class should implement " . Batch::class);
		}

		return new $class;
	}

	/**
	 * Check if there already is an ElggUpgrade for this upgrade
	 *
	 * @param string $upgrade_id Id in format <plugin_id>:<yyymmddnn>
	 *
	 * @return \ElggUpgrade|false
	 */
	public function upgradeExists($upgrade_id) {
		return elgg_call(ELGG_IGNORE_ACCESS, function () use ($upgrade_id) {
			$upgrades = \Elgg\Database\Entities::find([
				'type' => 'object',
				'subtype' => 'elgg_upgrade',
				'private_setting_name_value_pairs' => [
					[
						'name' => 'id',
						'value' => (string) $upgrade_id,
					],
				],
			]);

			return $upgrades ? $upgrades[0] : false;
		});
	}

	/**
	 * Check if there already is an ElggUpgrade for this upgrade class
	 *
	 * @param string $class_name name of the class used for the upgrade
	 *
	 * @return \ElggUpgrade|false
	 *
	 * @since 3.3
	 */
	public function getUpgradeByClass(string $class_name) {
		return elgg_call(ELGG_IGNORE_ACCESS, function () use ($class_name) {
			$upgrades = \Elgg\Database\Entities::find([
				'type' => 'object',
				'subtype' => 'elgg_upgrade',
				'private_setting_name_value_pairs' => [
					[
						'name' => 'class',
						'value' => $class_name,
					],
				],
			]);

			return $upgrades ? $upgrades[0] : false;
		});
	}
}
