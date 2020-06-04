<?php

namespace Elgg\Likes;

use Elgg\Database\Delete as DbDelete;

/**
 * Listen to 'delete' events to cleanup likes
 *
 * @internal
 * @since 3.3
 */
class Delete {
	
	/**
	 * Cleanup likes annotations on delete of an entity
	 *
	 * @param \Elgg\Event $event 'delete', 'group'|'object'|'site'|'user'
	 *
	 *  @return void
	 */
	public static function deleteLikes(\Elgg\Event $event) {
		
		$entity = $event->getObject();
		if (!$entity instanceof \ElggEntity) {
			return;
		}
		
		// Let's do a bulk delete of all likes annotations, instead of for each individually
		// this will save performance
		$delete = DbDelete::fromTable('annotations');
		$delete->where($delete->compare('entity_guid', '=', $entity->guid, ELGG_VALUE_GUID))
			->andWhere($delete->compare('name', '=', 'likes', ELGG_VALUE_STRING));
		
		elgg()->db->deleteData($delete);
	}
}
