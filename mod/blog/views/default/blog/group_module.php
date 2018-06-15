<?php
/**
 * Group blog module
 */

$group = elgg_extract('entity', $vars);
if (!($group instanceof \ElggGroup)) {
	return;
}

if (!$group->isToolEnabled('blog')) {
	return;
}

$all_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:object:blog:group', [
		'guid' => $group->guid,
		'subpage' => 'all',
	]),
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);

elgg_push_context('widgets');
$options = [
	'type' => 'object',
	'subtype' => 'blog',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
	'no_results' => elgg_echo('blog:none'),
	'distinct' => false,
];
$content = elgg_list_entities($options);
elgg_pop_context();

$new_link = null;
if ($group->canWriteToContainer(0, 'object', 'blog')) {
	$new_link = elgg_view('output/url', [
		'href' => elgg_generate_url('add:object:blog', [
			'guid' => $group->guid,
		]),
		'text' => elgg_echo('blog:write'),
		'is_trusted' => true,
	]);
}

echo elgg_view('groups/profile/module', [
	'title' => elgg_echo('collection:object:blog:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
]);
