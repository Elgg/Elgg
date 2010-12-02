<?php
/**
 * Elgg likes add form
 *
 * @package Elgg
 *
 * @uses $vars['entity']
 */

if (isset($vars['entity']) && isloggedin()) {
	$guid = $vars['entity']->getGuid();
	$url = elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/likes/add?guid={$guid}");
	
	echo "<span class='likes-list-holder'>";
	//check to see if the user has already liked
	if (!elgg_annotation_exists($guid, 'likes') ) {
		echo "<a class='user-like link' title='".elgg_echo('likes:likethis')."' href=\"{$url}\">" . elgg_echo('likes:likethis') . "</a>";
		$likes_classname = "not-liked";
		$likes_titletag = "";
	} else {
		$likes_titletag = "title='".elgg_echo('likes:remove')."'";
	}
	//display the number of likes
	$numoflikes = elgg_count_likes($vars['entity']);
	if ($numoflikes != 0) {
		if ($numoflikes == 1) {
			$user_string = elgg_echo('likes:userlikedthis');
		} else {
			$user_string = elgg_echo('likes:userslikedthis');
		}

		echo "<a class='likes-list_button link {$likes_classname}' {$likes_titletag}>" . elgg_count_likes($vars['entity']) . " " . $user_string . "</a>";

		//show the users who liked the object
		echo "<div class='likes-list hidden clearfix'>";
		echo list_annotations($vars['entity']->getGUID(), 'likes', 99);
		echo "</div>";	
	}
	echo "</span>";	
}





