<?php
/**
 * Output relationship metadata
 *
 * @uses $vars['relationship'] the relationship
 * @uses $vars['metadata']     metadata (false for no metadata, '' for default metadata)
 */

$metadata = elgg_extract('metadata', $vars, '');
if ($metadata === false) {
	return;
}

$relationship = elgg_extract('relationship', $vars);
if ($metadata === '' && $relationship instanceof ElggRelationship) {
	$metadata = elgg_view_menu('relationship', [
		'relationship' => $relationship,
		'class' => 'elgg-menu-hz',
	]);
}

if (elgg_is_empty($metadata)) {
	return;
}

echo elgg_format_element('div', ['class' => [
	'elgg-listing-summary-metadata',
	'elgg-relationship-metadata', // @todo remove in 4.0
]], $metadata);
