<?php

/**
 * Outputs object metadata
 * @uses $vars['metadata'] Metadata/menu
 */

$metadata = elgg_extract('metadata', $vars);
if (!isset($metadata)) {
	$metadata = '';

	$metadata .= elgg_view_menu('entity', [
		'entity' => elgg_extract('entity', $vars),
		'handler' => elgg_extract('handler', $vars),
	]);
	
	if (!elgg_in_context('admin')) {
		$metadata .= elgg_view_menu('social', [
			'entity' => elgg_extract('entity', $vars),
			'handler' => elgg_extract('handler', $vars),
			'class' => 'elgg-menu-hz',
		]);
	}
}

if (!$metadata) {
	return;
}

echo elgg_format_element('div', [
	'class' => [
		'elgg-listing-summary-metadata',
	]
], $metadata);
