<?php
/**
 * Group pages
 */

$group = elgg_extract('entity', $vars);
if (!($group instanceof \ElggGroup)) {
	return;
}

if (!$group->isToolEnabled('pages')) {
	return;
}

$all_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:object:page:group', ['guid' => $group->guid]),
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);

elgg_push_context('widgets');

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'page',
	'container_guid' => $group->guid,
	'metadata_name_value_pairs' => [
		'parent_guid' => 0,
	],
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
	'no_results' => elgg_echo('pages:none'),
]);

elgg_pop_context();

$new_link = null;
if ($group->canWriteToContainer(0, 'object', 'page')) {
	$new_link = elgg_view('output/url', [
		'href' => elgg_generate_url('add:object:page', ['guid' => $group->guid]),
		'text' => elgg_echo('add:object:page'),
		'is_trusted' => true,
	]);
}

echo elgg_view('groups/profile/module', [
	'title' => elgg_echo('collection:object:page:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
]);
