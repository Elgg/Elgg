<?php
/**
 * Quick introduction to the theme sandbox
 *
 * @todo links to resources?
 */
?>

<p>This theme sandbox provides a visual catalog for many of the theming elements
	that Elgg uses. The primary css selector is listed with each theme element.
	The sandbox is divided into sections that are listed in the sidebar.
</p>
<?php
$simple_cache = elgg_get_config('simplecache_enabled');
$system_cache = elgg_is_system_cache_enabled();

if ($simple_cache || $system_cache) {
	$advanced = elgg_view_url('admin/site_settings', 'Advanced Settings');
	$developers = elgg_view_url('admin/developers/settings', 'Developers\' Plugin Settings');
	
	$body = "Caches are enabled. Changes you make to CSS and views might not appear. It is
	always recommended to disable caches while developing themes and plugins. To
	disable caches, visit the $advanced or $developers pages.";

	echo elgg_view_message('warning', $body);
}
