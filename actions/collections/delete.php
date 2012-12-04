<?php
/**
 * Elgg delete a collection
 */

$entity_guid = (int) get_input('coll_entity_guid', 0, false);
$name = strip_tags(get_input('coll_name', ''));

$entity = get_entity($entity_guid);
if (!$entity) {
	register_error(elgg_echo("collection:could_not_load_container_entity"));
	forward(REFERER);
}

$coll = elgg_collections()->fetch($entity, $name);
if (!$coll || !$coll->canEdit()) {
	register_error(elgg_echo("collection:del:doesnt_exist_or_cant_edit"));
	forward(REFERER);
}

$coll->delete();

system_message(elgg_echo("collection:deleted"));
forward(REFERER);
