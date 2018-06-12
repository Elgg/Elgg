<?php

namespace Elgg\Pages\Upgrades;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;
use ElggObject;

/**
 * Migrate 'object', 'page_top' to 'object', 'page'
 * also set metadata 'parent_guid' to 0
 *
 * @since 3.0
 */
class MigratePageTop implements AsynchronousUpgrade {
	
	/**
	 * {@inheritDoc}
	 */
	public function getVersion() {
		return 2017110700;
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
		return elgg_get_entities([
			'type' => 'object',
			'subtype' => 'page_top',
			'count' => true,
		]);
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
		
		/* @var \ElggBatch $page_tops */
		$page_tops = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'page_top',
			'offset' => $offset,
			'limit' => 25,
			'batch' => true,
			'batch_inc_offset' => $this->needsIncrementOffset(),
		]);
		
		/* @var $page_top \ElggObject */
		foreach ($page_tops as $page_top) {
			if ($this->migrate($page_top)) {
				$result->addSuccesses();
			} else {
				$result->addError("Error migrating page_top: {$page_top->guid}");
				$result->addFailures();
			}
		}
	}
	
	/**
	 * Migrate one page_top to a page
	 *
	 * @param ElggObject $page_top the top page to migrate
	 *
	 * @return bool
	 */
	protected  function migrate(ElggObject $page_top) {
		
		$dbprefix = elgg_get_config('dbprefix');
		$query = "UPDATE {$dbprefix}entities
			SET subtype = 'page'
			WHERE guid = {$page_top->guid}
		";
		
		if (!elgg()->db->updateData($query)) {
			return false;
		}
		
		$page_top->parent_guid = 0;
		
		return true;
	}
}
