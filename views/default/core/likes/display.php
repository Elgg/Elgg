<?php
/**
 * Elgg likes display
 *
 * @package Elgg
 *
 * @uses $vars['entity']
 */

if (!isset($vars['entity'])) {
	return true;
}

$guid = $vars['entity']->getGUID();

// check to see if the user has already liked this
if (elgg_is_logged_in() && $vars['entity']->canAnnotate(0, 'likes')) {
	if (!elgg_annotation_exists($guid, 'likes')) {
		$url = elgg_get_site_url() . "action/likes/add?guid={$guid}";
		$params = array(
			'href' => $url,
			'text' => '<span class="elgg-icon elgg-icon-likes"></span>',
			'title' => elgg_echo('likes:likethis'),
			'is_action' => true,
			'encode_text' => false,
		);
		$likes_button = elgg_view('output/url', $params);
	} else {
		$options = array(
			'guid' => $guid,
			'annotation_name' => 'likes',
			'owner_guid' => elgg_get_logged_in_user_guid()
		);
		$likes = elgg_get_annotations($options);
		$url = elgg_get_site_url() . "action/likes/delete?annotation_id={$likes[0]->id}";
		$params = array(
			'href' => $url,
			'text' => "<span class=\"elgg-icon elgg-icon-liked\"></span>",
			'title' => elgg_echo('likes:remove'),
			'is_action' => true,
			'encode_text' => false,
		);
		$likes_button = elgg_view('output/url', $params);
	}
}

$list = '';
$num_of_likes = $vars['entity']->countLikes();
if ($num_of_likes) {
	// display the number of likes
	if ($num_of_likes == 1) {
		$likes_string = elgg_echo('likes:userlikedthis');
	} else {
		$likes_string = elgg_echo('likes:userslikedthis');
	}
	$params = array(
		'text' => "$num_of_likes $likes_string",
		'title' => elgg_echo('likes:see'),
		'class' => 'elgg-like-toggle',
	);
	$list = elgg_view('output/url', $params);
	$list .= "<div class='elgg-module elgg-module-popup elgg-likes-list hidden clearfix'>";
	$list .= elgg_list_annotations(array('guid' => $guid, 'annotation_name' => 'likes', 'limit' => 99));
	$list .= "</div>";
}

echo $likes_button;
echo $list;
