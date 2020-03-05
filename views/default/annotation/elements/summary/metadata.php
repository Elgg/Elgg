<?php
/**
 * Output annotation metadata
 *
 * @uses $vars['annotation'] ElggAnnotation
 * @uses $vars['metadata']   metadata (false for no metadata, '' for default metadata)
 */

$metadata = elgg_extract('metadata', $vars, '');
if ($metadata === false) {
	return;
}

$annotation = elgg_extract('annotation', $vars);
if ($metadata === '' && $annotation instanceof ElggAnnotation) {
	$metadata = elgg_view_menu('annotation', [
		'annotation' => $annotation,
		'class' => 'elgg-menu-hz',
		'prepare_dropdown' => true,
	]);
}

if (elgg_is_empty($metadata)) {
	return;
}

echo elgg_format_element('div', ['class' => [
	'elgg-listing-summary-metadata',
]], $metadata);
