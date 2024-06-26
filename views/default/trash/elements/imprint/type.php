<?php
/**
 * Display information about the type of the deleted entity
 *
 * @uses $vars['entity']    the entity to show information for
 * @uses $vars['type']      type information, if not set will be generated by the system
 * @uses $vars['type_icon'] type imprint icon (default: none)
 */

$type_text = elgg_extract('type', $vars);
if (!isset($type_text)) {
	$entity = elgg_extract('entity', $vars);
	if ($entity instanceof \ElggEntity) {
		$type_text = ucwords($entity->getSubtype());
		$lan_keys = [
			"item:{$entity->getType()}:{$entity->getSubtype()}",
			"collection:{$entity->getType()}:{$entity->getSubtype()}",
		];
		foreach ($lan_keys as $key) {
			if (!elgg_language_key_exists($key)) {
				continue;
			}
			
			$type_text = elgg_echo($key);
			break;
		}
		
		$type_text = elgg_echo('trash:imprint:type', [$type_text]);
	}
}

if (elgg_is_empty($type_text)) {
	return;
}

echo elgg_view('trash/elements/imprint/element', [
	'icon_name' => elgg_extract('type_icon', $vars, false),
	'content' => $type_text,
	'class' => 'elgg-listing-type',
]);
