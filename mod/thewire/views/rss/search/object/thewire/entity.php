<?php
/**
 * Elgg thewire.
 * Search entity view for RSS feeds.
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggWire) {
	return;
}

$title = '';
$owner = $entity->getOwnerEntity();
if ($owner instanceof \ElggEntity) {
	$title = elgg_echo('thewire:by', [$owner->getDisplayName()]);
}

$description = $entity->getVolatileData('search_matched_description');

?>
<item>
	<guid isPermaLink='false'><?php echo $entity->getGUID(); ?></guid>
	<pubDate><?php echo date('r', $entity->time_created) ?></pubDate>
	<link><?php echo htmlspecialchars($entity->getURL()); ?></link>
	<title><![CDATA[<?php echo $title; ?>]]></title>
	<description><![CDATA[<?php	echo $description; ?>]]></description>
</item>
