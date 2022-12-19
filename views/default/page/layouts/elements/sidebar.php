<?php
/**
 * Layout sidebar
 *
 * @uses $vars['sidebar'] Sidebar view
 */

$sidebar = elgg_extract('sidebar', $vars);
if (empty($sidebar)) {
	return;
}

echo elgg_format_element('div', ['class' => ['elgg-sidebar', 'elgg-layout-sidebar']], $sidebar);
