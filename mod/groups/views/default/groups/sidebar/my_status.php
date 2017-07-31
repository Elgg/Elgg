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

// notification info
if (elgg_is_active_plugin('notifications') && $is_member) {
	if ($subscribed) {
		elgg_register_menu_item('groups:my_status', [
			'name' => 'subscription_status',
			'text' => elgg_echo('groups:subscribed'),
			'href' => "notifications/group/$user->username",
			'is_action' => true
		]);
	} else {
		elgg_register_menu_item('groups:my_status', [
			'name' => 'subscription_status',
			'text' => elgg_echo('groups:unsubscribed'),
			'href' => "notifications/group/$user->username"
		]);
	}
}

$body = elgg_view_menu('groups:my_status', [
	'class' => 'elgg-menu-page',
]);
echo elgg_view_module('aside', elgg_echo('groups:my_status'), $body);
