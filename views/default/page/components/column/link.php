<?php
/**
 * Render a linked title
 *
 * @uses $vars['item']      The item being rendered
 * @uses $vars['item_vars'] Vars received from the page/components/table view
 * @uses $vars['type']      The item type or ""
 */

$entity = elgg_extract('item', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

echo elgg_view_entity_url($entity);
