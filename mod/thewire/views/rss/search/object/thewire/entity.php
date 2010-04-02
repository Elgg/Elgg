<?php
/**
 * Elgg thewire.
 * Search entity view for RSS feeds.
 *
 * @package ElggTheWire
 * @link http://elgg.org/
 */

if (!array_key_exists('entity', $vars)) {
	return FALSE;
}

$owner = $vars['entity']->getOwnerEntity();
if ($owner) {
	$title = sprintf(elgg_echo('thewire:by'), $owner->name);
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
