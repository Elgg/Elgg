<?php
/**
 * Elgg admin header
 */
 
$admin_title = elgg_view('output/url', [
	'href' => elgg_get_site_url() . 'admin',
	'text' => elgg_get_site_entity()->name . ' ' . elgg_echo('admin'),
]);

echo elgg_format_element('h1', ['class' => 'elgg-heading-site'], $admin_title);

echo elgg_view('output/url', [
	'href' => '#elgg-admin-nav-collapse',
	'text' => '<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>',
	'class' => 'elgg-admin-button-nav',
	'rel' => 'toggle',
]);

echo elgg_view_menu('admin_header', ['sort_by' => 'priority']);
