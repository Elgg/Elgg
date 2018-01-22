<?php

/**
 * Outputs object metadata
 * @uses $vars['metadata']         Metadata/menu
 * @uses $vars['show_entity_menu'] Show the entity menu (default: true)
 * @uses $vars['show_social_menu'] Show the social menu (default: true)
 */

$metadata = elgg_extract('metadata', $vars);
if (!isset($metadata)) {
	$metadata = '';
	
	$show_entity_menu = elgg_extract('show_entity_menu', $vars, true);
	$show_social_menu = elgg_extract('show_social_menu', $vars, true);

	if ($show_entity_menu) {
		$metadata .= elgg_view_menu('entity', [
			'entity' => elgg_extract('entity', $vars),
			'handler' => elgg_extract('handler', $vars),
		]);
	}
	
	if ($show_social_menu && !elgg_in_context('admin')) {
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
