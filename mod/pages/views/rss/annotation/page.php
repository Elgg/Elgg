<?php
/**
 * Elgg RSS view for a page revision
 */

$revision = elgg_extract('annotation', $vars);
if (!$revision instanceof ElggAnnotation) {
	return;
}

$poster = $revision->getOwnerEntity();
$poster_name = htmlspecialchars($poster->getDisplayName(), ENT_NOQUOTES, 'UTF-8');
$pubdate = date('r', $revision->getTimeCreated());
$permalink = $revision->getURL();

$title = elgg_echo('pages:revision:subtitle', ['', $poster_name]);

$creator = elgg_view('page/components/creator', ['entity' => $revision]);
$extensions = elgg_view('pages/extensions/item', $vars);

$item = <<<__HTML
<item>
	<guid isPermaLink='true'>$permalink</guid>
	<pubDate>$pubdate</pubDate>
	<link>$permalink</link>
	<title><![CDATA[$title]]></title>
	<description><![CDATA[{$vars['annotation']->value}]]></description>
	$creator$extensions
</item>

__HTML;

echo $item;
