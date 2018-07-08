<?php
/**
 * Advanced site settings, caching section.
 */

// simple cache
$is_simple_cache_on = (bool) elgg_get_config('simplecache_enabled');
$simple_cache_disabled_class = $is_simple_cache_on ? '' : 'elgg-state-disabled';

$params = [
	'label' => elgg_echo('installation:simplecache:label'),
	'name' => 'simplecache_enabled',
	'checked' => $is_simple_cache_on,
	'switch' => true,
];

$simple_cache_warning = elgg_echo('installation:simplecache:description');
if (_elgg_config()->hasInitialValue('simplecache_enabled')) {
	$params['class'] = 'elgg-state-disabled';
	$params['label_class'] = 'elgg-state-disabled';
	$params['disabled'] = true;
	
	$simple_cache_warning .= "<span class=\"elgg-text-help\">" . elgg_echo('admin:settings:in_settings_file') . "</span>";
}
$simple_cache_input = elgg_view("input/checkbox", $params);

$cache_symlinked = _elgg_is_cache_symlinked();
$params = [
	'label' => elgg_echo('installation:cache_symlink:label'),
	'name' => 'cache_symlink_enabled',
	'checked' => $cache_symlinked,
	'class' => $simple_cache_disabled_class,
	'label_class' => $simple_cache_disabled_class,
	'switch' => true,
];
$symlink_warning = '<p class="elgg-text-help">' . elgg_echo('installation:cache_symlink:description') . '</p>';
if ($cache_symlinked) {
	$params['class'] = 'elgg-state-disabled';
	$params['label_class'] = 'elgg-state-disabled';
	
	$symlink_warning .= elgg_format_element('span', ['class' => 'elgg-text-help'], elgg_echo('installation:cache_symlink:warning'));
}

$symlink_input = elgg_view('input/checkbox', $params);
$symlink_source = elgg_get_root_path() . 'cache/';
$symlink_target = elgg_get_asset_path();
$symlink_paths_help = elgg_echo('installation:cache_symlink:paths', [$symlink_source, $symlink_target]);
$symlink_warning .= elgg_format_element('p', ['class' => 'elgg-text-help'], $symlink_paths_help);

// minify
$minify_description = elgg_echo('installation:minify:description');
$minify_js_input = elgg_view("input/checkbox", [
	'label' => elgg_echo('installation:minify_js:label'),
	'name' => 'simplecache_minify_js',
	'checked' => (bool) elgg_get_config('simplecache_minify_js'),
	'label_class' => $simple_cache_disabled_class,
	'switch' => true,
]);

$minify_css_input = elgg_view("input/checkbox", [
	'label' => elgg_echo('installation:minify_css:label'),
	'name' => 'simplecache_minify_css',
	'checked' => (bool) elgg_get_config('simplecache_minify_css'),
	'label_class' => $simple_cache_disabled_class,
	'switch' => true,
]);

$system_cache_input = elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('installation:systemcache:label'),
	'help' => elgg_echo('installation:systemcache:description'),
	'name' => 'system_cache_enabled',
	'switch' => true,
	'checked' => elgg_is_system_cache_enabled(),
	'#class' => 'mtm',
]);

$body = <<<BODY
	<div>
		$simple_cache_input
		<p class="elgg-text-help">$simple_cache_warning</p>
	</div>
	<div>
		$symlink_input
		$symlink_warning
	</div>
	<div>
		<p>$minify_description</p>
		$minify_js_input<br />
		$minify_css_input
	</div>

	$system_cache_input
BODY;

echo elgg_view_module('info', elgg_echo('admin:legend:caching'), $body, ['id' => 'elgg-settings-advanced-caching']);
