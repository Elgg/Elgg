<?php
/**
 * Output an ElggMenuItem as a submit button
 *
 * @uses $vars['item'] the ElggMenuItem to display
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof \ElggMenuItem) {
	return;
}

unset($vars['item']);

$vars = array_merge($item->getValues(), $vars);
$vars['class'] = elgg_extract_class($vars, ['elgg-menu-content']);

if ($item->getLinkClass()) {
	$vars['class'] = array_merge($vars['class'], explode(' ', $item->getLinkClass()));
}

$href = elgg_extract('href', $vars);
if (!elgg_is_empty($href)) {
	// the href of the menu item is used as the custom formaction for the button
	$vars['formaction'] = $href;
}

unset($vars['href']);

if ($item->getConfirmText()) {
	$vars['confirm'] = $item->getConfirmText();
}

echo elgg_view('input/submit', $vars);
