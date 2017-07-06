<?php
/**
 * Latest forum posts
 *
 * @uses $vars['entity']
 */

if ($vars['entity']->forum_enable == 'no') {
	return true;
}

$group = $vars['entity'];

$all_link = elgg_view('output/url', [
	'href' => "discussion/owner/$group->guid",
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);

elgg_push_context('widgets');
$options = [
	'type' => 'object',
	'subtype' => 'discussion',
	'container_guid' => $group->getGUID(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
	'no_results' => elgg_echo('discussion:none'),
];
$content = elgg_list_entities($options);
elgg_pop_context();

$new_link = elgg_view('output/url', [
	'href' => "discussion/add/" . $group->getGUID(),
	'text' => elgg_echo('discussion:addtopic'),
	'is_trusted' => true,
]);

echo elgg_view('groups/profile/module', [
	'title' => elgg_echo('discussion:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
]);
