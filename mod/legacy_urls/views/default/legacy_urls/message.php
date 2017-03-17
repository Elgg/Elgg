<?php
/**
 * Redirect message
 *
 * @uses $vars['url'] URL we're redirecting the user to (note: not relying on legacy site URL injection)
 */

$link = elgg_view('output/url', [
	'text' => elgg_normalize_url($vars['url']),
	'href' => $vars['url'],
]);
$message = elgg_echo('legacy_urls:message', [$link]);

echo "<h2>$message</h2>";
