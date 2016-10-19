<?php
/**
 * Quick introduction to the theme sandbox
 */

echo elgg_format_element([
	'#tag_name' => 'p',
	'#text' => elgg_echo('theme_sandbox:intro:help'),
]);
$simple_cache = elgg_get_config('simplecache_enabled');
$system_cache = elgg_is_system_cache_enabled();

if ($simple_cache || $system_cache) {
	$advanced = elgg_view('output/url', array(
		'text' => 'Advanced Settings',
		'href' => 'admin/settings/advanced',
		'is_trusted' => true
	));
	$developers = elgg_view('output/url', array(
		'text' => 'Developers\' Plugin Settings',
		'href' => 'admin/developers/settings',
		'is_trusted' => true
	));
	
	$body = "Caches are enabled. Changes you make to CSS and views might not appear. It is
	always recommended to disable caches while developing themes and plugins. To
	disable caches, visit the $advanced or $developers pages.";

	echo elgg_view_module('info', 'Warning', $body);
}
