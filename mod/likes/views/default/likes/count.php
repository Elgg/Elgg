<?php
/**
 * Count of who has liked something
 *
 *  @uses $vars['entity']
 */


$list = '';
$num_of_likes = likes_count($vars['entity']);
$guid = $vars['entity']->getGUID();

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
		'href' => "#likes-$guid",
		'class' => 'elgg-non-link',
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
	echo $list;
}
