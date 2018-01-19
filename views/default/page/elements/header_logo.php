<?php
/**
 * Elgg header logo
 */

$site = elgg_get_site_entity();

echo elgg_format_element('h1', ['class' => 'elgg-heading-site'], elgg_view('output/url', [
	'text' => $site->getDisplayName(),
	'href' => $site->getURL(),
	'is_trusted' => true,
]));
