<?php
/**
 * Admin sidebar -- just outputs the page menus
 */

$content = elgg_view_menu('page', array('sort_by' => 'priority', 'show_section_headers' => true));

echo elgg_view_module('main', '', $content, array('class' => 'elgg-admin-sidebar-menu'));