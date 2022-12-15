<?php
/**
 * Elgg thewire rss view
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggWire) {
	return;
}

$owner = $entity->getOwnerEntity();
if (!$owner instanceof \ElggEntity) {
	return;
}

$title = elgg_echo('thewire:by', [$owner->getDisplayName()]);

$permalink = htmlspecialchars($entity->getURL(), ENT_NOQUOTES, 'UTF-8');
$pubdate = date('r', $entity->getTimeCreated());

$description = elgg_autop($entity->description);

$creator = elgg_view('page/components/creator', $vars);
$georss = elgg_view('page/components/georss', $vars);
$extension = elgg_view('extensions/item', $vars);

?>
<item>
	<guid isPermaLink="true"><?= $permalink; ?></guid>
	<pubDate><?= $pubdate; ?></pubDate>
	<link><?= $permalink; ?></link>
	<title><![CDATA[<?= $title; ?>]]></title>
	<description><![CDATA[<?= $description; ?>]]></description>
	<?= $creator; ?>
	<?= $georss; ?>
	<?= $extension; ?>
</item>
