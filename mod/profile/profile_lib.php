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

/**
 * Returns the html for a user profile.
 *
 * @param string $username The username of the profile to display
 * @param string $section Which section is currently selected.
 *
 * return mixed FALSE or html for the profile.
 */
function profile_get_user_profile_html($user, $section = 'activity') {
	$body = elgg_view('profile/profile_navigation', array('section' => $section, 'entity' => $user));
	$view_options = array('entity' => $user);

	switch($section){
		case 'widgets':
			$body .= elgg_view_layout('widgets', $view_options);
			break;

		case 'friends':
			$body .= elgg_view('profile/profile_contents/friends', $view_options);
			break;

		case 'twitter':
			$body .= elgg_view('profile/profile_contents/twitter', $view_options);
			break;

		case 'details':
			$body .= elgg_view('profile/profile_contents/details', $view_options);
			break;

		default:
		case 'activity':
			$body .= elgg_view('profile/profile_contents/activity', $view_options);
			break;
	}

	$body .= elgg_view('profile/profile_contents/sidebar');
	return $body;
}

/**
 * Dispatch the html for the edit section
 *
 * @param unknown_type $user
 * @param unknown_type $page
 * @return string
 */
function profile_get_user_edit_content($user, $page) {
	$section = (isset($page[2])) ? $page[2] : 'details';

	switch ($section) {
		case 'icon':
			$content .= elgg_view_title(elgg_echo('profile:edit'));
			$content .= elgg_view("profile/editicon", array('entity' => $user));
			break;
		default:
		case 'details':
			$content = elgg_view_title(elgg_echo('profile:edit'));
			$content .= elgg_view("profile/edit", array('entity' => $user));
			break;
	}

	return $content;
}