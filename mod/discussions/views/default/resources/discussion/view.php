<?php

$guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'discussion');

$topic = get_entity($guid);

elgg_push_entity_breadcrumbs($topic);

$content = elgg_view_entity($topic);

echo elgg_view_page($topic->getDisplayName(), [
	'content' => $content,
	'entity' => $topic,
	'filter_id' => 'discussion/view',
]);
