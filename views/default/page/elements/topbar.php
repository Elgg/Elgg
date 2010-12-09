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
echo '<a href="http://www.elgg.org" class="main">';
echo "<img class=\"site-logo\" src=\"".elgg_get_site_url()."_graphics/elgg_toolbar_logo.gif\" alt=\"Elgg logo\" />";
echo '</a>';

// avatar
$user_link = $user->getURL();
$user_image = $user->getIcon('topbar');
echo "<a href=\"$user_link\" class=\"main\"><img class=\"user-mini-avatar\" src=\"$user_image\" alt=\"User avatar\" /></a>";

// friends
//$friends = elgg_echo('friends');
//echo "<a class='myfriends main' href=\"".elgg_get_site_url()."pg/friends/{$user->username}\" title=\"$friends\">&nbsp;</a>";
echo elgg_view('output/url', array(
	'href' => elgg_get_site_url() . "pg/settings/{$user->username}/",
	'text' => '<span class="elgg-icon elgg-icon-friends"></span>',
	'class' => 'main',
	'title' => elgg_echo('friends'),
));

// logout link
echo elgg_view('output/url', array(
	'href' => "action/logout",
	'text' => elgg_echo('logout'),
	'is_action' => TRUE,
	'class' => 'alt',
));

// elgg tools menu
// need to echo this empty view for backward compatibility.
echo elgg_view("navigation/topbar_tools");

// enable elgg topbar extending
echo elgg_view('elgg_topbar/extend', $vars);

// user settings
echo elgg_view('output/url', array(
	'href' => elgg_get_site_url() . 'pg/settings/',
	'text' => '<span class="elgg-icon elgg-icon-settings"></span>' . elgg_echo('settings'),
	'class' => 'alt',
));

// The administration link is for admin or site admin users only
if ($user->isAdmin()) {
	echo elgg_view('output/url', array(
		'href' => elgg_get_site_url() . 'pg/admin/',
		'text' => '<span class="elgg-icon elgg-icon-settings"></span>' . elgg_echo('admin'),
		'class' => 'alt',
	));
}

echo '</div>';
echo '</div>';
