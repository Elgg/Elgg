<?php
/**
 * Elgg top toolbar
 * The standard elgg top toolbar
 */

$user = get_loggedin_user();
if (!elgg_instanceof($user, 'user')) {
	// do not show tobpar to logged out users
	return true;
}

echo '<div class="elgg-page-topbar">';
echo '<div class="elgg-inner clearfix">';

// Elgg logo
$image = '<img src="' . elgg_get_site_url() . '_graphics/elgg_toolbar_logo.gif" alt="Elgg logo" />';
echo elgg_view('output/url', array(
	'href' => 'http://www.elgg.org/',
	'text' => $image,
));

// avatar
$user_link = $user->getURL();
$user_image = $user->getIcon('topbar');
$image = "<img src=\"$user_image\" alt=\"$user->name\" class=\"elgg-border-plain\" />";
echo elgg_view('output/url', array(
	'href' => $user_link,
	'text' => $image,
));

// friends
echo elgg_view('output/url', array(
	'href' => elgg_get_site_url() . "pg/friends/{$user->username}/",
	'text' => '<span class="elgg-icon elgg-icon-friends"></span>',
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
echo elgg_view("navigation/topbar_tools");

// enable elgg topbar extending
echo elgg_view('elgg_topbar/extend', $vars);

// user settings
echo elgg_view('output/url', array(
	'href' => elgg_get_site_url() . "pg/settings/user/{$user->username}",
	'text' => '<span class="elgg-icon elgg-icon-settings"></span>' . elgg_echo('settings'),
	'class' => 'elgg-alt',
));

// The administration link is for admin or site admin users only
if ($user->isAdmin()) {
	echo elgg_view('output/url', array(
		'href' => elgg_get_site_url() . 'pg/admin/',
		'text' => '<span class="elgg-icon elgg-icon-settings"></span>' . elgg_echo('admin'),
		'class' => 'elgg-alt',
	));
}

echo '</div>';
echo '</div>';
