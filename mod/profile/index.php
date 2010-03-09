<?php
/**
 * Elgg profile index
 * 
 * @package ElggProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
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

		case 'widgets':
			$body .= elgg_view_layout('widgets');
		break;

		case 'friends':
			$body .= elgg_view('profile/profile_contents/friends', array("entity" => $user));
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
	//$body .= elgg_view_entity($user,true);
	$title = $user->name;
	//$body .= elgg_view_layout('widgets');
} else {
	$body = elgg_echo("profile:notfound");
	$title = elgg_echo("profile");
}
if ($option == 'widgets') {
	//page_draw_widgets($title, $body, $sidebar="");
} else {
	$body = elgg_view_layout("one_column", $body);
	page_draw($title, $body);
}