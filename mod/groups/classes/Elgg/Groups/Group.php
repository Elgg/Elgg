<?php

namespace Elgg\Groups;

/**
 * Handle group related hooks and events
 *
 * @since 4.0
 * @internal
 */
class Group {
	
	/**
	 * This function loads a set of default fields into the profile, then triggers
	 * a hook letting other plugins to edit add and delete fields.
	 *
	 * Note: This is a system:init event triggered function and is run at a super
	 * low priority to guarantee that it is called after all other plugins have
	 * initialized.
	 *
	 * @return void
	 */
	public static function setupProfileFields() {
	
		$profile_defaults = [
			'description' => 'longtext',
			'briefdescription' => 'text',
			'interests' => 'tags',
		];
	
		$profile_defaults = elgg_trigger_plugin_hook('profile:fields', 'group', null, $profile_defaults);
	
		elgg_set_config('group', $profile_defaults);
	
		// register any tag metadata names
		foreach ($profile_defaults as $name => $type) {
			if ($type == 'tags') {
				elgg_register_tag_metadata_name($name);
	
				// only shows up in search but why not just set this in en.php as doing it here
				// means you cannot override it in a plugin
				add_translation(get_current_language(), ["tag_names:$name" => elgg_echo("groups:$name")]);
			}
		}
	}
	
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
			$ac_name = elgg_echo('groups:group') . ": " . $object->getDisplayName();
			
			// delete the group if acl creation fails
			return (bool) create_access_collection($ac_name, $object->guid, 'group_acl');
		});
	}
	
	/**
	 * Listen to group ownership changes and update group icon ownership
	 * This will only move the source file, the actual icons are moved by
	 * _elgg_filestore_move_icons()
	 *
	 * This operation is performed in an event listener to ensure that icons
	 * are moved when ownership changes outside of the groups/edit action flow.
	 *
	 * @see _elgg_filestore_move_icons()
	 *
	 * @param \Elgg\Event $event "update:after", "group"
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
				'limit' => 0,
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
			$ac_name = elgg_echo('groups:group') . ": " . $group_name;
			$acl = _groups_get_group_acl($group);
			if ($acl) {
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
		remove_entity_relationship($group->guid, 'invited', $user->guid);
		remove_entity_relationship($user->guid, 'membership_request', $group->guid);
	
		if (elgg_extract('create_river_item', $params)) {
			elgg_create_river_item([
				'action_type' => 'join',
				'subject_guid' => $user->guid,
				'object_guid' => $group->guid,
			]);
		}
		
		// add a user to the group's access control
		$collection = _groups_get_group_acl($group);
		if (!empty($collection)) {
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
		remove_entity_relationship($group->guid, 'invited', $user->guid);
		remove_entity_relationship($user->guid, 'membership_request', $group->guid);
		
		// Removes a user from the group's access control
		$collection = _groups_get_group_acl($group);
		if (!empty($collection)) {
			$collection->removeMember($user->guid);
		}
	}
}
