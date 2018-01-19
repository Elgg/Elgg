<?php

$site_link = elgg_view('output/url', [
	'text' => elgg_get_site_entity()->getDisplayName(),
	'href' => elgg_get_site_entity()->getURL(),
]);

echo elgg_format_element('h1', ['class' => 'elgg-heading-walled-garden'], $site_link);
