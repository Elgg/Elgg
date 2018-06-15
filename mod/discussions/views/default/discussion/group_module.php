<?php
/**
 * Latest forum posts
 *
 * @uses $vars['entity']
 */

$group = elgg_extract('entity', $vars);
if (!($group instanceof \ElggGroup)) {
	return;
}

if (!$group->isToolEnabled('forum')) {
	return;
}

$all_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:object:discussion:group', ['guid' => $group->guid]),
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);

elgg_push_context('widgets');
$options = [
	'type' => 'object',
	'subtype' => 'discussion',
	'container_guid' => $group->guid,
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
	'no_results' => elgg_echo('discussion:none'),
];
$content = elgg_list_entities($options);
elgg_pop_context();

$new_link = null;
if ($group->canWriteToContainer(0, 'object', 'discussion')) {
	$new_link = elgg_view('output/url', [
		'href' => elgg_generate_url('add:object:discussion', ['guid' => $group->guid]),
		'text' => elgg_echo('add:object:discussion'),
		'is_trusted' => true,
	]);
}

echo elgg_view('groups/profile/module', [
	'title' => elgg_echo('collection:object:discussion:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
]);
