<?php

namespace Elgg\Likes;

/**
 * Likes permissions
 */
class Permissions {

	/**
	 * Only allow annotation owner (or someone who can edit the owner, like an admin) to delete like
	 *
	 * @param \Elgg\Hook $hook 'permissions_check', 'annotation'
	 *
	 * @return void|bool
	 */
	public static function allowLikedEntityOwner(\Elgg\Hook $hook) {
		$annotation = $hook->getParam('annotation');
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
	 * @param \Elgg\Hook $hook 'permissions_check:annotate', 'object'|'user'|'group'|'site'
	 *
	 * @return void|bool
	 */
	public static function allowLikeOnEntity(\Elgg\Hook $hook) {
		if ($hook->getParam('annotation_name') !== 'likes') {
			return;
		}
	
		$user = $hook->getUserParam();
		$entity = $hook->getEntityParam();
	
		if (!$user || !$entity instanceof \ElggEntity) {
			return false;
		}
	
		$type = $entity->type;
		$subtype = $entity->getSubtype();
	
		return (bool) elgg_trigger_plugin_hook('likes:is_likable', "$type:$subtype", [], false);
	}
}
