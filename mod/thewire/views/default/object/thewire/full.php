<?php
/**
 * View a wire post
 *
 * @uses $vars['entity'] ElggWire to show
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggWire) {
	return;
}

$params = [
	'access' => false,
	'body' => elgg_format_html((string) $entity->description, [
		'sanitize' => false,
		'autop' => false,
		'parse_thewire_hashtags' => true,
	]),
	'icon_entity' => $entity->getOwnerEntity(),
	'show_summary' => true,
	'tags' => false,
	'title' => false,
];

$params = $params + $vars;
echo elgg_view('object/elements/full', $params);
