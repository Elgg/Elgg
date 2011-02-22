<?php
/**
 * Elgg top toolbar
 * The standard elgg top toolbar
 */

$user = elgg_get_logged_in_user_entity();

//@todo echo elgg_view_menu('topbar', array('class' => 'elgg-menu-topbar'));

// Elgg logo
$image = '<img src="' . elgg_get_site_url() . '_graphics/elgg_toolbar_logo.gif" alt="Elgg logo" />';
echo elgg_view('output/url', array(
	'href' => 'http://www.elgg.org/',
	'text' => $image,
));

// avatar
$user_link = $user->getURL();
$user_image = $user->getIconURL('topbar');
$image = "<img src=\"$user_image\" alt=\"$user->name\" class=\"bab\" />";
echo elgg_view('output/url', array(
	'href' => $user_link,
	'text' => $image,
));

// friends
echo elgg_view('output/url', array(
	'href' => "pg/friends/{$user->username}/",
	'text' => elgg_view_icon('friends'),
	'title' => elgg_echo('friends'),
));

// logout link
echo elgg_view('output/url', array(
	'href' => "action/logout",
	'text' => elgg_echo('logout'),
	'is_action' => TRUE,
	'class' => 'elgg-alt',
));

// elgg tools menu
// need to echo this empty view for backward compatibility.
// @todo -- do we really?  So much else is broken, and the new menu system is so much nicer...
echo elgg_view("navigation/topbar_tools");

// enable elgg topbar extending
echo elgg_view('elgg_topbar/extend', $vars);

//@todo echo elgg_view_menu('topbar2', array('class' => 'elgg-menu-topbar elgg-alt'));

// user settings
echo elgg_view('output/url', array(
	'href' => "pg/settings/user/{$user->username}",
	'text' => elgg_view_icon('settings') . elgg_echo('settings'),
	'class' => 'elgg-alt',
));

// The administration link is for admin or site admin users only
if ($user->isAdmin()) {
	echo elgg_view('output/url', array(
		'href' => 'pg/admin/',
		'text' => elgg_view_icon('settings') . elgg_echo('admin'),
		'class' => 'elgg-alt',
	));
}
