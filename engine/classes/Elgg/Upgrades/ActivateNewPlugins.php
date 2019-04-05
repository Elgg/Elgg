<?php

namespace Elgg\Upgrades;

use Elgg\Upgrade\Result;
use Elgg\Upgrade\SystemUpgrade;

/**
 * Activate plugins added in Elgg 3.0
 */
class ActivateNewPlugins implements SystemUpgrade  {

	protected $plugins = [
		'activity',
		'friends',
		'friends_collections',
		'system_log',
	];
	
	/**
	 * Not all plugins which should be active have to be installed,
	 * for example in the MIT version of Elgg
	 *
	 * @return string[]
	 */
	protected function getPluginIDs() {
		$result = [];
		
		$plugins_path = elgg_get_plugins_path();
		foreach ($this->plugins as $plugin_id) {
			if (!is_dir($plugins_path . $plugin_id)) {
				// plugin is not installed
				// maybe using MIT version or otherwise modified version of Elgg
				continue;
			}
			
			// plugin exists
			$result[] = $plugin_id;
		}
		
		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getVersion() {
		return 2018041800;
	}

	/**
	 * {@inheritdoc}
	 */
	public function shouldBeSkipped() {
		return empty($this->getPluginIDs());
	}

	/**
	 * {@inheritdoc}
	 */
	public function needsIncrementOffset() {
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function countItems() {
		return count($this->getPluginIDs());
	}

	/**
	 * {@inheritdoc}
	 */
	public function run(Result $result, $offset) {
		_elgg_generate_plugin_entities();

		foreach ($this->getPluginIDs() as $id) {
			$plugin = elgg_get_plugin_from_id($id);

			if (!$plugin) {
				$result->addFailures(1);
				$result->addError(elgg_echo('PluginException:InvalidPlugin', [
					$id,
				]));
				continue;
			}

			if ($plugin->isActive()) {
				$result->addSuccesses(1);
				continue;
			}

			try {
				if ($plugin->activate()) {
					$result->addSuccesses(1);
				} else {
					$result->addError($plugin->getID() . ': ' . $plugin->getError());
					$result->addFailures(1);
				}
			} catch (\Exception $ex) {
				$result->addError($ex->getMessage());
				$result->addFailures(1);
			}
		}
	}
}
