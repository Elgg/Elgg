<?php
/**
 * Elgg top toolbar
 * The standard elgg top toolbar
 */

$user = get_loggedin_user();
if (($user instanceof ElggUser) && ($user->guid > 0)) {
	echo '<div id="elgg_topbar" class="clearfix">';
	echo '<div id="elgg_topbar_contents">';
	
	// Elgg logo
	echo '<a href="http://www.elgg.org">';
	echo "<img class=\"site_logo\" src=\"".elgg_get_site_url()."_graphics/elgg_toolbar_logo.gif\" alt=\"Elgg logo\" />";
	echo '</a>';
	
	// avatar
	$user_link = $user->getURL();
	$user_image = $user->getIcon('topbar');
	echo "<a href=\"$user_link\"><img class=\"user_mini_avatar\" src=\"$user_image\" alt=\"User avatar\" /></a>";

	// logout link
	echo elgg_view('page_elements/topbar_logout', $vars);
	
	// elgg tools menu
	// need to echo this empty view for backward compatibility.
	echo elgg_view("navigation/topbar_tools");
	
	// enable elgg topbar extending
	echo elgg_view('elgg_topbar/extend', $vars);
	
	// user settings
	$settings = elgg_echo('settings');
	echo "<a href=\"".elgg_get_site_url()."pg/settings\" class=\"settings\">$settings</a>";

	// The administration link is for admin or site admin users only
	if ($user->isAdmin()) {
		$admin = elgg_echo("admin");
		echo "<a href=\"".elgg_get_site_url()."pg/admin\" class=\"admin\">$admin</a>";
	}
	
	echo '</div>';
	echo '</div>';
}
