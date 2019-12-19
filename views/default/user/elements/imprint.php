<?php
/**
 * Displays the user imprint
 *
 * @uses $vars['entity'] The entity to show the imprint for
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggUser) {
	return;
}

$imprint = elgg_view('user/elements/imprint/contents', $vars);
if (elgg_is_empty($imprint)) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'elgg-listing-imprint',
], $imprint);
