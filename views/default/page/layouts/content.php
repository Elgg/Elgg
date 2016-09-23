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
 * @uses $vars['filter_context'] Filter context: all, friends, mine
 * @uses $vars['filter_vars']    Additional context variables to pass to the filter menu
 * @uses $vars['class']          Additional class to apply to layout
 */

$context = elgg_extract('context', $vars, elgg_get_context());

$vars['title'] = elgg_extract('title', $vars, '');
if (!$vars['title'] && $vars['title'] !== false) {
	$vars['title'] = elgg_echo($context);
}

// 1.8 supported 'filter_override'
if (isset($vars['filter_override'])) {
	$vars['filter'] = $vars['filter_override'];
}

// register the default content filters
if (!isset($vars['filter'])) {
	$filter_context = elgg_extract('filter_context', $vars, 'all');
	$filter_vars = (array) elgg_extract('filter_vars', $vars, []);
	foreach (['all_link', 'mine_link', 'friends_link'] as $key) {
		if (isset($vars[$key])) {
			$filter_vars[$key] = $vars[$key];
		}
	}
	$vars['filter'] = elgg_get_filter_tabs($context, $filter_context, true, $params);
}

$filter = elgg_view('page/layouts/elements/filter', $vars);
$vars['content'] = $filter . $vars['content'];

echo elgg_view_layout('one_sidebar', $vars);
