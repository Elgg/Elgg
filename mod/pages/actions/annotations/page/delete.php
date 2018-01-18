<?php
/**
 * Remove a page (revision) annotation
 */

// Make sure we can get the annotations and entity in question
$annotation_id = (int) get_input('annotation_id');
$annotation = elgg_get_annotation_from_id($annotation_id);
if (!$annotation) {
	return elgg_error_response(elgg_echo('pages:revision:delete:failure'));
}

$entity = $annotation->getEntity();
if (!$entity instanceof ElggPage || !$entity->canEdit() || !$annotation->canEdit()) {
	return elgg_error_response(elgg_echo('pages:revision:delete:failure'));
}

if (!$annotation->delete()) {
	return elgg_error_response(elgg_echo('pages:revision:delete:failure'));
}

return elgg_ok_response(
	'',
	elgg_echo('pages:revision:delete:success'),
	elgg_generate_url('history:object:page', ['guid' => $entity->guid])
);
