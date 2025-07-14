<?php

namespace Elgg\Groups;

/**
 * Handle group related events
 *
 * @since 4.0
 * @internal
 */
class Group {

	/**
	 * Groups created so create an access list for it
	 *
	 * @param \Elgg\Event $event 'create', 'group'
	 *
	 * @return bool
	 */
	public static function createAccessCollection(\Elgg\Event $event) {
		// ensure that user has sufficient permissions to update group metadata
		// prior to joining the group
		$object = $event->getObject();
		return elgg_call(ELGG_IGNORE_ACCESS, function() use ($object) {
			$ac_name = elgg_echo('groups:group') . ': ' . $object->getDisplayName();
			
			// delete the group if acl creation fails
			return (bool) elgg_create_access_collection($ac_name, $object->guid, 'group_acl');
		});
	}
	
	/**
	 * Listen to group ownership changes and update group icon ownership
	 * This will only move the source file, the actual icons are moved by
	 * \Elgg\Icons\MoveIconsOnOwnerChangeHandler::class
	 *
	 * This operation is performed in an event listener to ensure that icons
	 * are moved when ownership changes outside of the groups/edit action flow.
	 *
	 * @param \Elgg\Event $event 'update:after', 'group'
	 *
	 * @return void
	 */
	public static function updateGroup(\Elgg\Event $event) {
	
		/* @var $group \ElggGroup */
		$group = $event->getObject();
		$original_attributes = $group->getOriginalAttributes();
	
		if (!empty($original_attributes['owner_guid'])) {
			$previous_owner_guid = $original_attributes['owner_guid'];
	
			// Update owned metadata
			$metadata = elgg_get_metadata([
				'guid' => $group->guid,
				'metadata_owner_guids' => $previous_owner_guid,
				'limit' => false,
			]);
	
			if ($metadata) {
				foreach ($metadata as $md) {
					$md->owner_guid = $group->owner_guid;
					$md->save();
				}
			}
		}
	
		if (!empty($original_attributes['name'])) {
			// update access collection name if group name changes
			$group_name = html_entity_decode($group->getDisplayName(), ENT_QUOTES, 'UTF-8');
			$ac_name = elgg_echo('groups:group') . ': ' . $group_name;
			$acl = $group->getOwnedAccessCollection('group_acl');
			if ($acl instanceof \ElggAccessCollection) {
				$acl->name = $ac_name;
				$acl->save();
			}
		}
	}
	
	/**
	 * Perform actions when a user joins a group
	 *
	 * @param \Elgg\Event $event 'join', 'group'
	 *
	 * @return void
	 */
	public static function joinGroup(\Elgg\Event $event) {
		$params = $event->getObject();
		$group = elgg_extract('group', $params);
		$user = elgg_extract('user', $params);
		if (!$group instanceof \ElggGroup || !$user instanceof \ElggUser) {
			return;
		}
		
		// Remove any invite or join request flags
		$group->removeRelationship($user->guid, 'invited');
		$user->removeRelationship($group->guid, 'membership_request');
	
		if (elgg_extract('create_river_item', $params)) {
			elgg_create_river_item([
				'action_type' => 'join',
				'subject_guid' => $user->guid,
				'object_guid' => $group->guid,
			]);
		}
		
		$notify_user_action = (string) elgg_extract('notify_user_action', $params);
		if (!empty($notify_user_action)) {
			$rel = $user->getRelationship($group->guid, 'member');
			if ($rel instanceof \ElggRelationship) {
				elgg_enqueue_notification_event($notify_user_action, $rel);
			}
		}
		
		// add a user to the group's access control
		$collection = $group->getOwnedAccessCollection('group_acl');
		if ($collection instanceof \ElggAccessCollection) {
			$collection->addMember($user->guid);
		}
	}
	
	/**
	 * Perform actions when a user leaves a group
	 *
	 * @param \Elgg\Event $event 'leave', 'group'
	 *
	 * @return void
	 */
	public static function leaveGroup(\Elgg\Event $event) {
		$params = $event->getObject();
		$group = elgg_extract('group', $params);
		$user = elgg_extract('user', $params);
		if (!$group instanceof \ElggGroup || !$user instanceof \ElggUser) {
			return;
		}
		
		// Remove any invite or join request flags (for some edge cases)
		$group->removeRelationship($user->guid, 'invited');
		$user->removeRelationship($group->guid, 'membership_request');
		
		// Removes a user from the group's access control
		$collection = $group->getOwnedAccessCollection('group_acl');
		if ($collection instanceof \ElggAccessCollection) {
			$collection->removeMember($user->guid);
		}
	}
}
