<?php
/**
 * Topic save action
 */

$container_guid = (int) get_input('container_guid');
$guid = (int) get_input('topic_guid');

$container = get_entity($container_guid);
if (!$container || !$container->canWriteToContainer(0, 'object', 'discussion')) {
	return elgg_error_response(elgg_echo('discussion:error:permissions'));
}

$values = [];
$fields = elgg()->fields->get('object', 'discussion');
foreach ($fields as $field) {
	$name = (string) elgg_extract('name', $field);
	if (elgg_extract('#type', $field) === 'tags') {
		$value = elgg_string_to_array((string) get_input($name));
	} elseif ($name === 'title') {
		$value = elgg_get_title_input();
	} else {
		$value = get_input($name);
	}
	
	if ($name === 'access_id' && $container instanceof \ElggGroup && $value === null) {
		// access is null when a group is selected from the container_guid select
		$acl = $container->getOwnedAccessCollection('group_acl');
		if ($acl instanceof \ElggAccessCollection) {
			$value = $acl->getID();
		}
	}
	
	if (elgg_extract('required', $field) && elgg_is_empty($value)) {
		return elgg_error_response(elgg_echo('discussion:error:missing'));
	}
	
	$values[$name] = $value;
}

// check whether this is a new topic or an edit
$new_topic = empty($guid);

if ($new_topic) {
	$topic = new \ElggDiscussion();
} else {
	$topic = get_entity($guid);
	if (!$topic instanceof \ElggDiscussion || !$topic->canEdit()) {
		return elgg_error_response(elgg_echo('discussion:topic:notfound'));
	}
}

$topic->container_guid = $container_guid;

foreach ($values as $name => $value) {
	$topic->{$name} = $value;
}

if (!$topic->save()) {
	return elgg_error_response(elgg_echo('discussion:error:notsaved'));
}

// handle results differently for new topics and topic edits
if (!$new_topic) {
	return elgg_ok_response('', elgg_echo('discussion:topic:updated'), $topic->getURL());
}

elgg_create_river_item([
	'action_type' => 'create',
	'object_guid' => $topic->guid,
	'target_guid' => $topic->container_guid,
]);

return elgg_ok_response('', elgg_echo('discussion:topic:created'), $topic->getURL());
