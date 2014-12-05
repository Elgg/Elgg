<?php

$site = elgg_get_site_entity();
header("Content-type: text/plain");

$content = $site->getPrivateSetting('robots.txt');
$plugin_content = elgg_trigger_plugin_hook('robots.txt', 'site', array('site' => $site), '');
if ($plugin_content) {
	$content = $content . "\n\n" . $plugin_content;
}
echo $content;
