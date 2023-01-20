<?php
/**
 * Profile owner block
 *
 * @uses $vars['entity'] The user entity
 */

$user = elgg_extract('entity', $vars);
if (!$user instanceof \ElggUser) {
	// no user so we quit view
	echo elgg_echo('viewfailure', [__FILE__]);
	return;
}

$icon = elgg_view_entity_icon($user, 'large', [
	'use_hover' => false,
	'use_link' => false,
	'img_class' => 'photo u-photo',
]);

// if admin, display admin links
$admin_links = '';
if (elgg_is_admin_logged_in() && elgg_get_logged_in_user_guid() !== $user->guid) {
	$admin_links = elgg_view_menu('profile_admin', [
		'entity' => $user,
		'class' => ['elgg-menu-owner-block'],
		'prepare_vertical' => true,
	]);
}

// content links
$content_menu = elgg_view_menu('owner_block', [
	'entity' => $user,
	'prepare_vertical' => true,
]);

echo elgg_format_element('div', ['id' => 'profile-owner-block'], $icon . $content_menu . $admin_links);
