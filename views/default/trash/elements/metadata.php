<?php
/**
 * Outputs object metadata
 *
 * @uses $vars['metadata']         Metadata/menu
 * @uses $vars['entity']           Entity to show metadata for
 * @uses $vars['show_trash_menu'] Show the entity:trash menu (default: true)
 */

$metadata = elgg_extract('metadata', $vars);
if (!isset($metadata)) {
	$metadata = '';
	
	$entity = elgg_extract('entity', $vars);
	
	$show_entity_menu = elgg_extract('show_trash_menu', $vars, true);
	if ($show_entity_menu) {
		$metadata .= elgg_view_menu('entity:trash', [
			'entity' => $entity,
			'prepare_dropdown' => true,
		]);
	}
}

if (!$metadata) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'elgg-listing-summary-metadata'
], $metadata);
