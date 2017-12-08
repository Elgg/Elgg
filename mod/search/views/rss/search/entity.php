<?php
/**
 * Search entity view for RSS feeds.
 *
 * @uses $ars['entity']
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

// title cannot contain HTML but descriptions can.
$title = strip_tags($entity->getVolatileData('search_matched_title'));
$description = $entity->getVolatileData('search_matched_description');

?>

<item>
	<guid isPermaLink='true'><?php echo htmlspecialchars($entity->getURL()); ?></guid>
	<pubDate><?php echo date("r", $entity->time_created) ?></pubDate>
	<link><?php echo htmlspecialchars($entity->getURL()); ?></link>
	<title><![CDATA[<?php echo $title; ?>]]></title>
	<description><![CDATA[<?php echo $description; ?>]]></description>
</item>
