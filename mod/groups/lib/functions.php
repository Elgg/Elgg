<?php
/**
 * Holds helper functions for groups plugin
 */

/**
 * Returns a menu item for leaving a group
 *
 * @param \ElggGroup $group Group to leave
 * @param \ElggUser  $user  User to check leave action for
 *
 * @return \ElggMenuItem|false
 */
function groups_get_group_leave_menu_item(\ElggGroup $group, $user = null) {
	
	if (!$user instanceof \ElggUser) {
		$user = elgg_get_logged_in_user_entity();
	}
	
	if (!$user instanceof \ElggUser) {
		return false;
	}
	
	if (!$group->isMember($user) || ($group->owner_guid === $user->guid)) {
		// a member can leave a group if he/she doesn't own it
		return false;
	}
	
	return \ElggMenuItem::factory([
		'name' => 'groups:leave',
		'icon' => 'sign-out-alt',
		'text' => elgg_echo('groups:leave'),
		'href' => elgg_generate_action_url('groups/leave', [
			'group_guid' => $group->guid,
			'user_guid' => $user->guid,
		]),
	]);
}

/**
 * Returns a menu item for joining a group
 *
 * @param \ElggGroup $group Group to leave
 * @param \ElggUser  $user  User to check leave action for
 *
 * @return \ElggMenuItem|false
 */
function groups_get_group_join_menu_item(\ElggGroup $group, $user = null) {
	
	if (!$user instanceof \ElggUser) {
		$user = elgg_get_logged_in_user_entity();
	}
	
	if (!$user instanceof \ElggUser) {
		return false;
	}
	
	if ($group->isMember($user)) {
		return false;
	}
	
	$menu_name = 'groups:joinrequest';
	if ($group->isPublicMembership() || $group->canEdit()) {
		// admins can always join
		// non-admins can join if membership is public
		$menu_name = 'groups:join';
	}
	
	return \ElggMenuItem::factory([
		'name' => $menu_name,
		'icon' => 'sign-in-alt',
		'text' => elgg_echo($menu_name),
		'href' => elgg_generate_action_url('groups/join', [
			'group_guid' => $group->guid,
			'user_guid' => $user->guid,
		]),
	]);
}

/**
 * Grabs groups by invitations
 * Have to override all access until there's a way override access to getter functions.
 *
 * @param int   $user_guid    The user's guid
 * @param bool  $return_guids Return guids rather than ElggGroup objects
 * @param array $options      Additional options
 *
 * @return mixed ElggGroups or guids depending on $return_guids, or count
 */
function groups_get_invited_groups($user_guid, $return_guids = false, $options = []) {

	$groups = elgg_call(ELGG_IGNORE_ACCESS, function() use ($user_guid, $options) {
		$defaults = [
			'relationship' => 'invited',
			'relationship_guid' => (int) $user_guid,
			'inverse_relationship' => true,
			'limit' => 0,
		];
	
		$options = array_merge($defaults, $options);
		return elgg_get_entities($options);
	});
	
	if ($return_guids) {
		$guids = [];
		foreach ($groups as $group) {
			$guids[] = $group->guid;
		}

		return $guids;
	}

	return $groups;
}

/**
 * Prepares variables for the group edit form view.
 *
 * @param mixed $group ElggGroup or null. If a group, uses values from the group.
 * @return array
 */
function groups_prepare_form_vars($group = null) {
	$values = [
		'name' => '',
		'membership' => ACCESS_PUBLIC,
		'vis' => ACCESS_PUBLIC,
		'guid' => null,
		'entity' => null,
		'owner_guid' => elgg_get_logged_in_user_guid(),
		'content_access_mode' => ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED,
		'content_default_access' => '',
	];

	// handle customizable profile fields
	$fields = elgg()->fields->get('group', 'group');
	foreach ($fields as $field) {
		$values[$field['name']] = '';
	}

	// get current group settings
	if ($group) {
		foreach (array_keys($values) as $field) {
			if (isset($group->$field)) {
				$values[$field] = $group->$field;
			}
		}

		if ($group->access_id != ACCESS_PUBLIC && $group->access_id != ACCESS_LOGGED_IN) {
			// group only access - this is done to handle access not created when group is created
			$values['vis'] = ACCESS_PRIVATE;
		} else {
			$values['vis'] = $group->access_id;
		}

		// The content_access_mode was introduced in 1.9. This method must be
		// used for backwards compatibility with groups created before 1.9.
		$values['content_access_mode'] = $group->getContentAccessMode();
		
		$values['entity'] = $group;
	}

	// handle tool options
	if ($group instanceof ElggGroup) {
		$tools = elgg()->group_tools->group($group);
	} else {
		$tools = elgg()->group_tools->all();
	}

	foreach ($tools as $tool) {
		if ($group instanceof ElggGroup) {
			$enabled = $group->isToolEnabled($tool->name);
		} else {
			$enabled = $tool->isEnabledByDefault();
		}

		$prop_name = $tool->mapMetadataName();
		$values[$prop_name] = $enabled ? 'yes' : 'no';
	}

	// get any sticky form settings
	if (elgg_is_sticky_form('groups')) {
		$sticky_values = elgg_get_sticky_values('groups');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('groups');

	return $values;
}
