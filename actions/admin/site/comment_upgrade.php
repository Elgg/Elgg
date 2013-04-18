<?php
/**
 * Convert comment annotations to entities
 */

$offset = get_input('offset', 0);
$limit = 10;

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$annotations = elgg_get_annotations(array(
	'annotation_names' => 'generic_comment',
	'limit' => $limit
));

$success_count = 0;
$error_count = 0;

if ($annotations) {
	$db_prefix = elgg_get_config('dbprefix');

	// Create a new object for each annotation
	foreach ($annotations as $annotation) {
		$object = new ElggComment();
		$object->owner_guid = $annotation->owner_guid;
		$object->container_guid = $annotation->entity_guid;
		$object->description = $annotation->value;
		$object->access_id = $annotation->access_id;
		$object->enabled = $annotation->enabled;
		$object->save();

		// We need to save once before being able to change time_created
		$object->time_created = $annotation->time_created;
		$object->save();

		$guid = $object->getGUID();

		if ($guid) {
			/**
			 * Update the entry in river table for this comment
			 *
			 * - Update the view path
			 * - Remove annotation id
			 * - Save comment guid to the target_guid column
			 */
			// TODO Use the original query once target_guid is available
			$query_original = "UPDATE {$db_prefix}river
				SET view='river/object/comment/create', annotation_id=0, target_guid=$guid
				WHERE action_type='comment' AND annotation_id={$annotation->id}";
			$query = "UPDATE {$db_prefix}river
				SET view='river/object/comment/create', annotation_id=0
				WHERE action_type='comment' AND annotation_id={$annotation->id}";

			if (update_data($query)) {
				// It's now safe to delete the annotation
				$annotation->delete();
				$success_count++;
			} else {
				$msg = elgg_echo('upgrade:comments:river_update_failed', array($annotation->id));
				register_error();
				$error_count++;
			}
		} else {
			register_error(elgg_echo('upgrade:comments:create_failed', array($annotation->id)));
			$error_count++;
		}
	}
}

access_show_hidden_entities($access_status);

// Give some feedback for the UI
$output = new stdClass();
$output->numSuccess = $success_count;
$output->numErrors = $error_count;
$output->newOffset = $offset + $limit;
echo json_encode($output);