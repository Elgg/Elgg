<?php

/**
 * Default object message
 *
 * @uses $vars['object'] River object
 */

$object = elgg_extract('object', $vars);
if (!$object instanceof ElggObject) {
	return;
}

if (!$object->description) {
	return;
}

echo $object->getExcerpt();