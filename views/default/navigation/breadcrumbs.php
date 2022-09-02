<?php
/**
 * Displays breadcrumbs menu.
 *
 * @uses $vars['class'] Optional class to add to the menu
 */

echo elgg_view_menu('breadcrumbs', [
	'sort_by' => 'register',
	'class' => elgg_extract_class($vars, ['elgg-breadcrumbs', 'elgg-menu-hz']),
]);
