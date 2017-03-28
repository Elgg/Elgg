<?php
/**
 * Displays breadcrumbs.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['breadcrumbs'] Breadcrumbs
 *                            If not set, will use breadcrumbs from the stack
 *                            pushed by elgg_push_breadcrumb()
 *                            <code>
 *                            [
 *                               [
 *                                  'title' => 'Breadcrumb title',
 *                                  'link' => '/path/to/page',
 *                               ],
 *                            ]
 *                            </code>
 * @uses $vars['class']       Optional class to add to the wrapping <ul>
 *
 * @see elgg_push_breadcrumb
 * @see elgg_get_breadcrumbs
 */

$breadcrumbs = elgg_extract('breadcrumbs', $vars);
unset($vars['breadcrumbs']);

$breadcrumbs = elgg_get_breadcrumbs($breadcrumbs);

if (!is_array($breadcrumbs) || empty($breadcrumbs)) {
	return;
}

$vars['class'] = elgg_extract_class($vars, ['elgg-menu', 'elgg-menu-hz', 'elgg-breadcrumbs', 'breadcrumb', 'flex-wrap']);

foreach ($breadcrumbs as $key => &$breadcrumb) {

	$breadcrumb['name'] = "breadcrumb$key";

	if (!isset($breadcrumb['href']) && isset($breadcrumb['link'])) {
		$breadcrumb['href'] = $breadcrumb['link'];
		unset($breadcrumb['link']);
	}

	if (!isset($breadcrumb['text']) && isset($breadcrumb['title'])) {
		$breadcrumb['text'] = $breadcrumb['title'];
		unset($breadcrumb['title']);
	}

	if (empty($breadcrumb['href'])) {
		$breadcrumb['link_class'] = 'active';
	}

}

$params = $vars;
$params['items'] = $breadcrumbs;
$params['item_class'] = 'breadcrumb-item';
$params['sort_by'] = 'priority';

echo elgg_view_menu('breadcrumbs', $params);