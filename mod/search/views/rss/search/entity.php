<?php
/**
 * Elgg core search.
 * Search entity view for RSS feeds.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd <info@elgg.com>, The MITRE Corporation <http://www.mitre.org>
 * @link http://elgg.org/
 */

if (!array_key_exists('entity', $vars) || !($vars['entity'] instanceof ElggEntity)) {
	return FALSE;
}

// title cannot contain HTML but descriptions can.
$title = strip_tags($vars['entity']->getVolatileData('search_matched_title'));
$description = $vars['entity']->getVolatileData('search_matched_description');

?>

<item>
	<guid isPermaLink='true'><?php echo htmlspecialchars($vars['entity']->getURL()); ?></guid>
	<pubDate><?php echo date("r", $vars['entity']->time_created) ?></pubDate>
	<link><?php echo htmlspecialchars($vars['entity']->getURL()); ?></link>
	<title><![CDATA[<?php echo $title; ?>]]></title>
	<description><![CDATA[<?php	echo $description; ?>]]></description>
</item>
