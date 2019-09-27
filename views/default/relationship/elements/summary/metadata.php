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
	]);
}

if (elgg_is_empty($metadata)) {
	return;
}

echo elgg_format_element('div', ['class' => [
	'elgg-relationship-metadata',
]], $metadata);
