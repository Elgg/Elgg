<?php
/**
 * Remove a page (revision) annotation
 *
 * @package ElggPages
 */

// Make sure we can get the annotations and entity in question
$annotation_id = (int) get_input('annotation_id');
$annotation = elgg_get_annotation_from_id($annotation_id);
$entity = get_entity($annotation->entity_guid);

if ($annotation && $entity->canEdit() && $annotation->canEdit()) {
	$annotation->delete();
	system_message(elgg_echo("pages:revision:delete:success"));
} else {
	register_error(elgg_echo("pages:revision:delete:failure"));
}

forward("pages/history/{$annotation->entity_guid}");