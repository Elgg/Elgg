<?php
/**
 * Edit a file
 */

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', 'file', true);

$file = get_entity($guid);

elgg_push_entity_breadcrumbs($file);

echo elgg_view_page(elgg_echo('edit:object:file'), [
	'content' => elgg_view_form('file/upload', ['sticky_enabled' => true], ['entity' => $file]),
	'filter_id' => 'file/edit',
]);
