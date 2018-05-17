<?php

namespace Elgg\Discussions\Upgrades;

use Elgg\Database\Update;
use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * Migrate river items related to discussion replies
 *
 * @since 3.0
 */
class MigrateDiscussionReplyRiver implements AsynchronousUpgrade {
	
	/**
	 * {@inheritDoc}
	 */
	public function getVersion() {
		return 2017112801;
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
		
		$hidden = access_show_hidden_entities(true);
		
		$count = elgg_get_river([
			'type' => 'object',
			'subtype' => 'discussion_reply',
			'count' => true,
		]);
		
		access_show_hidden_entities($hidden);
		
		return $count;
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
		
		$qb = Update::table('river', 'r')
			->set('r.subtype', '"comment"')
			->set('r.action_type', '"comment"')
			->set('r.view', '"river/object/comment/create"')
			->andWhere('r.subtype = "discussion_reply"')
			->andWhere('r.action_type = "reply"')
			->andWhere('r.view = "river/object/discussion_reply/create"');
		
		$count = $qb->execute();
		
		$result->addSuccesses($count);
	}
}
