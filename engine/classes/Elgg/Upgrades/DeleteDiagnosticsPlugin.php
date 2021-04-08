<?php

namespace Elgg\Upgrades;

use Elgg\Upgrade\SystemUpgrade;
use Elgg\Upgrade\Result;

/**
 * Remove the diagnostics plugin entity
 */
class DeleteDiagnosticsPlugin implements SystemUpgrade {

	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): int {
		return 2020102301;
	}

	/**
	 * {@inheritDoc}
	 */
	public function needsIncrementOffset(): bool {
		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function shouldBeSkipped(): bool {
		return empty($this->countItems());
	}

	/**
	 * {@inheritDoc}
	 */
	public function countItems(): int {
		$plugin = elgg_get_plugin_from_id('diagnostics');
		if ($plugin instanceof \ElggPlugin) {
			return 1;
		}
		
		return 0;
	}

	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset): Result {
		$plugin = elgg_get_plugin_from_id('diagnostics');
		if (!$plugin instanceof \ElggPlugin) {
			$result->addSuccesses(1);
			return $result;
		}
		
		_elgg_services()->logger->disable();
		
		if ($plugin->delete()) {
			$result->addSuccesses(1);
		} else {
			$result->addFailures(1);
		}
		
		_elgg_services()->logger->enable();
		
		return $result;
	}
}
