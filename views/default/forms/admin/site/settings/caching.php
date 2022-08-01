<?php
/**
 * Advanced site settings, caching section.
 */

// simple cache
$is_simple_cache_on = (bool) elgg_get_config('simplecache_enabled');

$params = [
	'#type' => 'checkbox',
	'#label' => elgg_echo('installation:simplecache:label'),
	'#help' => elgg_echo('installation:simplecache:description'),
	'name' => 'simplecache_enabled',
	'switch' => true,
];

if (elgg()->config->hasInitialValue('simplecache_enabled')) {
	$params['disabled'] = true;
	
	// set checked state based on value in settings.php
	$is_simple_cache_on = (bool) elgg()->config->getInitialValue('simplecache_enabled');
	
	$params['#help'] .= ' ' . elgg_echo('admin:settings:in_settings_file');
}

$params['checked'] = $is_simple_cache_on;
$simple_cache_disabled_class = $is_simple_cache_on ? '' : 'elgg-state-disabled';

$body = elgg_view_field($params);

$cache_symlinked = _elgg_is_cache_symlinked();

$help = elgg_echo('installation:cache_symlink:description');
$help .= elgg_echo('installation:cache_symlink:paths', [elgg_get_root_path() . 'cache/', elgg_get_asset_path()]);
if ($cache_symlinked) {
	$help .= elgg_format_element('p', [], elgg_echo('installation:cache_symlink:warning'));
}

$body .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('installation:cache_symlink:label'),
	'#help' => $help,
	'name' => 'cache_symlink_enabled',
	'checked' => $cache_symlinked,
	'disabled' => !$is_simple_cache_on,
	'switch' => true,
]);

// minify
$minify_help = elgg_echo('installation:minify:description');
$body .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('installation:minify_js:label'),
	'#help' => $minify_help,
	'name' => 'simplecache_minify_js',
	'checked' => (bool) elgg_get_config('simplecache_minify_js'),
	'disabled' => !$is_simple_cache_on,
	'label_class' => $simple_cache_disabled_class,
	'switch' => true,
]);

$body .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('installation:minify_css:label'),
	'#help' => $minify_help,
	'name' => 'simplecache_minify_css',
	'checked' => (bool) elgg_get_config('simplecache_minify_css'),
	'disabled' => !$is_simple_cache_on,
	'label_class' => $simple_cache_disabled_class,
	'switch' => true,
]);

$body .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('installation:systemcache:label'),
	'#help' => elgg_echo('installation:systemcache:description'),
	'#class' => 'mtm',
	'name' => 'system_cache_enabled',
	'switch' => true,
	'checked' => elgg_is_system_cache_enabled(),
]);

echo elgg_view_module('info', elgg_echo('admin:legend:caching'), $body, ['id' => 'elgg-settings-advanced-caching']);
