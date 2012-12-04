<?php
/**
 * Elgg add item to collection
 */

$entity_guid = (int) get_input('coll_entity_guid', 0, false);
$name = strip_tags(get_input('coll_name', ''));
$item_guid = (int) get_input('item_guid', 0, false);

$entity = get_entity($entity_guid);
if (!$entity) {
	register_error(elgg_echo("collection:could_not_load_container_entity"));
	forward(REFERER);
}

$coll = elgg_collections()->create($entity, $name);
if (!$coll || !$coll->canEdit()) {
	register_error(elgg_echo("collection:add:cannot_create_or_edit"));
	forward(REFERER);
}

if (!elgg_entity_exists($item_guid)) {
	register_error(elgg_echo("collection:add:item_nonexistant"));
	forward(REFERER);
}

$accessor = $coll->getAccessor();

if ($accessor->hasAnyOf($item_guid)) {
	system_message(elgg_echo("collection:add:already_in_collection"));
	forward(REFERER);
}

$accessor->push($item_guid);

system_message(elgg_echo("collection:add:item_added"));
forward(REFERER);
