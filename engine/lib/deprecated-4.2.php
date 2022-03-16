<?php
/**
 * Bundle all functions which have been deprecated in Elgg 4.2
 */

/**
 * Render a menu item (usually as a link)
 *
 * @param \ElggMenuItem $item The menu item
 * @param array         $vars Options to pass to output/url if a link
 *
 * @return string
 * @since 1.9.0
 * @deprecated 4.2 use elgg_view('navigation/menu/elements/item/url')
 */
function elgg_view_menu_item(\ElggMenuItem $item, array $vars = []) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated use elgg_view("navigation/menu/elements/item/url")', '4.2');
	
	$vars = array_merge($item->getValues(), $vars);
	$vars['class'] = elgg_extract_class($vars, ['elgg-menu-content']);
	
	if ($item->getLinkClass()) {
		$vars['class'][] = $item->getLinkClass();
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
	
	return elgg_view('output/url', $vars);
}
