<?php
/**
 * Group pages
 */

$group = elgg_get_page_owner_entity();
if (!$group instanceof ElggGroup || $group->pages_enable === 'no') {
	return;
}

$all_link = elgg_view('output/url', [
	'href' => "pages/group/{$group->guid}/all",
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);

elgg_push_context('widgets');

$content = elgg_list_entities_from_metadata([
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

$new_link = elgg_view('output/url', [
	'href' => "pages/add/{$group->guid}",
	'text' => elgg_echo('pages:add'),
	'is_trusted' => true,
]);

echo elgg_view('groups/profile/module', [
	'title' => elgg_echo('pages:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
]);
