<?php
/**
 * RSS user view
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggUser) {
	return;
}

$permalink = htmlspecialchars($entity->getURL(), ENT_NOQUOTES, 'UTF-8');
$pubdate = date('r', $entity->getTimeCreated());
$title = htmlspecialchars($entity->getDisplayName(), ENT_NOQUOTES, 'UTF-8');

$description = $entity->getProfileData('description');
if (!empty($description)) {
	$description = elgg_autop($description);
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
