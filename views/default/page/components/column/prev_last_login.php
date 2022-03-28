<?php
/**
 * Render the prev last login timestamp
 *
 * @uses $vars['item']      The item being rendered
 * @uses $vars['item_vars'] Vars received from the page/components/table view
 * @uses $vars['type']      The item type or ""
 * @uses $vars['format']    Date format. Use "friendly" for output/friendlytime view.
 */

$entity = elgg_extract('item', $vars);
if (!$entity instanceof \ElggUser) {
	return;
}

$format = elgg_extract('format', $vars, DATE_RFC2822);

if ($format === 'friendly') {
	echo elgg_view('output/friendlytime', [
		'time' => $entity->prev_last_login,
	]);
	return;
}

echo elgg_view('output/date', [
	'value' => $entity->prev_last_login,
	'format' => $format,
]);
