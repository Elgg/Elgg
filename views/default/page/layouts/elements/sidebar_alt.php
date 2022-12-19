<?php
/**
 * Second layout sidebar
 *
 * @uses $vars['sidebar_alt'] Sidebar view
 */

$sidebar_alt = elgg_extract('sidebar_alt', $vars);
if (empty($sidebar_alt)) {
	return;
}

echo elgg_format_element('div', ['class' => ['elgg-sidebar-alt', 'elgg-layout-sidebar-alt']], $sidebar_alt);
