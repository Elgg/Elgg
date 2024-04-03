<?php
/**
 * Elgg custom index page contents
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

if ($is_module_enabled('about')) {
	$about = $plugin->about;
	if (!empty($about)) {
		echo elgg_view_module('info', '', $about);
	}
}

if (!elgg_is_logged_in() && $is_module_enabled('register') && elgg_get_config('allow_registration')) {
	echo elgg_view_module('info', elgg_echo('register'), elgg_view_form('register', [
		'sticky_enabled' => true,
		'sticky_ignored_fields' => [
			'password',
			'password2',
		],
	]));
}

if (!elgg_is_logged_in() && $is_module_enabled('login')) {
	echo elgg_view_module('info', elgg_echo('login'), elgg_view_form('login'));
}

if (elgg_is_active_plugin('activity') && $is_module_enabled('activity')) {
	echo elgg_view_module('info', elgg_echo('collection:river'), elgg_list_river([
		'distinct' => false,
		'no_results' => elgg_echo('river:none'),
		'limit' => 5,
		'pagination' => false,
	]));
}

if (elgg_is_active_plugin('file') && $is_module_enabled('file')) {
	echo elgg_view_module('info', elgg_echo('collection:object:file'), elgg_list_entities($get_list_params(['subtype' => 'file'])));
}

if (elgg_is_active_plugin('groups') && $is_module_enabled('groups')) {
	echo elgg_view_module('info', elgg_echo('collection:group'), elgg_list_entities($get_list_params(['type' => 'group'])));
}

if ($is_module_enabled('users')) {
	echo elgg_view_module('info', elgg_echo('collection:user'), elgg_list_entities($get_list_params(['type' => 'user'])));
}

if (elgg_is_active_plugin('blog') && $is_module_enabled('blog')) {
	echo elgg_view_module('info', elgg_echo('collection:object:blog'), elgg_list_entities($get_list_params(['subtype' => 'blog'])));
}

if (elgg_is_active_plugin('bookmarks') && $is_module_enabled('bookmarks')) {
	echo elgg_view_module('info', elgg_echo('collection:object:bookmarks'), elgg_list_entities($get_list_params(['subtype' => 'bookmarks'])));
}
