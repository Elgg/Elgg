<?php
/**
 * Advanced site settings, caching section.
 */

// simple cache
$is_simple_cache_on = (bool) elgg_get_config('simplecache_enabled');

$params = [
	'#type' => 'switch',
	'#label' => elgg_echo('installation:simplecache:label'),
	'#help' => elgg_echo('installation:simplecache:description'),
	'name' => 'simplecache_enabled',
];

if (elgg()->config->hasInitialValue('simplecache_enabled')) {
	$params['disabled'] = true;
	
	// set checked state based on value in settings.php
	$is_simple_cache_on = (bool) elgg()->config->getInitialValue('simplecache_enabled');
	
	$params['#help'] .= ' ' . elgg_echo('admin:settings:in_settings_file');
}

$params['value'] = $is_simple_cache_on;
$simple_cache_disabled_class = $is_simple_cache_on ? '' : 'elgg-state-disabled';

$body = elgg_view_field($params);

$cache_symlinked = _elgg_services()->simpleCache->isSymbolicLinked();

$help = elgg_echo('installation:cache_symlink:description');
$help .= elgg_echo('installation:cache_symlink:paths', [elgg_get_root_path() . 'cache/', elgg_get_asset_path()]);
if ($cache_symlinked) {
	$help .= elgg_format_element('p', [], elgg_echo('installation:cache_symlink:warning'));
}

$body .= elgg_view_field([
	'#type' => 'fieldset',
	'#class' => ['elgg-divide-left', 'plm'],
	'fields' => [
		[
			'#type' => 'switch',
			'#label' => elgg_echo('installation:cache_symlink:label'),
			'#help' => $help,
			'name' => 'cache_symlink_enabled',
			'value' => $cache_symlinked,
			'disabled' => !$is_simple_cache_on,
		],
		[
			'#type' => 'switch',
			'#label' => elgg_echo('installation:minify_js:label'),
			'#help' => elgg_echo('installation:minify:description'),
			'name' => 'simplecache_minify_js',
			'value' => elgg_get_config('simplecache_minify_js'),
			'disabled' => !$is_simple_cache_on,
			'label_class' => $simple_cache_disabled_class,
		],
		[
			'#type' => 'switch',
			'#label' => elgg_echo('installation:minify_css:label'),
			'#help' => elgg_echo('installation:minify:description'),
			'name' => 'simplecache_minify_css',
			'value' => elgg_get_config('simplecache_minify_css'),
			'disabled' => !$is_simple_cache_on,
			'label_class' => $simple_cache_disabled_class,
		],
	],
]);

$body .= elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('installation:systemcache:label'),
	'#help' => elgg_echo('installation:systemcache:description'),
	'name' => 'system_cache_enabled',
	'value' => _elgg_services()->systemCache->isEnabled(),
]);

echo elgg_view_module('info', elgg_echo('admin:legend:caching'), $body, ['id' => 'elgg-settings-advanced-caching']);
