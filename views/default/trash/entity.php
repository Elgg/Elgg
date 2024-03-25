<?php
/**
 * Entity view of an item in the trash
 *
 * @uses $vars['entity'] the entity
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$views = [
	"trash/{$entity->getType()}/{$entity->getSubtype()}",
	"trash/{$entity->getType()}/default",
	'trash/entity/default',
];

foreach ($views as $view) {
	if (!elgg_view_exists($view)) {
		continue;
	}
	
	echo elgg_view($view, $vars);
	break;
}
