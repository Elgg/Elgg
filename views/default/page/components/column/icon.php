<?php
/**
 * Render an icon
 *
 * @uses $vars['item']      The item being rendered
 * @uses $vars['item_vars'] Vars received from the page/components/table view
 * @uses $vars['type']      The item type or ""
 * @uses $vars['size']      The icon size
 */

$item = $vars['item'];
/* @var ElggEntity $item */

$size = elgg_extract('size', $vars, 'small');

echo elgg_view_entity_icon($item, $size);
