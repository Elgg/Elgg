<?php
/**
 * ElggObject default view.
 *
 * @warning This view may be used for other ElggEntity objects
 *
 * @package Elgg
 * @subpackage Core
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

if (!isset($vars['icon'])) {
	$vars['icon'] = true;
}

if (!isset($vars['title']) && empty($entity->getDisplayName())) {
	$vars['title'] = get_class($entity);
}

echo elgg_view('object/elements/summary', $vars);
