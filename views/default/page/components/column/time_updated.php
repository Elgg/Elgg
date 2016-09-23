<?php
/**
 * Render the time updated
 *
 * @uses $vars['item']      The item being rendered
 * @uses $vars['item_vars'] Vars received from the page/components/table view
 * @uses $vars['type']      The item type or ""
 * @uses $vars['format']    Date format. Use "friendly" for output/friendlytime view.
 */

$entity = $vars['item'];
/* @var ElggEntity $entity */

$format = elgg_extract('format', $vars, 'M d, Y H:i');

if ($format === 'friendly') {
	echo elgg_view('output/friendlytime', [
		'time' => $entity->time_updated,
	]);
	return;
}

echo date($format, $entity->time_updated);
