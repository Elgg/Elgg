<?php
/**
 * RSS object view
 *
 * @package Elgg
 * @subpackage Core
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$title = $entity->getDisplayName();
if (empty($title)) {
	$title = strip_tags($entity->description);
	$title = elgg_get_excerpt($title, 32);
}

$permalink = htmlspecialchars($entity->getURL(), ENT_NOQUOTES, 'UTF-8');
$pubdate = date('r', $entity->getTimeCreated());

$description = elgg_autop($entity->description);

$creator = elgg_view('page/components/creator', $vars);
$georss = elgg_view('page/components/georss', $vars);
$extension = elgg_view('extensions/item', $vars);

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
