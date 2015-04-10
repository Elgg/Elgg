<?php
/**
 * Advanced site settings, caching section.
 */

// simple cache
$is_simple_cache_on = (bool)elgg_get_config('simplecache_enabled');
$simple_cache_disabled_class = $is_simple_cache_on ? '' : 'elgg-state-disabled';

$params = array(
	'label' => elgg_echo('installation:simplecache:label'),
	'name' => 'simplecache_enabled',
	'checked' => $is_simple_cache_on,
);
if (elgg_get_config('simplecache_enabled_in_settings')) {
	$params['class'] = 'elgg-state-disabled';
	$params['label_class'] = 'elgg-state-disabled';
}
$simple_cache_input .= elgg_view("input/checkbox", $params);
$simple_cache_warning = '';

if (elgg_get_config('simplecache_enabled_in_settings')) {
	$warning = elgg_echo('admin:settings:in_settings_file');
	$simple_cache_warning .= "<span class=\"elgg-text-help\">$warning</span>";
}

// minify
$minify_js_input = elgg_view("input/checkbox", array(
	'label' => elgg_echo('installation:minify_js:label'),
	'name' => 'simplecache_minify_js',
	'checked' => (bool)elgg_get_config('simplecache_minify_js'),
	'label_class' => $simple_cache_disabled_class,
));

$minify_css_input = elgg_view("input/checkbox", array(
	'label' => elgg_echo('installation:minify_css:label'),
	'name' => 'simplecache_minify_css',
	'checked' => (bool)elgg_get_config('simplecache_minify_css'),
	'label_class' => $simple_cache_disabled_class,
));

$system_cache_input = elgg_view("input/checkbox", array(
	'label' => elgg_echo('installation:systemcache:label'),
	'name' => 'system_cache_enabled',
	'checked' => (bool)elgg_get_config('system_cache_enabled'),
))

?>
<fieldset class="elgg-fieldset" id="elgg-settings-advanced-caching">
	<legend><?php echo elgg_echo('admin:legend:caching'); ?></legend>
	
	<div>
		<?php echo $simple_cache_input; ?>
		<p class="elgg-text-help"><?php echo elgg_echo('installation:simplecache:description'); ?></p>
		<?php echo $simple_cache_warning; ?>
		
	</div>
	
	<div>
		<p><?php echo elgg_echo('installation:minify:description'); ?></p>
		<?php echo $minify_js_input; ?><br />
		<?php echo $minify_css_input; ?>
	</div>
	
	<div>
		<?php echo $system_cache_input; ?>
		<p class="elgg-text-help"><?php echo elgg_echo('installation:systemcache:description'); ?></p>
	</div>
	
</fieldset>