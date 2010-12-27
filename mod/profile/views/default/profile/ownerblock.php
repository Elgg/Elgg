<?php
/**
 * A simple owner block which houses info about the user whose 'stuff' you are looking at
 */

// get the user who owns this profile
if ($vars['entity']) {
	if ($vars['context'] == 'edit') {
		$user = get_entity($vars['entity']->container_guid);
	} else {
		$user = get_entity($vars['entity']->guid);
	}
} else {
	$user = elgg_get_page_owner();
}
if (!$user) {
	// no user so we quit view
	echo elgg_echo('viewfailure', array(__FILE__));
	return TRUE;
}

$more_info = '';

$location = elgg_view("output/tags", array('value' => $user->location));

$icon = elgg_view("profile/icon", array(
	'entity' => $user,
	'size' => 'large',
	'override' => 'true'
));
$icon_class = "large";

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
	'entity' => elgg_get_page_owner(),
	'class' => 'profile-content-menu',
));

//contruct the display
$display = <<<EOT

<div id="profile-owner-block">
	<div class="owner_block_icon $icon_class">
		$icon
	</div>
	$more_info
	$profile_actions
	$content_menu
	$admin_links
</div>

EOT;

echo $display;
