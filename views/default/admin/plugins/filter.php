<?php
/**
 * Category filter for plugins
 *
 * @uses $vars['category_options']
 * @uses $vars['active_filter']
 */

$categories = elgg_extract('category_options', $vars);
if (empty($categories)) {
	return;
}

$input_filter = elgg_extract('active_filter', $vars);

$list_items = '';
foreach ($categories as $key => $category) {
	if (empty($key)) {
		continue;
	}

	$key = preg_replace('/[^a-z0-9-]/i', '-', elgg_strtolower($key));
	
	$options = [];
	if ($key === $input_filter) {
		$options['class'] = 'elgg-state-selected';
	}
	$list_items .= elgg_format_element('li', $options, elgg_view_url('#', $category, ['rel' => $key]));
}

$body = elgg_format_element([
	'#tag_name' => 'ul',
	'class' => 'elgg-admin-plugins-categories elgg-admin-sidebar-menu elgg-menu-hz',
	'#text' => $list_items,
]);

echo elgg_view_module('', elgg_echo('filter'), $body, [
	'id' => 'plugins-filter',
]);
