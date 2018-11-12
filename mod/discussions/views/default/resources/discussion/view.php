<?php

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'discussion');

$topic = get_entity($guid);

elgg_push_entity_breadcrumbs($topic, false);

$content = elgg_view_entity($topic, [
	'full_view' => true,
	'show_responses' => true,
]);

$title = $topic->getDisplayName();

$params = [
	'content' => $content,
	'title' => $title,
	'entity' => $topic,
	'sidebar' => elgg_view('discussion/sidebar'),
	'filter' => '',
];
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
