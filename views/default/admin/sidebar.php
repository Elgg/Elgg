<?php
/**
 * Admin sidebar -- just outputs the page menus
 */

$menu = elgg_view_menu('page', [
	'show_section_headers' => true,
	'prepare_vertical' => true,
]);

echo elgg_format_element('div', [
	'class' => 'elgg-admin-sidebar-menu',
	'id' => 'elgg-admin-nav-collapse'
], $menu);
