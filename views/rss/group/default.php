<?php
/**
 * RSS group view
 *
 * @package Elgg
 * @subpackage Core
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$permalink = htmlspecialchars($entity->getURL(), ENT_NOQUOTES, 'UTF-8');
$pubdate = date('r', $entity->getTimeCreated());
$title = htmlspecialchars($entity->getDisplayName(), ENT_NOQUOTES, 'UTF-8');

if ($entity->description) {
	$description = elgg_autop($entity->description);
} elseif ($entity->briefdescription) {
	$description = elgg_autop($entity->briefdescription);
} else {
	$description = '';
}

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
