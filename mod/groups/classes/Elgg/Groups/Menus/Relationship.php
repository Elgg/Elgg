<?php

namespace Elgg\Groups\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Relationship {
	
	/**
	 * Add a remove user link to relationship menu if it's about a group membership relationship
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:relationship'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerRemoveUser(\Elgg\Hook $hook) {
		
		$relationship = $hook->getParam('relationship');
		if (!$relationship instanceof \ElggRelationship || $relationship->relationship !== 'member') {
			return;
		}
		
		$user = get_entity($relationship->guid_one);
		$group = get_entity($relationship->guid_two);
	
		// Make sure we have a user and a group
		if (!$user instanceof \ElggUser || !$group instanceof \ElggGroup) {
			return;
		}
	
		// Check if we are looking at the group owner
		if ($group->owner_guid === $user->guid) {
			return;
		}
		
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'removeuser',
			'href' => elgg_generate_action_url('groups/remove', [
				'user_guid' => $user->guid,
				'group_guid' => $group->guid,
			]),
			'text' => elgg_echo('groups:removeuser'),
			'icon' => 'user-times',
			'confirm' => true,
		]);
	
		return $return;
	}

	/**
	 * Add menu items to the group membership request relationship menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:relationship'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerMembershipRequestItems(\Elgg\Hook $hook) {
		
		$relationship = $hook->getParam('relationship');
		if (!$relationship instanceof \ElggRelationship || $relationship->relationship !== 'membership_request') {
			return;
		}
		
		$user = get_entity($relationship->guid_one);
		$group = get_entity($relationship->guid_two);
		if (!$group instanceof \ElggGroup || !$user instanceof \ElggUser) {
			return;
		}
		
		/* @var $result \Elgg\Menu\MenuItems */
		$result = $hook->getValue();
		
		$page_owner = elgg_get_page_owner_entity();
		if ($page_owner->guid === $group->guid && $group->canEdit()) {
			$result[] = \ElggMenuItem::factory([
				'name' => 'accept',
				'text' => elgg_echo('accept'),
				'href' => elgg_generate_action_url('groups/addtogroup', [
					'user_guid' => $user->guid,
					'group_guid' => $group->guid,
				]),
				'link_class' => 'elgg-button elgg-button-submit',
				'section' => 'actions',
			]);
			
			$result[] = \ElggMenuItem::factory([
				'name' => 'reject',
				'text' => elgg_echo('delete'),
				'href' => elgg_generate_action_url('groups/killrequest', [
					'user_guid' => $user->guid,
					'group_guid' => $group->guid,
				]),
				'confirm' => elgg_echo('groups:joinrequest:remove:check'),
				'link_class' => 'elgg-button elgg-button-delete',
				'section' => 'actions',
			]);
		}
		
		return $result;
	}
	
	/**
	 * Add menu items to the invited relationship menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:relationship'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerInvitedItems(\Elgg\Hook $hook) {
		
		$relationship = $hook->getParam('relationship');
		if (!$relationship instanceof \ElggRelationship || $relationship->relationship !== 'invited') {
			return;
		}
		
		$group = get_entity($relationship->guid_one);
		$user = get_entity($relationship->guid_two);
		if (!$group instanceof \ElggGroup || !$user instanceof \ElggUser) {
			return;
		}
		
		/* @var $result \Elgg\Menu\MenuItems */
		$result = $hook->getValue();
		
		$page_owner = elgg_get_page_owner_entity();
		if ($page_owner->guid === $group->guid && $group->canEdit()) {
			$result[] = \ElggMenuItem::factory([
				'name' => 'delete',
				'text' => elgg_echo('delete'),
				'href' => elgg_generate_action_url('groups/killinvitation', [
					'user_guid' => $user->guid,
					'group_guid' => $group->guid,
				]),
				'confirm' => elgg_echo('groups:invite:remove:check'),
				'link_class' => 'elgg-button elgg-button-delete',
				'section' => 'actions',
			]);
		} elseif ($page_owner->guid === $user->guid && $user->canEdit()) {
			$result[] = \ElggMenuItem::factory([
				'name' => 'accept',
				'text' => elgg_echo('accept'),
				'href' => elgg_generate_action_url('groups/join', [
					'user_guid' => $user->guid,
					'group_guid' => $group->guid,
				]),
				'link_class' => 'elgg-button elgg-button-submit',
				'is_trusted' => true,
				'section' => 'actions',
			]);
			
			$result[] = \ElggMenuItem::factory([
				'name' => 'delete',
				'text' => elgg_echo('delete'),
				'href' => elgg_generate_action_url('groups/killinvitation', [
					'user_guid' => $user->guid,
					'group_guid' => $group->guid,
				]),
				'confirm' => elgg_echo('groups:invite:remove:check'),
				'link_class' => 'elgg-button elgg-button-delete',
				'section' => 'actions',
			]);
		}
		
		return $result;
	}
}
