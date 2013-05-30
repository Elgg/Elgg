<?php
/**
 * Main content area layout
 *
 * @uses $vars['content']        HTML of main content area
 * @uses $vars['sidebar']        HTML of the sidebar
 * @uses $vars['header']         HTML of the content area header (override)
 * @uses $vars['nav']            HTML of the content area nav (override)
 * @uses $vars['footer']         HTML of the content area footer
 * @uses $vars['filter']         HTML of the content area filter (override)
 * @uses $vars['title']          Title text (override)
 * @uses $vars['context']        Page context (override)
 * @uses $vars['filter_context'] Filter context: everyone, friends, mine
 * @uses $vars['class']          Additional class to apply to layout
 */

// give plugins an opportunity to add to content sidebars
$params = $vars;
$params['content'] = elgg_extract('sidebar', $vars, '');
$vars['sidebar'] = elgg_view('page/layouts/content/sidebar', $params);

if (!isset($vars['filter'])) {
	// tell body it should use a default filter menu
	$vars['filter'] = true;
}

echo elgg_view_layout('one_sidebar', $vars);
