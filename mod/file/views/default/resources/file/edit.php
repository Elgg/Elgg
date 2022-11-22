<?php
/**
 * Edit a file
 */

$file_guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($file_guid, 'object', 'file', true);

$file = get_entity($file_guid);

elgg_push_entity_breadcrumbs($file);

echo elgg_view_page(elgg_echo('edit:object:file'), [
	'content' => elgg_view_form('file/upload', ['sticky_enabled' => true], ['entity' => $file]),
	'filter_id' => 'file/edit',
]);
