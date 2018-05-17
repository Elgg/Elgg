<?php

/**
 * Wire river message
 */

$object = elgg_extract('object', $vars);
if (!$object instanceof ElggWire) {
	return;
}

echo thewire_filter($object->description);