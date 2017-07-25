<?php
/**
 * Profile owner block
 *
 * @uses $vars['entity'] The user entity
 */

$user = elgg_extract('entity', $vars);
if (!($user instanceof \ElggUser)) {
	// no user so we quit view
	echo elgg_echo('viewfailure', [__FILE__]);
	return true;
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

$admin = $menu->getSection('admin', []);

// if admin, display admin links
$admin_links = '';
if (elgg_is_admin_logged_in() && elgg_get_logged_in_user_guid() != $user->guid) {
	$text = elgg_echo('admin:options');

	$admin_links = '<ul class="profile-admin-menu-wrapper">';
	$admin_links .= "<li><a rel=\"toggle\" href=\"#profile-menu-admin\">$text&hellip;</a>";
	$admin_links .= '<ul class="elgg-menu profile-admin-menu hidden" id="profile-menu-admin">';
	foreach ($admin as $menu_item) {
		$admin_links .= elgg_view('navigation/menu/elements/item', ['item' => $menu_item]);
	}
	$admin_links .= '</ul>';
	$admin_links .= '</li>';
	$admin_links .= '</ul>';
}

// content links
$content_menu = elgg_view_menu('owner_block', [
	'entity' => elgg_get_page_owner_entity(),
	'class' => 'profile-content-menu',
]);

echo <<<HTML

<div id="profile-owner-block">
	$icon
	$content_menu
	$admin_links
</div>

HTML;
