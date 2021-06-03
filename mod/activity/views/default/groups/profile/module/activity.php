<?php
/**
 * Groups latest activity
 */

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
	return;
}

$all_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:river:group', [
		'guid' => $group->guid,
	]),
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);

elgg_push_context('widgets');

$content = elgg_view('river/listing/group', [
	'entity' => $group,
	'options' => [
		'limit' => 4,
		'pagination' => false,
	],
]);
elgg_pop_context();

echo elgg_view('groups/profile/module', [
	'title' => elgg_echo('collection:river:group'),
	'content' => $content,
	'all_link' => $all_link,
]);
