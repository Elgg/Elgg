<?php
/**
 * RSS user view
 *
 * @package Elgg
 * @subpackage Core
 */

$permalink = htmlspecialchars($vars['entity']->getURL(), ENT_NOQUOTES, 'UTF-8');
$pubdate = date('r', $vars['entity']->getTimeCreated());
$title = htmlspecialchars($vars['entity']->name, ENT_NOQUOTES, 'UTF-8');

if ($vars['entity']->description) {
	$description = elgg_autop($vars['entity']->description);
} else {
	$description = '';
}

$georss = elgg_view('page/components/georss', $vars);
$extension = elgg_view('extensions/item', $vars);

$item = <<<__HTML
<item>
	<guid isPermaLink="true">$permalink</guid>
	<pubDate>$pubdate</pubDate>
	<link>$permalink</link>
	<title><![CDATA[$title]]></title>
	<description><![CDATA[$description]]></description>
	$georss$extension
</item>

__HTML;

echo $item;
