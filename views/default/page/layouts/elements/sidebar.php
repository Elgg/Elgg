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

echo elgg_format_element('aside', ['class' => 'elgg-layout-sidebar'], $sidebar);
