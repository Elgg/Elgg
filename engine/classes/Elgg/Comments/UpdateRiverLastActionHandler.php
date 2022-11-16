<?php

namespace Elgg\Comments;

/**
 * Updates river item last action
 *
 * @since 5.0
 */
class UpdateRiverLastActionHandler {
	
	/**
	 * Updates the last action of a related river item
	 *
	 * @param \Elgg\Event $event 'create:after', 'river'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event) {
		$item = $event->getObject();
		if (!$item instanceof \ElggRiverItem) {
			return;
		}
		
		$object = $item->getObjectEntity();
		if (!$object instanceof \ElggComment || $item->action_type !== 'comment') {
			return;
		}
		
		// find create river and update the river item
		$river = elgg_get_river([
			'object_guid' => $item->target_guid,
			'action' => 'create',
			'limit' => 1,
		]);
		if (empty($river)) {
			return;
		}
		
		$old_item = $river[0];
		$old_item->updateLastAction($item->getTimePosted());
	}
}
