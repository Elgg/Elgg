<?php
/**
 * Elgg rearrange items in a collection
 */

$entity_guid = (int) get_input('coll_entity_guid', 0, false);
$name = get_input('coll_name', '', false);
$items_before = get_input('guids_before', array(), false);
$items_after = get_input('guids_after', array(), false);

// sanity check input
if ($entity_guid < 1 || !is_string($name) || !$name || !is_array($items_before) || !is_array($items_after)) {
	register_error(elgg_echo("collection:rearrange:invalid_input"));
	forward(REFERER);
}
$items_before = array_map('intval', $items_before);
$items_after = array_map('intval', $items_after);


$entity = get_entity($entity_guid);
if (!$entity) {
	register_error(elgg_echo("collection:could_not_load_container_entity"));
	forward(REFERER);
}

$coll = elgg_get_collection($entity, $name);
if (!$coll) {
	register_error(elgg_echo("collection:cant_see_collection"));
	forward(REFERER);
}

if (!$coll->canEdit()) {
	register_error(elgg_echo("collection:cant_edit"));
	forward(REFERER);
}

$accessor = $coll->getAccessor();

if ($accessor->rearrange($items_before, $items_after)) {
	system_message(elgg_echo("collection:rearrange:success"));
	forward(REFERER);
}

register_error(elgg_echo("collection:rearrange:failed"));
forward(REFERER);
