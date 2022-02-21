<?php
/**
 * Render checkbox selector to select the ID of the item
 *
 * @uses $vars['item']      The item being rendered
 * @uses $vars['item_vars'] Vars received from the page/components/table view
 * @uses $vars['type']      The item type or ""
 * @uses $vars['name']      The name of the checkbox (default: item_id[])
 * @uses $vars['value']     The value of the checkbox (default to the GUID of an ElggEntity or the ID of an ElggExtender)
 */

$item = elgg_extract('item', $vars);
$default_value = false;
if ($item instanceof \ElggEntity) {
	$default_value = $item->guid;
} elseif ($item instanceof \ElggExtender) {
	$default_value = $item->id;
}

$value = elgg_extract('value', $vars, $default_value);
if ($value === false) {
	// no value to select
	return;
}

$name = elgg_extract('name', $vars, 'item_id[]');

echo elgg_view('input/checkbox', [
	'name' => $name,
	'value' => $value,
	'default' => false,
]);
