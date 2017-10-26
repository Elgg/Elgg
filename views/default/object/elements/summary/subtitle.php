<?php
/**
 * Outputs object subtitle
 * @uses $vars['subtitle'] Subtitle
 */
$subtitle = elgg_extract('subtitle', $vars);
if (!isset($subtitle)) {
	$subtitle = elgg_view('object/elements/imprint', $vars);
}
if (!$subtitle) {
	return;
}

echo elgg_format_element('div', [
	'class' => [
		'elgg-listing-summary-subtitle',
		'elgg-subtext',
	]
], $subtitle);
