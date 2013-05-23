<?php
/**
 * Redirect message
 * 
 * @uses $vars['url']
 */

$link = elgg_view('output/url', array(
	'text' => elgg_normalize_url($vars['url']),
	'href' => $vars['url'],
));
$message = elgg_echo('legacy_urls:message', array($link));

echo "<h2>$message</h2>";
