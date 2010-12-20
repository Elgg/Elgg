<?php
/**
 * Elgg likes add form
 *
 * @package Elgg
 *
 * @uses $vars['entity']
 */

if (isset($vars['entity']) && isloggedin()) {
	$guid = $vars['entity']->getGUID();
	$url = elgg_get_site_url() . "action/likes/add?guid={$guid}";
	
	//check to see if the user has already liked
	if (!elgg_annotation_exists($guid, 'likes') ) {
		$params = array(
			'href' => $url,
			'text' => '<span class="elgg-icon elgg-icon-likes"></span>',
			'title' => elgg_echo('likes:likethis'),
			'is_action' => true,
		);
		echo elgg_view('output/url', $params);
		$likes_classname = 'elgg-icon-likes';
		$likes_titletag = "";
	} else {
		$likes_classname = 'elgg-icon-liked';
		$likes_titletag = elgg_echo('likes:remove');
	}
	
	//display the number of likes
	$numoflikes = elgg_count_likes($vars['entity']);
	if ($numoflikes != 0) {
		if ($numoflikes == 1) {
			$user_string = elgg_echo('likes:userlikedthis');
		} else {
			$user_string = elgg_echo('likes:userslikedthis');
		}

		$params = array(
			'href' => $url,
			'text' => "<span class=\"elgg-icon $likes_classname\"></span>" . elgg_count_likes($vars['entity']) . " " . $user_string,
			'title' => $likes_titletag,
			'is_action' => true,
		);
		echo elgg_view('output/url', $params);

		//show the users who liked the object
		echo "<div class='likes-list hidden clearfix'>";
		echo list_annotations($vars['entity']->getGUID(), 'likes', 99);
		echo "</div>";	
	}
}





