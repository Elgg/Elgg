<?php
/**
 * Output an ElggMenuItem as an url
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

if ($item->getHref() === false || $item->getHref() === null) {
	$vars['class'][] = 'elgg-non-link';
}

if (!isset($vars['rel']) && !isset($vars['is_trusted'])) {
	$vars['is_trusted'] = true;
}

if ($item->getConfirmText()) {
	$vars['confirm'] = $item->getConfirmText();
}

if (isset($vars['badge']) && is_numeric($vars['badge'])) {
	$vars['badge'] = \Elgg\Values::shortFormatOutput($vars['badge'], 1);
}

echo elgg_view('output/url', $vars);
