<?php
/**
 * Elgg likes button
 *
 * @uses $vars['entity']
 */

if (!isset($vars['entity'])) {
	return true;
}

$guid = $vars['entity']->getGUID();

$list = '';
$num_of_likes = likes_count($vars['entity']);

// check to see if the user has already liked this
if (elgg_is_logged_in() && $vars['entity']->canAnnotate(0, 'likes')) {
	if (!elgg_annotation_exists($guid, 'likes')) {
		$url = elgg_get_site_url() . "action/likes/add?guid={$guid}";
		$params = array(
			'href' => $url,
			'text' => elgg_view_icon('thumbs-up'),
			'title' => elgg_echo('likes:likethis'),
			'is_action' => true,
			'is_trusted' => true,
			'class' => 'elgg_like',
			'id' => $guid,
		);
		$likes_button = elgg_view('output/url', $params);
	} else {
		$like = elgg_get_annotations(array(
			'guid' => $guid,
			'annotation_owner_guid' => elgg_get_logged_in_user_guid(),
			'annotation_name' => 'likes',
		));
		$like = $like[0];
		$url = elgg_get_site_url() . "action/likes/delete?id={$like->id}";
		$params = array(
			'href' => $url,
			'text' => elgg_view_icon('thumbs-up-alt'),
			'title' => elgg_echo('likes:remove'),
			'is_action' => true,
			'is_trusted' => true,
			'class' => 'elgg_like',
			'id' => $guid,
		);
		$likes_button = elgg_view('output/url', $params);
	}
}

if ($num_of_likes) {
	// display the number of likes
	if ($num_of_likes == 1) {
		$likes_string = elgg_echo('likes:userlikedthis', array($num_of_likes));
	} else {
		$likes_string = elgg_echo('likes:userslikedthis', array($num_of_likes));
	}
	$params = array(
		'text' => $likes_string,
		'title' => elgg_echo('likes:see'),
		'rel' => 'popup',
		'href' => "#likes-$guid"
	);
	$list = elgg_view('output/url', $params);
	$list .= "<div class='elgg-module elgg-module-popup elgg-likes hidden clearfix' id='likes-$guid'>";
	$list .= elgg_list_annotations(array(
		'guid' => $guid,
		'annotation_name' => 'likes',
		'limit' => 99,
		'list_class' => 'elgg-list-likes'
	));
	$list .= "</div>";
}


echo $likes_button. $list;
