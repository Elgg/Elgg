<?php
/**
 * Elgg custom index layout
 *
 * This is just a helper view to make it easier to use Elgg's
 * page-rendering helper functions like elgg_view_page.
 */

$plugin = elgg_get_plugin_from_id('custom_index');
$is_module_enabled = function(string $module) use ($plugin) {
	return (bool) $plugin->{"module_{$module}_enabled"};
};

$get_list_params = function(array $extras = []) {
	return array_merge([
		'type' => 'object',
		'limit' => 4,
		'pagination' => false,
		'no_results' => true,
	], $extras);
};

$modules = [];

if ($is_module_enabled('about')) {
	$about = $plugin->about;
	if (!empty($about)) {
		$modules[] = elgg_view_module('featured', '', $about);
	}
}

if (!elgg_is_logged_in() && $is_module_enabled('register') && elgg_get_config('allow_registration')) {
	$modules[] = elgg_view_module('featured', elgg_echo('register'), elgg_view_form('register'));
}

if (!elgg_is_logged_in() && $is_module_enabled('login')) {
	$modules[] = elgg_view_module('featured', elgg_echo('login'), elgg_view_form('login'));
}

// activity
if (elgg_is_active_plugin('activity') && $is_module_enabled('activity')) {
	$modules[] = elgg_view_module('featured', elgg_echo('collection:river'), elgg_list_river([
		'distinct' => false,
		'no_results' => elgg_echo('river:none'),
		'limit' => 5,
		'pagination' => false,
	]));
}

// files
if (elgg_is_active_plugin('file') && $is_module_enabled('file')) {
	$modules[] = elgg_view_module('featured', elgg_echo('collection:object:file'), elgg_list_entities($get_list_params(['subtype' => 'file'])));
}

// groups
if (elgg_is_active_plugin('groups') && $is_module_enabled('groups')) {
	$modules[] = elgg_view_module('featured', elgg_echo('collection:group'), elgg_list_entities($get_list_params(['type' => 'group'])));
}

if ($is_module_enabled('users')) {
	$modules[] = elgg_view_module('featured', elgg_echo('collection:user'), elgg_list_entities($get_list_params(['type' => 'user'])));
}

// groups
if (elgg_is_active_plugin('blog') && $is_module_enabled('blog')) {
	$modules[] = elgg_view_module('featured', elgg_echo('collection:object:blog'), elgg_list_entities($get_list_params(['subtype' => 'blog'])));
}

// files
if (elgg_is_active_plugin('bookmarks') && $is_module_enabled('bookmarks')) {
	$modules[] = elgg_view_module('featured', elgg_echo('collection:object:bookmarks'), elgg_list_entities($get_list_params(['subtype' => 'bookmarks'])));
}

$left = '';
$right = '';

// spread modules evenly
foreach ($modules as $index => $module) {
	if ($index % 2 == 0) {
		$left .= $module;
	} else {
		$right .= $module;
	}
}

$left .= elgg_view('index/lefthandside');
$right .= elgg_view('index/righthandside');

$left = elgg_format_element('div', ['class' => ['elgg-col', 'elgg-col-1of2', 'custom-index-col1']], elgg_format_element('div', ['class' => 'elgg-inner'], $left));
$right = elgg_format_element('div', ['class' => ['elgg-col', 'elgg-col-2of2', 'custom-index-col2']], elgg_format_element('div', ['class' => 'elgg-inner'], $right));

echo elgg_format_element('div', ['class' => ['custom-index', 'elgg-main', 'elgg-grid', 'clearfix']], $left . $right);
