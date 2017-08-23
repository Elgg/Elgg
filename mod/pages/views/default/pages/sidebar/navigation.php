<?php
/**
 * Navigation menu for a user's or a group's pages
 *
 * @uses $vars['page'] Page object if manually setting selected item
 */

elgg_load_library('elgg:pages');

// add the jquery treeview files for navigation
elgg_load_css('jquery.treeview');
elgg_require_js('pages/sidebar/navigation');

$selected_page = elgg_extract('page', $vars, false);

$title = elgg_echo('pages:navigation');

pages_register_navigation_tree(elgg_get_page_owner_entity(), $selected_page);

$content = elgg_view_menu('pages_nav', [
    'class' => 'pages-nav',
]);

if (!$content) {
	$content = '<p>' . elgg_echo('pages:none') . '</p>';
}

echo elgg_view_module('aside', $title, $content);
