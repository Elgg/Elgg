<?php
/**
 * Item view for generating HTML of an entity in an input/autocomplete (or input/userpicker)
 *
 * @uses $vars['entity'] the selected entity
 * @uses $vars['input_name'] name of the returned data array
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$type = $entity->getType();
$subtype = $entity->getSubtype();

$views = [
	"input/autocomplete/{$type}/{$subtype}",
	"input/autocomplete/{$type}/default",
	"input/autocomplete/default",
];
foreach ($views as $view) {
	if (elgg_view_exists($view)) {
		echo elgg_view($view, $vars);
		return;
	}
}

elgg_log("No autocomplete item view could be found for {$entity->guid}", 'WARNING');
