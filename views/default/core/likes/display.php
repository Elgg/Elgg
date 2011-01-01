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
	$likes = get_annotations($guid, '', '', 'likes', '', get_loggedin_userid());
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

$list = '';
$num_of_likes = elgg_count_likes($vars['entity']);
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
	$list .= "<div class='elgg-popup-module elgg-likes-list hidden clearfix'>";
	$list .= list_annotations($guid, 'likes', 99);
	$list .= "</div>";
}

echo $likes_button;
echo $list;
