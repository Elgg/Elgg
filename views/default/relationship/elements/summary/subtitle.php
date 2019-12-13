<?php
/**
 * Output relationship subtitle
 *
 * @uses $vars['relationship'] the relationship
 * @uses $vars['subtitle']     subtitle (false for no subtitle, '' for default subtitle)
 */

$subtitle = elgg_extract('subtitle', $vars, '');
if ($subtitle === false) {
	return;
}

$relationship = elgg_extract('relationship', $vars);
if ($subtitle === '' && $relationship instanceof ElggRelationship) {
	$subtitle = elgg_view('object/elements/imprint/element', [
		'icon_name' =>'history',
		'content' => elgg_view_friendly_time($relationship->time_created),
		'class' => [
			'elgg-listing-time',
			'elgg-relationship-time', // @todo remove in 4.0
		],
	]);
	$subtitle = elgg_format_element('div', ['class' => 'elgg-listing-imprint'], $subtitle);
}

echo elgg_format_element('div', ['class' => [
	'elgg-relationship-subtitle', // @todo remove in 4.0
	'elgg-listing-summary-subtitle',
	'elgg-subtext',
]], $subtitle);
