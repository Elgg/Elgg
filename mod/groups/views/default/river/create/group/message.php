<?php

$object = elgg_extract('object', $vars);
if (!$object instanceof ElggGroup) {
	return;
}

if (!$object->description) {
	return;
}

echo elgg_get_excerpt($object->description);