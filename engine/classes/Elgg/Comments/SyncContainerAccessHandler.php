<?php

namespace Elgg\Comments;

/**
 * Keeps comments access in sync with container access
 *
 * @since 4.0
 */
class SyncContainerAccessHandler {
	
	/**
	 * Update comment access to match that of the container
	 *
	 * @param \Elgg\Event $event 'update:after', 'all'
	 *
	 * @return true
	 */
	public function __invoke(\Elgg\Event $event) {
		$entity = $event->getObject();
		if (!$entity instanceof \ElggEntity) {
			return true;
		}
		
		// need to override access in case comments ended up with ACCESS_PRIVATE
		// and to ensure write permissions
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity) {
			$comments = elgg_get_entities([
				'type' => 'object',
				'subtype' => 'comment',
				'container_guid' => $entity->guid,
				'wheres' => [
					function(\Elgg\Database\QueryBuilder $qb, $main_alias) use ($entity) {
						return $qb->compare("{$main_alias}.access_id", '!=', $entity->access_id, ELGG_VALUE_INTEGER);
					},
				],
				'limit' => false,
				'batch' => true,
				'batch_inc_offset' => false,
			]);
			
			foreach ($comments as $comment) {
				// Update comment access_id
				$comment->access_id = $entity->access_id;
				$comment->save();
			}
		});
			
		return true;
	}
}
