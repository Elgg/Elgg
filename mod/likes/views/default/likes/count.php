<?php
/**
 * Count of who has liked something
 *
 *  @uses $vars['entity']
 */

$num_of_likes = \Elgg\Likes\DataService::instance()->getNumLikes($vars['entity']);
$guid = $vars['entity']->guid;

elgg_load_js('lightbox');
elgg_load_css('lightbox');

// display the number of likes
if ($num_of_likes == 1) {
	$likes_string = elgg_echo('likes:userlikedthis', array($num_of_likes));
} else {
	$likes_string = elgg_echo('likes:userslikedthis', array($num_of_likes));
}

$class = 'elgg-lightbox elgg-non-link';
if (!$num_of_likes) {
	$class .= ' hidden';
}

$params = array(
	'text' => $likes_string,
	'title' => elgg_echo('likes:see'),
	'class' => $class,
	'href' => '#',
	'data-likes-guid' => $vars['entity']->guid,
	'data-colorbox-opts' => json_encode([
		'maxHeight' => '85%',
		'href' => elgg_normalize_url("ajax/view/likes/popup?guid=$guid")
	]),
);
echo elgg_view('output/url', $params);
