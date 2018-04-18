<?php
namespace Elgg\Likes\Upgrades;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

class PublicLikesAnnotations implements AsynchronousUpgrade {
	
	/**
	 * {@inheritdoc}
	 */
	public function getVersion() {
		return 2017120700;
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
		return empty($this->countItems());
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function countItems() {
		$dbprefix = elgg_get_config('dbprefix');
		$public = ACCESS_PUBLIC;
		
		$query = "SELECT COUNT(*) as total
			FROM {$dbprefix}annotations
			WHERE name = 'likes'
			AND access_id != {$public}
		";
		
		$row = elgg()->db->getDataRow($query);
		if (empty($row)) {
			return 0;
		}
		
		return (int) $row->total;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset) {
		$dbprefix = elgg_get_config('dbprefix');
		$public = ACCESS_PUBLIC;
		
		$query = "UPDATE {$dbprefix}annotations
			SET access_id = {$public}
			WHERE name = 'likes'
			AND access_id != {$public}
		";
		
		$count = elgg()->db->updateData($query, true, []);
		
		$result->addSuccesses($count);
	}
}