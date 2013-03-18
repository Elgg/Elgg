<?php
/**
 * Group status for logged in user
 *
 * @package ElggGroups
 *
 * @uses $vars['entity'] Group entity
 */

$project = elgg_extract('entity', $vars);
$user = elgg_get_logged_in_user_entity();
$subscribed = elgg_extract('subscribed', $vars);

if (!elgg_is_logged_in()) {
	return true;
}

// membership status
$is_member = $project->isMember($user);
$is_owner = $project->getOwnerEntity() == $user;

if ($is_owner) {
	elgg_register_menu_item('projects:my_status', array(
		'name' => 'membership_status',
		'text' => '<a>' . elgg_echo('projects:my_status:project_owner') . '</a>',
		'href' => false
	));
} elseif ($is_member) {
	elgg_register_menu_item('projects:my_status', array(
		'name' => 'membership_status',
		'text' => '<a>' . elgg_echo('projects:my_status:project_member') . '</a>',
		'href' => false
	));
} else {
	elgg_register_menu_item('projects:my_status', array(
		'name' => 'membership_status',
		'text' => elgg_echo('projects:join'),
		'href' => "/action/projects/join?project_guid={$project->getGUID()}",
		'is_action' => true
	));
}

// notification info
if (elgg_is_active_plugin('notifications')) {
	if ($subscribed) {
		elgg_register_menu_item('projects:my_status', array(
			'name' => 'subscription_status',
			'text' => elgg_echo('projects:subscribed'),
			'href' => "notifications/project/$user->username",
			'is_action' => true
		));
	} else {
		elgg_register_menu_item('projects:my_status', array(
			'name' => 'subscription_status',
			'text' => elgg_echo('projects:unsubscribed'),
			'href' => "notifications/project/$user->username"
		));
	}
}

$body = elgg_view_menu('projects:my_status');
echo elgg_view_module('aside', elgg_echo('projects:my_status'), $body);
