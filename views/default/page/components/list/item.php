<?php
/**
 * View a item to use in a list
 *
 * @uses $vars['item']       ElggEntity, ElggAnnotation, ElggRiverItem or ElggRelationship object
 * @uses $vars['item_class'] Additional CSS class for the <li> elements
 * @uses $vars['content']    Content of the list item
 */

$content = elgg_extract('content', $vars);
$item = elgg_extract('item', $vars);

$li_attrs = [
	'class' => elgg_extract_class($vars, 'elgg-item', 'item_class'),
];

if ($item instanceof \ElggEntity) {
	$guid = $item->guid;
	$type = $item->type;
	$subtype = $item->getSubtype();

	$li_attrs['id'] = "elgg-$type-$guid";

	$li_attrs['class'][] = "elgg-item-$type";
	if ($subtype) {
		$li_attrs['class'][] = "elgg-item-$type-$subtype";
	}
} elseif ($item instanceof \ElggRiverItem) {
	$type = $item->getType();

	$li_attrs['id'] = "item-$type-{$item->id}";
	
	$li_attrs['class'][] = "elgg-item-$type";
	
	$object = $item->getObjectEntity();
	if ($object instanceof \ElggEntity) {
		$li_attrs['class'][] = "elgg-item-{$type}-{$object->getType()}-{$object->getSubtype()}-{$item->action_type}";
	}
} elseif ($item instanceof ElggRelationship) {
	$type = $item->getType();
	$relationship = $item->getSubtype();
	$id = $item->id;
	
	$li_attrs['id'] = "elgg-{$type}-{$id}";
	
	$li_attrs['class'][] = "elgg-item-{$type}";
	$li_attrs['class'][] = "elgg-item-{$type}-{$relationship}";
} elseif (is_callable([$item, 'getType'])) {
	$type = $item->getType();

	$li_attrs['id'] = "item-$type-{$item->id}";
}

echo elgg_format_element('li', $li_attrs, $content);
