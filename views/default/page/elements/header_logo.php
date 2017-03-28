<?php
/**
 * Elgg header logo
 */

$site = elgg_get_site_entity();
$site_name = $site->name;
$site_url = elgg_get_site_url();

echo elgg_view('output/url', [
	'href' => $site_url,
	'text' => $site->name,
	'class' => 'elgg-heading-site navbar-brand',
]);
