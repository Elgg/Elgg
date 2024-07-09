<?php

$group = elgg_get_page_owner_entity();

elgg_push_entity_breadcrumbs($group);

$content = elgg_view_form('groups/invite', [
	'id' => 'invite_to_group',
], [
	'entity' => $group,
]);

echo elgg_view_page(elgg_echo('groups:invite:title'), [
	'content' => $content,
	'filter_id' => 'groups/invite',
	'filter_value' => 'invite',
]);
