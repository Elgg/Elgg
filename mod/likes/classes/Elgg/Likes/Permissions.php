<?php

namespace Elgg\Likes;

/**
 * Likes permissions
 */
class Permissions {

	/**
	 * Only allow annotation owner (or someone who can edit the owner, like an admin) to delete like
	 *
	 * @param \Elgg\Event $event 'permissions_check', 'annotation'
	 *
	 * @return void|bool
	 */
	public static function allowLikedEntityOwner(\Elgg\Event $event) {
		$annotation = $event->getParam('annotation');
		if (!$annotation || $annotation->name !== 'likes') {
			return;
		}
		
		$owner = $annotation->getOwnerEntity();
		if (!$owner) {
			return;
		}
		
		return $owner->canEdit();
	}
	
	/**
	 * Sets the default for whether to allow liking/viewing likes on an entity
	 *
	 * @param \Elgg\Event $event 'permissions_check:annotate', 'object'|'user'|'group'|'site'
	 *
	 * @return void|bool
	 */
	public static function allowLikeOnEntity(\Elgg\Event $event) {
		if ($event->getParam('annotation_name') !== 'likes') {
			return;
		}
	
		$user = $event->getUserParam();
		$entity = $event->getEntityParam();
	
		if (!$user || !$entity instanceof \ElggEntity) {
			return false;
		}
		
		return $entity->hasCapability('likable');
	}
}
