<?php
/**
 * Elgg widget controls
 *
 * @uses $vars['widget']
 * @uses $vars['show_edit'] Whether to show the edit button (true)
 */

echo elgg_view_menu('widget', [
	'entity' => elgg_extract('widget', $vars),
	'show_edit' => elgg_extract('show_edit', $vars, true),
	'class' => 'elgg-menu-hz',
]);
