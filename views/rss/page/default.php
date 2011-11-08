<?php
/**
 * Elgg RSS output pageshell
 *
 * @package Elgg.Core
 *
 * @uses $vars['title']      The title of the RSS feed
 * @uses $vars['body']       The items for the RSS feed as a string
 * @uses $vars['descrption'] The description for the RSS feed
 */

// Set title
if (empty($vars['title'])) {
	$title = elgg_get_config('sitename');
} else {
	$title = elgg_get_config('sitename') . ": " . $vars['title'];
}

// Remove RSS from URL
$url = str_replace('?view=rss', '', full_url());
$url = str_replace('&view=rss', '', $url);
$url = htmlspecialchars($url, ENT_NOQUOTES, 'UTF-8');

$body = elgg_extract('body', $vars, '');
$description = elgg_extract('description', $vars, '');

$namespaces = elgg_view('extensions/xmlns');
$extensions = elgg_view('extensions/channel');


// allow caching as required by stupid MS products for https feeds.
header('Pragma: public', true);
header("Content-Type: text/xml");

echo "<?xml version='1.0'?>";
echo <<<END
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:georss="http://www.georss.org/georss" $namespaces>
<channel>
	<title><![CDATA[$title]]></title>
	<link>$url</link>
	<description><![CDATA[$description]]></description>
	$extensions
	$body
</channel>
</rss>
END;
