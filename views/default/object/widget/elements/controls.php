<?php
/**
 * Elgg widget controls
 *
 * @uses $vars['widget']
 * @uses $vars['show_edit'] Whether to show the edit button (true)
 */

echo elgg_view_menu('widget', array(
	'entity' => elgg_extract('widget', $vars),
	'show_edit' => elgg_extract('show_edit', $vars, true),
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));
