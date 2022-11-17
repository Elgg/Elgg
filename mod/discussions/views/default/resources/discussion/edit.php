<?php

$guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'discussion', true);

$discussion = get_entity($guid);

elgg_push_entity_breadcrumbs($discussion);

echo elgg_view_page(elgg_echo('edit:object:discussion'), [
	'content' => elgg_view_form('discussion/save', ['sticky_enabled' => true], ['entity' => $discussion]),
	'filter_id' => 'discussion/edit',
]);
