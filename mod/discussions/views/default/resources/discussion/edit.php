<?php

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'discussion', true);

$topic = get_entity($guid);

elgg_push_entity_breadcrumbs($topic);

$body_vars = discussion_prepare_form_vars($topic);

echo elgg_view_page(elgg_echo('edit:object:discussion'), [
	'content' => elgg_view_form('discussion/save', [], $body_vars),
	'filter_id' => 'discussion/edit',
]);
