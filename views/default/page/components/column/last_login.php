<?php
/**
 * Render the last login timestamp
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

$value = (int) $entity->last_login;
if (empty($value)) {
	echo elgg_echo('never');
	return;
}

$format = elgg_extract('format', $vars, DATE_RFC2822);

if ($format === 'friendly') {
	echo elgg_view('output/friendlytime', [
		'time' => $value,
	]);
	return;
}

echo elgg_view('output/date', [
	'value' => $value,
	'format' => $format,
]);
