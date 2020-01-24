<?php
/**
 * Output annotation subtitle
 *
 * @uses $vars['annotation'] ElggAnnotation
 * @uses $vars['subtitle']   Subtitle (false for no subtitle, '' for default subtitle)
 */

$subtitle = elgg_extract('subtitle', $vars, '');
if ($subtitle === false) {
	return;
}

if ($subtitle === '') {
	$subtitle = elgg_view('annotation/elements/imprint', $vars);
}

if (elgg_is_empty($subtitle)) {
	return;
}

echo elgg_format_element('div', ['class' => [
	'elgg-listing-summary-subtitle',
	'elgg-subtext',
]], $subtitle);
