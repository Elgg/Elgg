<?php
/**
 * Elgg thewire.
 * Search entity view for RSS feeds.
 */

if (!array_key_exists('entity', $vars)) {
	return false;
}

$owner = $vars['entity']->getOwnerEntity();
if ($owner) {
	$title = elgg_echo('thewire:by', [$owner->getDisplayName()]);
}
$description = $vars['entity']->getVolatileData('search_matched_description');

?>

<item>
	<guid isPermaLink='false'><?php echo $vars['entity']->getGUID(); ?></guid>
	<pubDate><?php echo date("r", $vars['entity']->time_created) ?></pubDate>
	<link><?php echo htmlspecialchars($vars['entity']->getURL()); ?></link>
	<title><![CDATA[<?php echo $title; ?>]]></title>
	<description><![CDATA[<?php	echo $description; ?>]]></description>
</item>
