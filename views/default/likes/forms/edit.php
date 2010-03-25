<?php
/**
 * Elgg likes add form
 *
 * @package Elgg
 * @author Curverider Ltd <info@elgg.com>
 * @link http://elgg.com/
 *
 * @uses $vars['entity']
 */

if (isset($vars['entity']) && isloggedin()) {
	$guid = $vars['entity']->getGuid();
	$url = elgg_add_action_tokens_to_url($vars['url'] . "action/likes/add?guid={$guid}");
	//check to see if the user has already liked
	if (!elgg_annotation_exists($guid, 'likes')) {
		echo "<a href=\"{$url}\">" . elgg_echo('likes:likethis') . "</a><br />";
	}
	//display the number of likes
	$numoflikes = elgg_count_likes($vars['entity']);
	if ($numoflikes != 0) {
		if ($numoflikes == 1) {
			$user_string = elgg_echo('likes:userlikedthis');
		} else {
			$user_string = elgg_echo('likes:userslikedthis');
		}

		echo "<a class=\"likes_list_button\">" . elgg_count_likes($vars['entity']) . " " . $user_string . "</a>";
	}
	//show the users who liked the object
	echo "<div class=\"likes_list\" style=\"display: none;\">";
	echo list_annotations($vars['entity']->getGUID(), 'likes', 99);
	echo "</div>";
}