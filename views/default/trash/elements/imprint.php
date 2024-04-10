<?php
/**
 * Displays information about the author and the time of deletion
 *
 * @uses $vars['entity'] The entity to show the imprint for
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$imprint = elgg_view('trash/elements/imprint/contents', $vars);
if (elgg_is_empty($imprint)) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'elgg-listing-imprint',
], $imprint);
