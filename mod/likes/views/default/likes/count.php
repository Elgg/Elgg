<?php

/**
 * Count of who has liked something
 *
 *  @uses $entity
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$num_of_likes = \Elgg\Likes\DataService::instance()->getNumLikes($entity);

$class = [
	'elgg-lightbox',
	'nav-link',
];
if (!$num_of_likes) {
	$class[] = 'hidden';
}

echo elgg_view('output/url', [
	'text' => elgg_format_element('span', [
		'class' => 'elgg-counter',
		'data-channel' => "likes:$entity->guid",
			], $num_of_likes),
	'title' => elgg_echo('likes:see'),
	'class' => $class,
	'icon' => 'heart',
	'href' => '#',
	'data-likes-guid' => $entity->guid,
	'data-colorbox-opts' => json_encode([
		'maxHeight' => '85%',
		'href' => elgg_normalize_url("ajax/view/likes/popup?guid=$entity->guid")
	]),
]);
