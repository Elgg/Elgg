<?php
/**
 * JSON group view
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

echo json_encode($entity->toObject());
