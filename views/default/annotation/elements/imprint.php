<?php
/**
 * Displays the annotation imprint
 *
 * @uses $vars['annotation'] The annotation
 */

$annotation = elgg_extract('annotation', $vars);
if (!$annotation instanceof ElggAnnotation) {
	return;
}

$imprint = elgg_view('annotation/elements/imprint/contents', $vars);
if (elgg_is_empty($imprint)) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'elgg-listing-imprint',
], $imprint);
