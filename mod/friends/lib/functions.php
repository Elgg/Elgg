<?php
/**
 * Holds helper functions for friends plugin
 */

/**
 * Generate menu items to add the user as a friend
 *
 * @param \ElggUser $user        the potential friend
 * @param bool      $make_button make the menu items buttons (default: false)
 *
 * @return ElggMenuItem[]
 * @internal
 * @since 3.2
 */
function _elgg_friends_get_add_friend_menu_items(\ElggUser $user, bool $make_button = false) {
	
	$current_user = elgg_get_logged_in_user_entity();
	if (!$current_user instanceof \ElggUser || $user->guid === $current_user->guid) {
		return [];
	}
	
	$result = [];
	$isFriend = $user->isFriendOf($current_user->guid);
	
	// Always emit both to make it super easy to toggle with ajax
	$result[] = \ElggMenuItem::factory([
		'name' => 'remove_friend',
		'icon' => 'user-times',
		'text' => elgg_echo('friend:remove'),
		'href' => elgg_generate_action_url('friends/remove', [
			'friend' => $user->guid,
		]),
		'section' => 'action',
		'link_class' => $make_button ? 'elgg-button elgg-button-action' : null,
		'item_class' => $isFriend ? '' : 'hidden',
		'data-toggle' => 'add_friend',
	]);
	
	$add_toggle = 'remove_friend';
	$pending_request = false;
	if ((bool) elgg_get_plugin_setting('friend_request', 'friends')) {
		$sent_request = (bool) check_entity_relationship($user->guid, 'friendrequest', $current_user->guid);
		$pending_request = (bool) check_entity_relationship($current_user->guid, 'friendrequest', $user->guid);
		if (!$isFriend && !$sent_request) {
			// no current friend, and no pending request
			$add_toggle = 'friend_requests';
		}
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'friend_requests',
			'icon' => 'user-plus',
			'text' => elgg_echo('friends:menu:request:status:pending'),
			'href' => elgg_generate_url('collection:relationship:friendrequest:sent', [
				'username' => $current_user->username,
			]),
			'section' => 'action',
			'link_class' => $make_button ? 'elgg-button elgg-button-action-done' : null,
			'item_class' => $pending_request ? '' : 'hidden',
		]);
	}
	
	$result[] = \ElggMenuItem::factory([
		'name' => 'add_friend',
		'icon' => 'user-plus',
		'text' => elgg_echo('friend:add'),
		'href' => elgg_generate_action_url('friends/add', [
			'friend' => $user->guid,
		]),
		'section' => 'action',
		'link_class' =>  $make_button ? 'elgg-button elgg-button-action' : null,
		'item_class' => ($pending_request || $isFriend) ? 'hidden' : '',
		'data-toggle' => $add_toggle,
	]);
	
	return $result;
}
