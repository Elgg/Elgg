<?php
/**
 * Render an owner element
 *
 * @uses $vars['item']      The item being rendered
 * @uses $vars['item_vars'] Vars received from the page/components/table view
 * @uses $vars['type']      The item type or ""
 * @uses $vars['link']      Set to false to not use an anchor
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggEntity) {
	return;
}

$link = elgg_extract('link', $vars, true);

$entity = $item->getOwnerEntity();
if (!$entity) {
	return;
}

echo $link ? elgg_view_entity_url($entity) : $entity->getDisplayName();
