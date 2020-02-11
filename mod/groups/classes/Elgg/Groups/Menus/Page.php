<?php

namespace Elgg\Groups\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Page {

	/**
	 * Register menu items for the page menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerGroupProfile(\Elgg\Hook $hook) {
		
		if (!elgg_in_context('group_profile') || !elgg_is_logged_in()) {
			return;
		}
		
		// Get the page owner entity
		$page_owner = elgg_get_page_owner_entity();
		if (!$page_owner instanceof \ElggGroup || !$page_owner->canEdit()) {
			return;
		}
		
		/* @var $return \Elgg\Menu\MenuItems */
		$return = $hook->getValue();
		
		if ($page_owner->isPublicMembership()) {
			// show lint to invited users
			$return[] = \ElggMenuItem::factory([
				'name' => 'membership_invites',
				'text' => elgg_echo('groups:invitedmembers'),
				'href' => elgg_generate_url('collection:user:user:group_invites', [
					'guid' => $page_owner->guid,
				]),
			]);
		} else {
			// show link to mebership requests
			$count = elgg_count_entities([
				'type' => 'user',
				'relationship' => 'membership_request',
				'relationship_guid' => $page_owner->guid,
				'inverse_relationship' => true,
			]);
		
			$text = elgg_echo('groups:membershiprequests');
			$title = $text;
			if ($count) {
				$title = elgg_echo('groups:membershiprequests:pending', [$count]);
			}
			
			$return[] = \ElggMenuItem::factory([
				'name' => 'membership_requests',
				'text' => $text,
				'badge' => $count ? $count : null,
				'title' => $title,
				'href' => elgg_generate_entity_url($page_owner, 'requests'),
			]);
		}
		
		return $return;
	}
	
	/**
	 * Register menu items for the page menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		
		if (elgg_get_context() !== 'groups') {
			return;
		}
		
		// Get the page owner entity
		$page_owner = elgg_get_page_owner_entity();
		if ($page_owner instanceof \ElggGroup) {
			return;
		}
		
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'groups:all',
			'text' => elgg_echo('groups:all'),
			'href' => elgg_generate_url('collection:group:group:all'),
		]);
	
		$user = elgg_get_logged_in_user_entity();
		if (!$user) {
			return $return;
		}
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'groups:owned',
			'text' => elgg_echo('groups:owned'),
			'href' => elgg_generate_url('collection:group:group:owner', [
				'username' => $user->username,
			]),
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'groups:member',
			'text' => elgg_echo('groups:yours'),
			'href' => elgg_generate_url('collection:group:group:member', [
				'username' => $user->username,
			]),
		]);
	
		$invitation_count = groups_get_invited_groups($user->guid, false, ['count' => true]);
	
		// Invitations
		$text = elgg_echo('groups:invitations');
		$title = $text;
		if ($invitation_count) {
			$title = elgg_echo('groups:invitations:pending', [$invitation_count]);
		}
	
		$return[] = \ElggMenuItem::factory([
			'name' => 'groups:user:invites',
			'text' => $text,
			'badge' => $invitation_count ?: null,
			'title' => $title,
			'href' => elgg_generate_url('collection:group:group:invitations', [
				'username' => $user->username,
			]),
		]);
	
		return $return;
	}
}
