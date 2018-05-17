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
	 * {@inheritdoc}
	 */
	public function getVersion() {
		return 2018041800;
	}

	/**
	 * {@inheritdoc}
	 */
	public function shouldBeSkipped() {
		return false;
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
		return count($this->plugins);
	}

	/**
	 * {@inheritdoc}
	 */
	public function run(Result $result, $offset) {
		foreach ($this->plugins as $id) {
			$plugin = elgg_get_plugin_from_id($id);

			if (!$plugin) {
				$result->addFailures(1);
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
					$result->addError($plugin->getError());
					$result->addFailures(1);
				}
			} catch (\Exception $ex) {
				$result->addError($ex->getMessage());
				$result->addFailures(1);
			}
		}
	}

}