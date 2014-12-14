<?php
/**
 * Convert discussion replies from annotations to entities
 *
 * Run for 2 seconds per request as set by $batch_run_time_in_secs. This includes
 * the engine loading time.
 */

// from engine/start.php
global $START_MICROTIME;
$batch_run_time_in_secs = 2;

if (get_input('upgrade_completed')) {
	// set the upgrade as completed
	$factory = new ElggUpgrade();
	$upgrade = $factory->getUpgradeFromPath('admin/upgrades/discussion_replies');
	if ($upgrade instanceof ElggUpgrade) {
		$upgrade->setCompleted();
	}

	return true;
}

// Offset is the total amount of errors so far. We skip these
// annotations to prevent them from possibly repeating the same error.
$offset = get_input('offset', 0);
$limit = 50;

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

// don't want any event or plugin hook handlers from plugins to run
$original_events = _elgg_services()->events;
$original_hooks = _elgg_services()->hooks;
_elgg_services()->events = new Elgg\EventsService();
_elgg_services()->hooks = new Elgg\PluginHooksService();
elgg_register_plugin_hook_handler('permissions_check', 'all', 'elgg_override_permissions');
elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'elgg_override_permissions');

$success_count = 0;
$error_count = 0;

do {
	$annotations_to_delete = array();
	$annotations = elgg_get_annotations(array(
		'annotation_names' => 'group_topic_post',
		'limit' => $limit,
		'offset' => $offset,
		'order_by' => 'n_table.id DESC',
	));

	if (!$annotations) {
		// no annotations left
		break;
	}

	$db_prefix = elgg_get_config('dbprefix');
	$container_guids = array();

	// Create a new object for each annotation
	foreach ($annotations as $annotation) {
		$object = new ElggDiscussionReply();
		$object->owner_guid = $annotation->owner_guid;
		$object->container_guid = $annotation->entity_guid;
		$object->description = $annotation->value;
		$object->access_id = $annotation->access_id;
		// make sure disabled replies stay disabled
		$object->enabled = $annotation->enabled;
		$object->time_created = $annotation->time_created;
		$object->save(false);

		$container_guids[] = $object->container_guid;

		$guid = $object->getGUID();

		if ($guid) {
			/**
			 * Update the entry in river table for this reply
			 *
			 * - Update the view path
			 * - Remove annotation id
			 * - Save reply guid to the object_guid column
			 */
			$query = "
				UPDATE {$db_prefix}river
				SET view = 'river/object/discussion_reply/create',
					type = 'object',
					subtype = 'discussion_reply',
					action_type = 'reply',
					annotation_id = 0,
					object_guid = $guid,
					target_guid = $object->container_guid
				WHERE action_type = 'reply'
				  AND annotation_id = $annotation->id
			";

			if (!update_data($query)) {
				register_error(elgg_echo('upgrade:river_update_failed', array($annotation->id)));
				$error_count++;
			}

			// set the time_updated and last_action for this reply
			// to the original time_created
			$fix_ts_query = "
				UPDATE {$db_prefix}entities
				SET time_updated = time_created,
					last_action = time_created
				WHERE guid = $guid
			";

			if (update_data($fix_ts_query)) {
				// It's now safe to delete the annotation
				$annotations_to_delete[] = $annotation->id;
				$success_count++;
			} else {
				register_error(elgg_echo('upgrade:timestamp_update_failed', array($annotation->id)));
				$error_count++;
			}

		} else {
			register_error(elgg_echo('discussion:upgrade:replies:create_failed', array($annotation->id)));
			$error_count++;
		}
	}

	if ($annotations_to_delete) {
		$annotation_ids = implode(",", $annotations_to_delete);
		$delete_query = "DELETE FROM {$db_prefix}annotations WHERE id IN ($annotation_ids)";
		delete_data($delete_query);
	}

	// update the last action on containers to be the max of all its replies
	// or its own last action
	$reply_subtype_id = get_subtype_id('object', 'discussion_reply');

	foreach (array_unique($container_guids) as $guid) {
		// can't use a subquery in an update clause without hard to read tricks.
		$max = get_data_row("SELECT MAX(time_updated) as max_time_updated
					FROM {$db_prefix}entities e
					WHERE e.container_guid = $guid
					AND e.subtype = $reply_subtype_id");

		$query = "
		UPDATE {$db_prefix}entities
			SET last_action = '$max->max_time_updated'
			WHERE guid = $guid
			AND last_action < '$max->max_time_updated'
		";

		update_data($query);
	}

} while ((microtime(true) - $START_MICROTIME) < $batch_run_time_in_secs);

access_show_hidden_entities($access_status);

// replace events and hooks
_elgg_services()->events = $original_events;
_elgg_services()->hooks = $original_hooks;

// Give some feedback for the UI
echo json_encode(array(
	'numSuccess' => $success_count,
	'numErrors' => $error_count,
));
