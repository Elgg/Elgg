<?php
/**
 * Elgg likes button
 *
 * @uses $vars['entity']
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

// check to see if the user has already liked this
if (!elgg_is_logged_in() || !$entity->canAnnotate(0, 'likes')) {
	return;
}

if (!elgg_annotation_exists($guid, 'likes')) {
	echo elgg_view('output/url', [
		'href' => elgg_generate_action_url('likes/add', [
			'guid' => $entity->guid,
		]),
		'text' => elgg_view_icon('thumbs-up'),
		'title' => elgg_echo('likes:likethis'),
		'is_trusted' => true,
	]);
	return;
}

echo elgg_view('output/url', [
	'href' => elgg_generate_action_url('likes/delete', [
		'guid' => $entity->guid,
	]),
	'text' => elgg_view_icon('thumbs-up-alt'),
	'title' => elgg_echo('likes:remove'),
	'is_trusted' => true,
]);
