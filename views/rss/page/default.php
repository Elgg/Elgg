<?php
/**
 * Elgg RSS output pageshell
 *
 * @uses $vars['title']      The title of the RSS feed
 * @uses $vars['body']       The items for the RSS feed as a string
 * @uses $vars['descrption'] The description for the RSS feed
 */

use Elgg\Exceptions\Http\PageNotFoundException;

// Don't show RSS if disabled
if (elgg()->config->disable_rss) {
	throw new PageNotFoundException();
}

// Set title
$title = elgg_get_site_entity()->getDisplayName();
if (!empty($vars['title'])) {
	$title .= ": {$vars['title']}";
}

// Remove RSS from URL
$url = elgg_http_remove_url_query_element(elgg_get_current_url(), 'view');

$rssurl = htmlspecialchars($url, ENT_NOQUOTES, 'UTF-8');
$url = htmlspecialchars($url, ENT_NOQUOTES, 'UTF-8');

$body = elgg_extract('body', $vars, '');
$description = elgg_extract('description', $vars, '');

$namespaces = elgg_view('extensions/xmlns');
$extensions = elgg_view('extensions/channel');

// allow caching as required by stupid MS products for https feeds.
elgg_set_http_header('Pragma: public');
elgg_set_http_header('Content-Type: text/xml; charset=utf-8');

// echo is needed because of PHP short tag support
// https://www.php.net/manual/en/ini.core.php#ini.short-open-tag
echo "<?xml version='1.0'?>";
?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:georss="http://www.georss.org/georss" xmlns:atom="http://www.w3.org/2005/Atom" <?= $namespaces; ?>>
<channel>
	<title><![CDATA[<?= $title; ?>}]]></title>
	<link><?= $url; ?></link>
	<atom:link href="<?= $rssurl; ?>" rel="self" type="application/rss+xml" />
	<description><![CDATA[<?= $description; ?>}]]></description>
	<?= $extensions; ?>
	<?= $body; ?>
</channel>
</rss>
