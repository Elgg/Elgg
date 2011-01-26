<?php
/**
 * Profile owner block
 */

$user = elgg_get_page_owner_entity();

if (!$user) {
	// no user so we quit view
	echo elgg_echo('viewfailure', array(__FILE__));
	return TRUE;
}

$icon = elgg_view("profile/icon", array(
	'entity' => $user,
	'size' => 'large',
	'override' => 'true'
));

// grab the actions and admin menu items from user hover
$menu = elgg_trigger_plugin_hook('register', "menu:user_hover", array('entity' => $user), array());
$builder = new ElggMenuBuilder($menu);
$menu = $builder->getMenu();
$actions = elgg_get_array_value('action', $menu, array());
$admin = elgg_get_array_value('admin', $menu, array());

$profile_actions = '';
if (isloggedin() && $actions) {
	$profile_actions = '<ul class="elgg-menu">';
	foreach ($actions as $action) {
		$profile_actions .= '<li>' . $action->getLink(array('class' => 'elgg-action-button')) . '</li>';
	}
	$profile_actions .= '</ul>';
}

// if admin, display admin links
$admin_links = '';
if (isadminloggedin() && get_loggedin_userid() != elgg_get_page_owner_guid()) {
	$admin_links = '<ul class="profile-admin-menu-wrapper">';
	$admin_links .= '<li><a class="elgg-toggle" id="elgg-toggler-admin-menu">Admin options&hellip;</a>';
	$admin_links .= '<ul class="profile-admin-menu" id="elgg-togglee-admin-menu">';
	foreach ($admin as $menu_item) {
		$admin_links .= elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
	}
	$admin_links .= '</ul>';
	$admin_links .= '</li>';
	$admin_links .= '</ul>';	
}

// content links
$content_menu = elgg_view_menu('owner_block', array(
	'entity' => elgg_get_page_owner_entity(),
	'class' => 'profile-content-menu',
));

echo <<<HTML

<div id="profile-owner-block">
	$icon
	$profile_actions
	$content_menu
	$admin_links
</div>

HTML;
