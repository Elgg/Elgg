<?php

namespace Elgg\River;

/**
 * Updates river item last action
 *
 * @since 4.0
 */
class UpdateLastActionHandler {
	
	/**
	 * Updates the last action of the object of an river item
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
		if ($object instanceof \ElggEntity) {
			$object->updateLastAction($item->getTimePosted());
		}
		
		$target = $item->getTargetEntity();
		if ($target instanceof \ElggEntity) {
			$target->updateLastAction($item->getTimePosted());
		}
	}
}
