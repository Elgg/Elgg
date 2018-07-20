<?php

namespace Elgg\Upgrades;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * Migrate the database cron log values to the new (file) location
 */
class MigrateCronLog implements AsynchronousUpgrade {

	/**
	 * {@inheritdoc}
	 */
	public function getVersion() {
		return 2018061401;
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
	public function shouldBeSkipped() {
		return (bool) !$this->countItems();
	}

	/**
	 * {@inheritdoc}
	 */
	public function countItems() {
		return count($this->getLogs());
	}
	
	/**
	 * Get all the database cron logs
	 *
	 * @return array
	 */
	protected function getLogs() {
		
		$intervals = elgg_get_config('elgg_cron_periods');
		if (empty($intervals)) {
			return [];
		}
		
		$suffixes = [
			'ts',
			'msg',
		];
		
		$site = elgg_get_site_entity();
		$result = [];
		foreach ($intervals as $interval) {
			foreach ($suffixes as $suffix) {
				$result[$interval][$suffix] = $site->getPrivateSetting("cron_latest:{$interval}:{$suffix}");
			}
		}
		
		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function run(Result $result, $offset) {

		$site = elgg_get_site_entity();
		
		$fh = new \ElggFile();
		$fh->owner_guid = $site->guid;
		
		$logs = $this->getLogs();
		foreach ($logs as $interval => $values) {
			if (empty($values)) {
				$result->addSuccesses();
				continue;
			}
			
			foreach ($values as $suffix => $value) {
				
				$site->removePrivateSetting("cron_latest:{$interval}:{$suffix}");
				
				if (empty($value)) {
					continue;
				}
				
				if ($suffix === 'ts') {
					$fh->setFilename("{$interval}-completion.log");
				} else {
					$fh->setFilename("{$interval}-output.log");
				}
				
				$fh->open('write');
				$fh->write($value);
				$fh->close();
			}
			
			$result->addSuccesses();
		}
	}
}
