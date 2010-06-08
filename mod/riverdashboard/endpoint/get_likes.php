<?php
/**
 * Grabs more "likes" to display.
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");

$limit = get_input('limit', 25);
$offset = get_input('offset', 0);
$entity_guid = get_input('entity_guid');

if (!$entity = get_entity($entity_guid)) {
	exit;
}

$annotations = $entity->getAnnotations('likes', $limit, $offset);

if (is_array($annotations) && sizeof($annotations) > 0) {
	foreach($annotations as $annotation) {
		echo elgg_view_annotation($annotation, "", false);
	}
}
