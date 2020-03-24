<?php

namespace Elgg\Friends\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Relationship {

		/**
	 * Add menu items to a pending friend request
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:relationship'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function addPendingFriendRequestItems(\Elgg\Hook $hook) {
		
		$relationship = $hook->getParam('relationship');
		if (!$relationship instanceof \ElggRelationship || $relationship->relationship !== 'friendrequest') {
			return;
		}
		
		$page_owner = elgg_get_page_owner_entity();
		if ($relationship->guid_two !== $page_owner->guid) {
			// looking at sent requests
			return;
		}
		
		/* @var $result \Elgg\Menu\MenuItems */
		$result = $hook->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'accept',
			'icon' => 'check',
			'text' => elgg_echo('accept'),
			'href' => elgg_generate_action_url('friends/request/accept', [
				'id' => $relationship->id,
			]),
			'section' => 'actions',
			'link_class' => 'elgg-button elgg-button-submit',
		]);
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'decline',
			'icon' => 'delete',
			'text' => elgg_echo('decline'),
			'href' => elgg_generate_action_url('friends/request/decline', [
				'id' => $relationship->id,
			]),
			'section' => 'actions',
			'link_class' => 'elgg-button elgg-button-delete',
			'confirm' => elgg_echo('deleteconfirm'),
		]);
		
		return $result;
	}
	
	/**
	 * Add menu items to a sent friend request
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:relationship'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function addSentFriendRequestItems(\Elgg\Hook $hook) {
		
		$relationship = $hook->getParam('relationship');
		if (!$relationship instanceof \ElggRelationship || $relationship->relationship !== 'friendrequest') {
			return;
		}
		
		$page_owner = elgg_get_page_owner_entity();
		if ($relationship->guid_one !== $page_owner->guid) {
			// looking at pending requests
			return;
		}
		
		/* @var $result \Elgg\Menu\MenuItems */
		$result = $hook->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'revoke',
			'icon' => 'delete',
			'text' => elgg_echo('revoke'),
			'href' => elgg_generate_action_url('friends/request/revoke', [
				'id' => $relationship->id,
			]),
			'section' => 'actions',
			'link_class' => 'elgg-button elgg-button-delete',
		]);
		
		return $result;
	}
}
