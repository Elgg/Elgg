<?php

namespace Elgg\Pages\Upgrades;

use ElggRiverItem;
use \Elgg\Upgrade\Batch;
use \Elgg\Upgrade\Result;

/**
 * Migrate river entries for 'object', 'page_top' to 'object', 'page'
 *
 * @since 3.0
 */
class MigratePageTopRiver implements Batch {
	
	/**
	 * {@inheritDoc}
	 */
	public function getVersion() {
		return 2017110701;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function shouldBeSkipped() {
		return empty($this->countItems());
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function countItems() {
		
		$dbprefix = elgg_get_config('dbprefix');
		
		$query = "SELECT COUNT(*) as total
			FROM {$dbprefix}river
			WHERE type = 'object'
			AND subtype = 'page_top'
		";
		
		$row = get_data_row($query);
		if (empty($row)) {
			return 0;
		}
		
		return (int) $row->total;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function needsIncrementOffset() {
		return false;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset) {
		
		$dbprefix = elgg_get_config('dbprefix');
		
		$query = "UPDATE {$dbprefix}river
			SET subtype = 'page'
			WHERE type = 'object'
			AND subtype = 'page_top'
		";
		
		$count = update_data($query, [], true);
		
		$result->addSuccesses($count);
		
		return $result;
	}
}
