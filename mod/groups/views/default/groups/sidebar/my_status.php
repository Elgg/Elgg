<?php
/**
 * Group status for logged in user
 *
 * @package ElggGroups
 *
 * @uses $vars['entity'] Group entity
 */

$group = elgg_extract('entity', $vars);
$user = elgg_get_logged_in_user_entity();
$subscribed = elgg_extract('subscribed', $vars);

if (!elgg_is_logged_in()) {
	return true;
}

// membership status
$is_member = $group->isMember($user);
$is_owner = $group->getOwnerEntity() == $user;

if ($is_owner) {
	elgg_register_menu_item('groups:my_status', array(
		'name' => 'membership_status',
		'text' => '<a>' . elgg_echo('groups:my_status:group_owner') . '</a>',
		'href' => false
	));
} elseif ($is_member) {
	elgg_register_menu_item('groups:my_status', array(
		'name' => 'membership_status',
		'text' => '<a>' . elgg_echo('groups:my_status:group_member') . '</a>',
		'href' => false
	));
} else {
	elgg_register_menu_item('groups:my_status', array(
		'name' => 'membership_status',
		'text' => elgg_echo('groups:join'),
		'href' => "/action/groups/join?group_guid={$group->getGUID()}",
		'is_action' => true
	));
}

// notification info
if (elgg_is_active_plugin('notifications') && $is_member) {
	if ($subscribed) {
		elgg_register_menu_item('groups:my_status', array(
			'name' => 'subscription_status',
			'text' => elgg_echo('groups:subscribed'),
			'href' => "notifications/group/$user->username",
			'is_action' => true
		));
	} else {
		elgg_register_menu_item('groups:my_status', array(
			'name' => 'subscription_status',
			'text' => elgg_echo('groups:unsubscribed'),
			'href' => "notifications/group/$user->username"
		));
	}
}

$body = elgg_view_menu('groups:my_status');
echo elgg_view_module('aside', elgg_echo('groups:my_status'), $body);
