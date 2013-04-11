<?php
/**
 * Elgg 1.9.0-dev upgrade 2013010400
 * comments_to_entities
 *
 * Convert comment annotations to entities
 */

// Register subtype and class for comments
update_subtype('object', 'comment', 'ElggComment');

$ia = elgg_set_ignore_access(true);
$batch = new ElggBatch('elgg_get_annotations', array(
	'annotation_names' => 'generic_comment',
	'limit' => false,
));
$batch->setIncrementOffset(false);

$db_prefix = elgg_get_config('dbprefix');

// Create a new object for each annotation
foreach ($batch as $annotation) {
	$object = new ElggComment();
	$object->owner_guid = $annotation->owner_guid;
	$object->container_guid = $annotation->entity_guid;
	$object->description = $annotation->value;
	$object->access_id = $annotation->access_id;
	$object->save();

	// We need to save once before setting time_created
    $object->time_created = $annotation->time_created;
	$object->save();

	$guid = $object->getGUID();

	/**
	 * Update the entry in river table for this comment
	 *
	 * - Update the view path
	 * - Remove annotation id
	 * - Save comment guid to the target_guid column
	 */
	$query = "UPDATE {$db_prefix}river
SET view='river/object/comment/create', annotation_id=0, target_guid=$guid
WHERE action_type='comment' AND annotation_id={$annotation->id}";
	update_data($query);

	// Delete the annotation
	$annotation->delete();
}
elgg_set_ignore_access($ia);