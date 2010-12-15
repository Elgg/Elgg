<?php
/**
 * Elgg likes - display users liked link/text
 *
 * @package Elgg
 *
 * @uses $vars['entity']
 */

if (isset($vars['entity']) && isloggedin()) {

	//display the number of likes
	$numoflikes = elgg_count_likes($vars['entity']);
	if ($numoflikes != 0) {
		if ($numoflikes == 1) {
			$user_string = elgg_echo('likes:userlikedthis');
		} else {
			$user_string = elgg_echo('likes:userslikedthis');
		}

		echo "<a class='river_more_comments off likes_user_list_button link'>" . elgg_count_likes($vars['entity']) . " " . $user_string . "</a>";
	}
}