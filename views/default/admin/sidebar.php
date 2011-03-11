<?php
/**
 * Admin sidebar -- just outputs the page menus
 */
$sections = array('administer', 'configure', 'develop');
$content = '';

foreach ($sections as $section) {
	$section_content = elgg_view_menu("admin-$section", array('sort_by' => 'priority', 'class' => 'mbl mts'));
	
	if ($section_content) {
		$content .= '<h2>' . elgg_echo("admin:$section") . '</h2>';
		$content .= $section_content;
	}
}

echo elgg_view_module('main', '', $content, array('class' => 'elgg-admin-sidebar-menu'));