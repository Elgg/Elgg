<?php
/**
 * Install completion page
 */

echo elgg_autop(elgg_echo('install:complete:instructions'));

$link = elgg_view('output/url', [
	'text' => elgg_echo('install:complete:gotosite'),
	'href' => elgg_get_site_url(),
	'class' => ['elgg-button', 'elgg-button-action'],
]);

echo elgg_format_element('div', ['class' => 'elgg-install-nav'], $link);
