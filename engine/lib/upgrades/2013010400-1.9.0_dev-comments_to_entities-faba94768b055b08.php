<?php
/**
 * Elgg 1.9.0-dev upgrade 2013010400
 * comments_to_entities
 *
 * Convert comment annotations to entities
 * 
 * @warning we do not migrate disabled comments in this upgrade. See the comment
 * upgrade action in actions/admin/upgrades/upgrade_comments.php for that.
 */

// Register subtype and class for comments
if (get_subtype_id('object', 'comment')) {
	update_subtype('object', 'comment', 'ElggComment');
} else {
	add_subtype('object', 'comment', 'ElggComment');
}

$ia = elgg_set_ignore_access(true);

// upgrade latest 50 comments from annotations.
$batch = new ElggBatch('elgg_get_annotations', array(
	'annotation_names' => 'generic_comment',
	'order_by' => 'n_table.id DESC',
	'limit' => 50,
));
$batch->setIncrementOffset(false);

// don't want any event or plugin hook handlers from plugins to run
$original_events = _elgg_services()->events;
$original_hooks = _elgg_services()->hooks;
_elgg_services()->events = new Elgg_EventsService();
_elgg_services()->hooks = new Elgg_PluginHooksService();
elgg_register_plugin_hook_handler('permissions_check', 'all', 'elgg_override_permissions');
elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'elgg_override_permissions');

$db_prefix = elgg_get_config('dbprefix');
$new_comment_guids = array();
$container_guids = array();

// Create a new object for each annotation
foreach ($batch as $annotation) {
	/* @var ElggAnnotation $annotation */

	$object = new ElggComment();
	$object->owner_guid = $annotation->owner_guid;
	$object->container_guid = $annotation->entity_guid;
	$object->description = $annotation->value;
	$object->access_id = $annotation->access_id;
	$object->time_created = $annotation->time_created;
	$object->save(false);

	$guid = $object->getGUID();
	$new_comment_guids[] = $guid;
	$container_guids[] = $object->container_guid;

	/**
	 * Update the entry in river table for this comment
	 *
	 * - Update the view path
	 * - Remove annotation id
	 * - Save comment guid to the target_guid column
	 */
	$query = "
		UPDATE {$db_prefix}river
		SET view = 'river/object/comment/create',
			type = 'object',
			subtype = 'comment',
			annotation_id = 0,
			object_guid = $guid,
			target_guid = {$object->container_guid}
		WHERE action_type = 'comment'
		  AND annotation_id = {$annotation->id}
	";

	// Delete the annotation
	$annotation->delete();
}

// replace events and hooks
_elgg_services()->events = $original_events;
_elgg_services()->hooks = $original_hooks;

elgg_set_ignore_access($ia);


// set new comment entities' time_updated and last_action to time_created
// this is not exposed through the API.
$guid_str = implode(',', $new_comment_guids);

$query = "
UPDATE {$db_prefix}entities
	SET time_updated = time_created,
		last_action = time_created
	WHERE guid IN ($guid_str)
";

update_data($query);


// update the last action on containers to be the max of all its comments
// or its own last action
$comment_subtype_id = get_subtype_id('object', 'comment');

foreach (array_unique($container_guids) as $guid) {
	// can't use a subquery in an update clause without hard to read tricks.
	$max = get_data_row("SELECT MAX(time_updated) as max_time_updated
				FROM {$db_prefix}entities e
				WHERE e.container_guid = $guid
				AND e.subtype = $comment_subtype_id");

	$query = "
	UPDATE {$db_prefix}entities
		SET last_action = '$max->max_time_updated'
		WHERE guid = $guid
		AND last_action < '$max->max_time_updated'
	";

	update_data($query);
}

// display notice to run ajax upgrade if there are annotations left
$options = array(
	'annotation_names' => 'generic_comment',
	'order_by' => 'n_table.id DESC',
	'limit' => 50,
	'count' => true
);

if (elgg_get_annotations($options)) {
	$migrate_link = elgg_view('output/url', array(
		'href' => 'admin/upgrades/comments',
		'text' => "migrate the rest of the comments",
		'is_trusted' => true,
	));
	
	// not using translation because new keys won't be in the cache
	elgg_add_admin_notice('comment_upgrade_needed', "The data structure of site comments has changed in Elgg 1.9. The most recent 50 comments were migrated but you must $migrate_link.");
}