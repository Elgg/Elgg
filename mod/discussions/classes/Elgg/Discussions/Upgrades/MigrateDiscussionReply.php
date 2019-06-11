<?php

namespace Elgg\Discussions\Upgrades;

use Elgg\Database\Update;
use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * Migrate 'object', 'discussion_reply' to 'object', 'comment'
 *
 * @since 3.0
 */
class MigrateDiscussionReply implements AsynchronousUpgrade {
	
	/**
	 * {@inheritDoc}
	 */
	public function getVersion() {
		return 2017112800;
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
		return elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function () {
			return elgg_count_entities([
				'type' => 'object',
				'subtype' => 'discussion_reply',
			]);
		});
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
		
		$qb = Update::table('entities', 'e')
			->set('e.subtype', '"comment"')
			->where('e.subtype = "discussion_reply"');
		
		$count = $qb->execute();
		
		$result->addSuccesses($count);
	}
}
