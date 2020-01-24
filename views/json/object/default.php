<?php
/**
 * JSON object view
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

echo json_encode($entity->toObject());
