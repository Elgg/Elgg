<?php
/**
 * Displays breadcrumbs.
 *
 * @uses $vars['breadcrumbs'] Breadcrumbs
 *                            If not set, will use breadcrumbs from the stack
 *                            pushed by elgg_push_breadcrumb()
 *                            <code>
 *                            [
 *                               [
 *                                  'text' => 'Breadcrumb title',
 *                                  'href' => '/path/to/page',
 *                               ],
 *                            ]
 *                            </code>
 * @uses $vars['class']       Optional class to add to the menu
 *
 * @see elgg_push_breadcrumb
 * @see elgg_get_breadcrumbs
 */

$breadcrumbs = elgg_extract('breadcrumbs', $vars);
$breadcrumbs = elgg_get_breadcrumbs($breadcrumbs);

echo elgg_view_menu('breadcrumbs', [
	'items' => $breadcrumbs,
	'sort_by' => 'register',
	'class' => elgg_extract_class($vars, ['elgg-breadcrumbs', 'elgg-menu-hz']),
]);
