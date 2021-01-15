<?php

namespace Elgg\Icons;

/**
 * Moves icons on ownership changes
 *
 * @since 4.0
 */
class MoveIconsOnOwnerChangeHandler {
	
	/**
	 * Listen to entity ownership changes and update icon ownership by moving
	 * icons to their new owner's directory on filestore.
	 *
	 * This will only transfer icons that have a custom location on filestore
	 * and are owned by the entity's owner (instead of the entity itself).
	 * Even though core icon service does not store icons in the entity's owner
	 * directory, there are plugins that do (e.g. file plugin) - this handler
	 * helps such plugins avoid ownership mismatch.
	 *
	 * @param \Elgg\Event $event 'update:after', 'object'|'group'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event) {
		$entity = $event->getObject();
		if (!$entity instanceof \ElggEntity) {
			return;
		}
		
		$original_attributes = $entity->getOriginalAttributes();
		if (empty($original_attributes['owner_guid'])) {
			return;
		}
	
		$previous_owner_guid = $original_attributes['owner_guid'];
		$new_owner_guid = $entity->owner_guid;
	
		$sizes = array_keys(elgg_get_icon_sizes($entity->getType(), $entity->getSubtype()));
		foreach ($sizes as $size) {
			// using the Icon Service because we don't want to auto generate the 'new' icon
			$new_icon = _elgg_services()->iconService->getIcon($entity, $size, 'icon', false);
			if ($new_icon->owner_guid == $entity->guid) {
				// we do not need to update icons that are owned by the entity itself
				continue;
			}
	
			if ($new_icon->owner_guid != $new_owner_guid) {
				// a plugin implements some custom logic
				continue;
			}
	
			$old_icon = new \ElggIcon();
			$old_icon->owner_guid = $previous_owner_guid;
			$old_icon->setFilename($new_icon->getFilename());
			if (!$old_icon->exists()) {
				// there is no icon to move
				continue;
			}
	
			if ($new_icon->exists()) {
				// there is already a new icon
				// just removing the old one
				$old_icon->delete();
				elgg_log("Entity $entity->guid has been transferred to a new owner but an icon was "
					. "left behind under {$old_icon->getFilenameOnFilestore()}. "
					. "Old icon has been deleted", 'NOTICE');
				continue;
			}
	
			$old_icon->transfer($new_icon->owner_guid, $new_icon->getFilename());
			elgg_log("Entity $entity->guid has been transferred to a new owner. "
				. "Icon was moved from {$old_icon->getFilenameOnFilestore()} to {$new_icon->getFilenameOnFilestore()}.", 'NOTICE');
		}
	}
}
