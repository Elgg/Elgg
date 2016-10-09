<?php
/**
 * Profile owner block
 */

$user = elgg_get_page_owner_entity();

if (!$user) {
	// no user so we quit view
	echo elgg_echo('viewfailure', [__FILE__]);
	return;
}

$icon = elgg_view_entity_icon($user, 'large', [
	'use_hover' => false,
	'use_link' => false,
	'img_class' => 'photo u-photo',
]);

// grab the actions and admin menu items from user hover
$menu = elgg()->menus->getMenu('user_hover', [
	'entity' => $user,
	'username' => $user->username,
]);

$action_links = elgg_view('profile/owner_block/actions', [
	'actions' => $menu->getSection('action', []),
]);

$content_links = elgg_view_menu('owner_block', [
	'entity' => elgg_get_page_owner_entity(),
	'class' => 'profile-content-menu',
]);

$admin_links = elgg_view('profile/owner_block/admin', [
	'admin' => $menu->getSection('admin', []),
]);

echo <<<HTML

<div id="profile-owner-block">
	$icon
	$action_links
	$content_links
	$admin_links
</div>

HTML;
