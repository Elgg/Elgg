<?php
/**
 * Generic performance overview, more detailed information can be
 * added to additional tabs in /admin/performance
 */

echo elgg_view('output/longtext', [
	'value' => elgg_echo('admin:performance:generic:description'),
]);

$icon_ok = elgg_view_icon('check');
$icon_warning = elgg_view_icon('exclamation-triangle');
$icon_error = elgg_view_icon('times');

$view_module = function($icon, $title, $value = '', $subtext = '') {
	$body = elgg_format_element('strong', [], $title);
	if (!elgg_is_empty($value)) {
		$body .= elgg_format_element('span', ['class' => 'mlm'], $value);
	}
	
	if (!elgg_is_empty($subtext)) {
		$body .= elgg_format_element('div', ['class' => 'elgg-subtext'], $subtext);
	}
	
	return elgg_view_image_block($icon, $body, ['class' => 'elgg-admin-information-row']);
};

// apache version
// Check if the function exists before callling it else it may fail in case of Nginx or other Non Apache servers
if (function_exists('apache_get_version')) {
	if (apache_get_version() !== false) {
		$icon =  $icon_warning;
		$title = elgg_echo('admin:performance:apache:mod_cache');
		$value = elgg_echo('status:unavailable');
		$subtext = '';
		
		if (in_array('mod_cache', apache_get_modules())) {
			$icon = $icon_ok;
			$value = elgg_echo('status:enabled');
		} else {
			$subtext = elgg_echo('admin:performance:apache:mod_cache:warning');
		}
		
		echo $view_module($icon, $title, $value, $subtext);
	}
}

// open_basedir
$icon = $icon_ok;
$title = elgg_echo('admin:performance:php:open_basedir');
$value = elgg_echo('admin:performance:php:open_basedir:not_configured');
$subtext = '';

$open_basedirs = ini_get('open_basedir');
if (!empty($open_basedirs)) {
	$icon = $icon_warning;
	$value = elgg_format_element('span', ['class' => ['elgg-subtext']], $open_basedirs);
	
	$separator = ':';
	if (stripos(PHP_OS, 'WIN') === 0) {
		$open_basedirs = $separator = ';';
	}
	
	$parsed_open_basedirs = explode($separator, $open_basedirs);
	
	if (count($parsed_open_basedirs) > 5) {
		$icon = $icon_error;
		$subtext = elgg_echo('admin:performance:php:open_basedir:error');
	} else {
		$subtext = elgg_echo('admin:performance:php:open_basedir:warning');
	}
	
	$subtext .= ' ' . elgg_echo('admin:performance:php:open_basedir:generic');
}

echo $view_module($icon, $title, $value, $subtext);

// opcache
$icon = $icon_error;
$title = elgg_echo('admin:server:label:opcache');
$value = elgg_echo('status:unavailable');
$subtext = '';

if (function_exists('opcache_get_status')) {
	$icon = $icon_warning;
	$opcache_status = opcache_get_status(false);
	
	if (!empty($opcache_status)) {
		$icon = $icon_ok;
		$value = elgg_echo('status:enabled');
	} else {
		$value = elgg_echo('status:disabled');
		$subtext = elgg_echo('admin:server:opcache:inactive');
	}
}

echo $view_module($icon, $title, $value, $subtext);

// memcache
$icon = $icon_error;
$title = elgg_echo('admin:server:label:memcache');
$value = elgg_echo('status:unavailable');
$subtext = '';

if (\Stash\Driver\Memcache::isAvailable()) {
	$icon = $icon_warning;
	
	if (elgg_get_config('memcache') && !empty(elgg_get_config('memcache_servers'))) {
		$icon = $icon_ok;
		$value = elgg_echo('status:enabled');
	} else {
		$value = elgg_echo('status:disabled');
		$subtext = elgg_echo('admin:server:memcache:inactive');
	}
}

echo $view_module($icon, $title, $value, $subtext);

// redis
$icon = $icon_error;
$title = elgg_echo('admin:server:label:redis');
$value = elgg_echo('status:unavailable');
$subtext = '';

if (\Stash\Driver\Redis::isAvailable()) {
	$icon = $icon_warning;
	
	if (elgg_get_config('redis') && !empty(elgg_get_config('redis_servers'))) {
		$icon = $icon_ok;
		$value = elgg_echo('status:enabled');
	} else {
		$value = elgg_echo('status:disabled');
		$subtext = elgg_echo('admin:server:redis:inactive');
	}
}

echo $view_module($icon, $title, $value, $subtext);

// simplecache
$icon = $icon_error;
$title = elgg_view('output/url', [
	'text' => elgg_echo('admin:performance:simplecache'),
	'href' => elgg_generate_url('admin', [
		'segments' => 'site_settings',
	]) . '#elgg-settings-advanced-caching',
	'is_trusted' => true,
]);
$value = elgg_echo('status:disabled');
$subtext = elgg_echo('installation:simplecache:description');

if (elgg_is_simplecache_enabled()) {
	$icon = $icon_ok;
	$value = elgg_echo('status:enabled');
	
	if (!elgg()->config->hasInitialValue('simplecache_enabled')) {
		$icon = $icon_warning;
		$subtext = elgg_echo('admin:performance:simplecache:settings:warning');
	}
}

echo $view_module($icon, $title, $value, $subtext);

// systemcache
$icon = $icon_error;
$title = elgg_view('output/url', [
	'text' => elgg_echo('admin:performance:systemcache'),
	'href' => elgg_generate_url('admin', [
		'segments' => 'site_settings',
	]) . '#elgg-settings-advanced-caching',
	'is_trusted' => true,
]);
$value = elgg_echo('status:disabled');
$subtext = elgg_echo('installation:systemcache:description');

if (elgg_is_system_cache_enabled()) {
	$icon = $icon_ok;
	$value = elgg_echo('status:enabled');
	$subtext = '';
}

echo $view_module($icon, $title, $value, $subtext);
