<?php
/**
 * Group file module
 */

$group = elgg_extract('entity', $vars);
if (!($group instanceof \ElggGroup)) {
	return;
}

if (!$group->isToolEnabled('file')) {
	return;
}

$all_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:object:file:group', ['guid' => $group->guid]),
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);

elgg_push_context('widgets');
$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'file',
	'container_guid' => $group->guid,
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
	'no_results' => elgg_echo('file:none'),
	'distinct' => false,
]);
elgg_pop_context();

$new_link = null;
if ($group->canWriteToContainer(0, 'object', 'file')) {
	$new_link = elgg_view('output/url', [
		'href' => elgg_generate_url('add:object:file', ['guid' => $group->guid]),
		'text' => elgg_echo('add:object:file'),
		'is_trusted' => true,
	]);
}

echo elgg_view('groups/profile/module', [
	'title' => elgg_echo('collection:object:file:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
]);
