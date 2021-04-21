<?php

namespace Elgg\Groups\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Title {
	
	/**
	 * Registers title menu items for group
	 *
	 * @param \Elgg\Hook $hook 'register' 'menu:title'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		$group = $hook->getEntityParam();
		if (!$group instanceof \ElggGroup) {
			return;
		}
		
		$user = elgg_get_logged_in_user_entity();
		if (empty($user)) {
			return;
		}
		
		$result = $hook->getValue();
		if ($group->canEdit()) {
			// group owners can edit the group and invite new members
			$result[] = \ElggMenuItem::factory([
				'name' => 'edit',
				'icon' => 'edit',
				'href' => elgg_generate_entity_url($group, 'edit'),
				'text' => elgg_echo('groups:edit'),
				'link_class' => 'elgg-button elgg-button-action',
			]);
			
			if (elgg_is_active_plugin('friends')) {
				$result[] = \ElggMenuItem::factory([
					'name' => 'groups:invite',
					'icon' => 'user-plus',
					'href' => elgg_generate_entity_url($group, 'invite'),
					'text' => elgg_echo('groups:invite'),
					'link_class' => 'elgg-button elgg-button-action',
				]);
			}
		}
		
		if ($group->isMember($user)) {
			$is_owner = ($group->owner_guid === $user->guid);
			$result[] = \ElggMenuItem::factory([
				'name' => 'group-dropdown',
				'href' => false,
				'text' => elgg_echo($is_owner ? 'groups:button:owned' : 'groups:button:joined'),
				'link_class' => 'elgg-button elgg-button-action-done',
				'child_menu' => [
					'display' => 'dropdown',
				],
				'data-position' => json_encode([
					'my' => 'right top',
					'at' => 'right bottom',
				]),
			]);
			
			// leave group
			$leave_group = groups_get_group_leave_menu_item($group, $user);
			if ($leave_group instanceof \ElggMenuItem) {
				$leave_group->setParentName('group-dropdown');
				$result[] = $leave_group;
			}
			
			// subscription settings
			$subscribed = $group->hasSubscriptions($user->guid);
			
			$result[] = \ElggMenuItem::factory([
				'name' => 'notifications',
				'parent_name' => 'group-dropdown',
				'text' => elgg_echo('groups:usersettings:notifications:title'),
				'href' => elgg_generate_url('settings:notification:groups', [
					'username' => $user->username,
				]),
				'badge' => $subscribed ? elgg_echo('on') : elgg_echo('off'),
				'icon' => $subscribed ? 'bell' : 'bell-slash',
			]);
		} else {
			$join_group = groups_get_group_join_menu_item($group, $user);
			if ($join_group instanceof \ElggMenuItem) {
				$join_group->setLinkClass('elgg-button elgg-button-action');
				$result[] = $join_group;
			}
		}
		
		return $result;
	}
}
