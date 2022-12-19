<?php

$site = elgg_get_site_entity();
header('Content-type: text/plain; charset=utf-8');

$content = $site->getMetadata('robots.txt');
$plugin_content = elgg_trigger_event_results('robots.txt', 'site', ['site' => $site], '');
if (!empty($plugin_content) && is_string($plugin_content)) {
	$content .= PHP_EOL . PHP_EOL . $plugin_content;
}

echo $content;
