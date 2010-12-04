<?php
/**
 * Elgg profile index
 * 
 * @package ElggProfile
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$username = get_input('username');
$option = get_input('option', 'activity');
$body = '';

// Try and get the user from the username and set the page body accordingly
if ($user = get_user_by_username($username)) {
	if ($user->isBanned() && !isadminloggedin()) {
		forward(); exit;
	}
	$body = elgg_view('profile/profile_navigation', array("option" => $option,"entity" => $user));
	switch($option){
		case 'activity':
			$body .= elgg_view('profile/profile_contents/details', array("entity" => $user));
		break;

		case 'friends':
			$body .= elgg_view('profile/profile_contents/friends', array("entity" => $user));
		break;
		case 'groups':
			$body .= elgg_view('profile/profile_contents/groups', array("entity" => $user));
		break;
		case 'extend':
			$body .= elgg_view('profile/profile_contents/extend', array("entity" => $user));
		break;

		case 'twitter':
			$body .= elgg_view('profile/profile_contents/twitter', array("entity" => $user));
		break;

		case 'default':
			$body .= elgg_view('profile/profile_contents/details', array("entity" => $user));
		break;
	}
	$title = $user->name;
} else {
	$body = elgg_echo("profile:notfound");
	$title = elgg_echo("profile");
}
$body = elgg_view_layout("one_column", array('content' => $body));
echo elgg_view_page($title, $body);