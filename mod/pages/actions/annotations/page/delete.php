<?php
/**
 * Remove a page (revision) annotation
 *
 * @package ElggPages
 */

// Make sure we can get the annotations and entity in question
$annotation_id = (int) get_input('annotation_id');
$annotation = elgg_get_annotation_from_id($annotation_id);
if ($annotation) {
	$entity = get_entity($annotation->entity_guid);
	if (pages_is_page($entity) && $entity->canEdit() && $annotation->canEdit()) {
		$annotation->delete();
		system_message(elgg_echo("pages:revision:delete:success"));
		forward("pages/history/{$annotation->entity_guid}");
	}
}
register_error(elgg_echo("pages:revision:delete:failure"));
forward(REFERER);
