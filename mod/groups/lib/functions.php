<?php
/**
 * Holds helper functions for groups plugin
 */

/**
 * Returns a menu item for leaving a group
 *
 * @param \ElggGroup     $group Group to leave
 * @param null|\ElggUser $user  User to check leave action for
 *
 * @return \ElggMenuItem|false
 */
function groups_get_group_leave_menu_item(\ElggGroup $group, ?\ElggUser $user = null) {
	
	if (!$user instanceof \ElggUser) {
		$user = elgg_get_logged_in_user_entity();
	}
	
	if (!$user instanceof \ElggUser) {
		return false;
	}
	
	if (!$group->isMember($user) || ($group->owner_guid === $user->guid) || $group->isDeleted()) {
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
 * @param \ElggGroup     $group Group to leave
 * @param null|\ElggUser $user  User to check leave action for
 *
 * @return \ElggMenuItem|false
 */
function groups_get_group_join_menu_item(\ElggGroup $group, ?\ElggUser $user = null) {
	
	if (!$user instanceof \ElggUser) {
		$user = elgg_get_logged_in_user_entity();
	}
	
	if (!$user instanceof \ElggUser) {
		return false;
	}
	
	if ($group->isMember($user) || $group->isDeleted()) {
		return false;
	}
	
	if ($user->hasRelationship($group->guid, 'membership_request')) {
		return \ElggMenuItem::factory([
			'name' => 'groups:killrequest',
			'icon' => 'sign-in-alt',
			'text' => elgg_echo('groups:joinrequest:revoke'),
			'href' => elgg_generate_action_url('groups/killrequest', [
				'user_guid' => $user->guid,
				'group_guid' => $group->guid,
			]),
			'confirm' => true,
		]);
	}
	
	$menu_name = 'groups:joinrequest';
	if ($group->isPublicMembership() || $group->canEdit() || $group->getRelationship($user->guid, 'invited')) {
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
function groups_get_invited_groups(int $user_guid, bool $return_guids = false, array $options = []) {

	$groups = elgg_call(ELGG_IGNORE_ACCESS, function() use ($user_guid, $options) {
		$defaults = [
			'type' => 'group',
			'relationship' => 'invited',
			'relationship_guid' => (int) $user_guid,
			'inverse_relationship' => true,
			'limit' => false,
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
