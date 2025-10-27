<?php

$guid = (int) elgg_extract('guid', $vars);
$entity = elgg_entity_gatekeeper($guid, 'object', 'thewire');

elgg_push_entity_breadcrumbs($entity);

$content = elgg_view('thewire/reply', ['post' => $entity]);

$content .= elgg_view_form('thewire/add', [
	'class' => 'thewire-form',
], [
	'post' => $entity,
]);

echo elgg_view_page(elgg_echo('reply'), [
	'content' => $content,
	'filter_id' => 'thewire/edit',
	'filter_value' => 'reply',
]);
