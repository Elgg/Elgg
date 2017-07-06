<?php

$site = elgg_get_site_entity();
header("Content-type: text/plain;charset=utf-8");

$content = $site->getPrivateSetting('robots.txt');
$plugin_content = elgg_trigger_plugin_hook('robots.txt', 'site', ['site' => $site], '');
if ($plugin_content) {
	$content = $content . "\n\n" . $plugin_content;
}
echo $content;
