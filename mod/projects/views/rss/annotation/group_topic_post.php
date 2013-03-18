<?php
/**
 * RSS view for a discussion reply
 *
 * @uses $vars['annotation']
 */

$annotation = $vars['annotation'];

$poster = $annotation->getOwnerEntity();
$poster_name = htmlspecialchars($poster->name, ENT_NOQUOTES, 'UTF-8');
$pubdate = date('r', $annotation->getTimeCreated());
$permalink = $annotation->getURL();

$title = elgg_echo('discussion:reply:title', array($poster_name));

$creator = elgg_view('page/components/creator', array('entity' => $annotation));
$extensions = elgg_view('extensions/item', $vars);

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
