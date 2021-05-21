<?php
/**
 * Edit a file
 */

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Exceptions\Http\EntityPermissionsException;

$file_guid = (int) elgg_extract('guid', $vars);

$file = get_entity($file_guid);

if (!$file instanceof ElggFile) {
	throw new EntityNotFoundException();
}

if (!$file->canEdit()) {
	throw new EntityPermissionsException();
}

elgg_push_entity_breadcrumbs($file);

$body_vars = file_prepare_form_vars($file);

$content = elgg_view_form('file/upload', [], $body_vars);

echo elgg_view_page(elgg_echo('edit:object:file'), [
	'content' => $content,
	'filter_id' => 'file/edit',
]);
