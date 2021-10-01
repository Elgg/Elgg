<?php
/**
 * Outputs object metadata
 *
 * @uses $vars['metadata']         Metadata/menu
 * @uses $vars['entity']           Entity to show metadata for
 * @uses $vars['show_entity_menu'] Show the entity menu (default: true)
 * @uses $vars['show_social_menu'] Show the social menu (default: true)
 */

$metadata = elgg_extract('metadata', $vars);
if (!isset($metadata)) {
	$metadata = '';
	
	$entity = elgg_extract('entity', $vars);
	$show_entity_menu_default = true;
	if ($entity instanceof \ElggEntity) {
		$entity_url = $entity->getURL();
		if (!empty($entity_url) && stripos(current_page_url(), $entity_url) !== false) {
			// probably on a full view of an entity
			$show_entity_menu_default = false;
		}
	}
	
	$show_entity_menu = elgg_extract('show_entity_menu', $vars, $show_entity_menu_default);
	$show_social_menu = elgg_extract('show_social_menu', $vars, true);

	if ($show_entity_menu) {
		$metadata .= elgg_view_menu('entity', [
			'entity' => $entity,
			'handler' => elgg_extract('handler', $vars),
			'prepare_dropdown' => true,
		]);
	}
	
	if ($show_social_menu && !elgg_in_context('admin')) {
		$metadata .= elgg_view_menu('social', [
			'entity' => $entity,
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
