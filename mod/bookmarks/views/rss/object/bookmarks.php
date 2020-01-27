<?php
/**
 * Bookmark RSS object view
 */

$title = $vars['entity']->getDisplayName();
if (empty($title)) {
	$title = strip_tags($vars['entity']->description);
	$title = elgg_get_excerpt($title, 32);
}

$permalink = htmlspecialchars($vars['entity']->getURL(), ENT_NOQUOTES, 'UTF-8');
$pubdate = date('r', $vars['entity']->getTimeCreated());

$url_text = elgg_echo('bookmarks:address');
$link = elgg_view('output/url', ['href' => $vars['entity']->address]);
$description = $vars['entity']->description . "<p>$url_text: $link</p>";

$creator = elgg_view('page/components/creator', $vars);
$georss = elgg_view('page/components/georss', $vars);
$extension = elgg_view('extensions/item');

$item = <<<__HTML
<item>
	<guid isPermaLink="true">$permalink</guid>
	<pubDate>$pubdate</pubDate>
	<link>$permalink</link>
	<title><![CDATA[$title]]></title>
	<description><![CDATA[$description]]></description>
	$creator$georss$extension
</item>

__HTML;

echo $item;
