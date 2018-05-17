<?php

namespace Elgg\Upgrades;

use Elgg\Upgrade\Result;
use Elgg\Upgrade\SystemUpgrade;

/**
 * Remove entities associated with plugins removed in 3.0
 */
class DeleteOldPlugins implements SystemUpgrade  {

	protected $plugins = [
		'htmlawed',
		'aalborg_theme',
		'legacy_urls',
		'logbrowser',
		'logrotate',
		'twitter_api',
	];

	/**
	 * {@inheritdoc}
	 */
	public function getVersion() {
		return 2018041801;
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
				$result->addSuccesses(1);
				continue;
			}

			_elgg_services()->logger->disable();
			if (!$plugin->isValid()) {
				if ($plugin->delete()) {
					$result->addSuccesses(1);
				} else {
					$result->addFailures(1);
					$result->addError($plugin->getError());
				}
			}
			_elgg_services()->logger->enable();
		}
	}

}